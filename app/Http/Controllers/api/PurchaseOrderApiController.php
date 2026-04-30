<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PurchaseOrder;
use App\Models\PurchaseRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PurchaseOrderApiController extends Controller
{
    /**
     * GET /api/purchase-orders
     * Daftar semua Purchase Order (dengan pagination).
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = $request->integer('per_page', 10);

        $purchaseOrders = PurchaseOrder::query()
            ->with(['purchaseRequest', 'items'])
            ->latest()
            ->paginate($perPage);

        return response()->json($purchaseOrders);
    }

    /**
     * POST /api/purchase-orders
     * Tambah Purchase Order baru.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'purchase_request_id'              => ['required', 'integer', 'exists:purchase_requests,id'],
            'nomor_po'                         => ['required', 'string', 'max:50', 'unique:purchase_orders,nomor_po'],
            'tanggal'                          => ['required', 'date'],
            'supplier_nama'                    => ['required', 'string', 'max:255'],
            'supplier_kontak'                  => ['required', 'string', 'max:100'],
            'supplier_alamat'                  => ['required', 'string'],
            'status'                           => ['required', 'string', 'in:' . implode(',', array_keys(PurchaseOrder::statusOptions()))],
            'catatan'                          => ['nullable', 'string'],
            'items'                            => ['required', 'array', 'min:1'],
            'items.*.purchase_request_item_id' => ['required', 'integer', 'exists:purchase_request_items,id'],
            'items.*.harga'                    => ['required', 'numeric', 'min:0'],
        ]);

        $purchaseRequest = PurchaseRequest::query()
            ->with('items')
            ->findOrFail($validated['purchase_request_id']);

        if ($purchaseRequest->items->isEmpty()) {
            return response()->json([
                'message' => 'Purchase Request belum memiliki item.',
                'errors'  => ['purchase_request_id' => ['Purchase Request belum memiliki item.']],
            ], 422);
        }

        $submittedItems = collect($validated['items']);
        $error = $this->validateItemsBelongToPR($purchaseRequest, $submittedItems);
        if ($error) {
            return response()->json(['message' => $error, 'errors' => ['items' => [$error]]], 422);
        }

        $purchaseOrder = PurchaseOrder::create([
            'purchase_request_id' => $validated['purchase_request_id'],
            'nomor_po'            => $validated['nomor_po'],
            'tanggal'             => $validated['tanggal'],
            'supplier_nama'       => $validated['supplier_nama'],
            'supplier_kontak'     => $validated['supplier_kontak'],
            'supplier_alamat'     => $validated['supplier_alamat'],
            'status'              => $validated['status'],
            'catatan'             => $validated['catatan'] ?? null,
        ]);

        $purchaseOrder->items()->createMany(
            $this->buildItemsFromPurchaseRequest($purchaseRequest, $submittedItems)
        );

        $purchaseOrder->load(['purchaseRequest', 'items']);

        return response()->json($purchaseOrder, 201);
    }

    /**
     * GET /api/purchase-orders/{id}
     * Detail Purchase Order.
     */
    public function show(PurchaseOrder $purchaseOrder): JsonResponse
    {
        $purchaseOrder->load(['purchaseRequest', 'items']);

        return response()->json($purchaseOrder);
    }

    /**
     * PUT /api/purchase-orders/{id}
     * Update Purchase Order.
     */
    public function update(Request $request, PurchaseOrder $purchaseOrder): JsonResponse
    {
        $validated = $request->validate([
            'purchase_request_id'              => ['required', 'integer', 'exists:purchase_requests,id'],
            'nomor_po'                         => ['required', 'string', 'max:50', 'unique:purchase_orders,nomor_po,' . $purchaseOrder->id],
            'tanggal'                          => ['required', 'date'],
            'supplier_nama'                    => ['required', 'string', 'max:255'],
            'supplier_kontak'                  => ['required', 'string', 'max:100'],
            'supplier_alamat'                  => ['required', 'string'],
            'status'                           => ['required', 'string', 'in:' . implode(',', array_keys(PurchaseOrder::statusOptions()))],
            'catatan'                          => ['nullable', 'string'],
            'items'                            => ['required', 'array', 'min:1'],
            'items.*.purchase_request_item_id' => ['required', 'integer', 'exists:purchase_request_items,id'],
            'items.*.harga'                    => ['required', 'numeric', 'min:0'],
        ]);

        $purchaseRequest = PurchaseRequest::query()
            ->with('items')
            ->findOrFail($validated['purchase_request_id']);

        if ($purchaseRequest->items->isEmpty()) {
            return response()->json([
                'message' => 'Purchase Request belum memiliki item.',
                'errors'  => ['purchase_request_id' => ['Purchase Request belum memiliki item.']],
            ], 422);
        }

        $submittedItems = collect($validated['items']);
        $error = $this->validateItemsBelongToPR($purchaseRequest, $submittedItems);
        if ($error) {
            return response()->json(['message' => $error, 'errors' => ['items' => [$error]]], 422);
        }

        $purchaseOrder->update([
            'purchase_request_id' => $validated['purchase_request_id'],
            'nomor_po'            => $validated['nomor_po'],
            'tanggal'             => $validated['tanggal'],
            'supplier_nama'       => $validated['supplier_nama'],
            'supplier_kontak'     => $validated['supplier_kontak'],
            'supplier_alamat'     => $validated['supplier_alamat'],
            'status'              => $validated['status'],
            'catatan'             => $validated['catatan'] ?? null,
        ]);

        $purchaseOrder->items()->delete();
        $purchaseOrder->items()->createMany(
            $this->buildItemsFromPurchaseRequest($purchaseRequest, $submittedItems)
        );

        $purchaseOrder->load(['purchaseRequest', 'items']);

        return response()->json($purchaseOrder);
    }

    /**
     * DELETE /api/purchase-orders/{id}
     * Hapus Purchase Order.
     */
    public function destroy(PurchaseOrder $purchaseOrder): JsonResponse
    {
        $purchaseOrder->delete();

        return response()->json(['message' => 'Purchase Order berhasil dihapus.']);
    }

    /**
     * Validasi bahwa semua item yang dikirim berasal dari PR yang dipilih.
     */
    private function validateItemsBelongToPR(PurchaseRequest $purchaseRequest, $submittedItems): ?string
    {
        $submittedIds = $submittedItems
            ->pluck('purchase_request_item_id')
            ->map(fn ($id) => (int) $id);
        $expectedIds = $purchaseRequest->items
            ->pluck('id')
            ->map(fn ($id) => (int) $id);

        if (
            $submittedIds->count() !== $expectedIds->count()
            || $submittedIds->diff($expectedIds)->isNotEmpty()
            || $expectedIds->diff($submittedIds)->isNotEmpty()
        ) {
            return 'Item Purchase Order harus berasal dari Purchase Request yang dipilih.';
        }

        return null;
    }

    /**
     * Bangun payload item PO dari item PR + harga yang diinput.
     */
    private function buildItemsFromPurchaseRequest(PurchaseRequest $purchaseRequest, $submittedItems): array
    {
        $priceByItemId = $submittedItems->mapWithKeys(
            fn ($item) => [(int) $item['purchase_request_item_id'] => (float) $item['harga']]
        );

        return $purchaseRequest->items
            ->map(function ($item) use ($priceByItemId) {
                $harga = $priceByItemId->get($item->id, 0);

                return [
                    'purchase_request_item_id' => $item->id,
                    'nama_barang'              => $item->nama_barang,
                    'sku'                      => $item->sku,
                    'qty'                      => $item->qty,
                    'satuan'                   => $item->satuan,
                    'harga'                    => $harga,
                    'subtotal'                 => $item->qty * $harga,
                ];
            })
            ->values()
            ->all();
    }
}