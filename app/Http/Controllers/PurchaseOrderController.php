<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use App\Models\PurchaseRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Controller resource untuk modul Purchase Order.
 */
class PurchaseOrderController extends Controller
{
    /**
     * Menampilkan daftar Purchase Order dengan pagination.
     */
    public function index(): View
    {
        $purchaseOrders = PurchaseOrder::query()
            ->with(['purchaseRequest', 'items'])
            ->latest()
            ->paginate(10);

        return view('purchase-orders.index', compact('purchaseOrders'));
    }

    /**
     * Menampilkan form tambah Purchase Order.
     */
    public function create(): View
    {
        $purchaseOrder = new PurchaseOrder();
        $statusOptions = PurchaseOrder::statusOptions();
        $purchaseRequests = PurchaseRequest::query()->with('items')->latest()->get();
        $purchaseRequestPayloads = $purchaseRequests
            ->map(function ($purchaseRequest) {
                return [
                    'id' => $purchaseRequest->id,
                    'items' => $purchaseRequest->items
                        ->map(function ($item) {
                            return [
                                'id' => $item->id,
                                'nama_barang' => $item->nama_barang,
                                'sku' => $item->sku,
                                'qty' => $item->qty,
                                'satuan' => $item->satuan,
                            ];
                        })
                        ->values(),
                ];
            })
            ->values();
        $priceByItemId = [];
        $priceBySignature = [];

        return view(
            'purchase-orders.create',
            compact(
                'purchaseOrder',
                'statusOptions',
                'purchaseRequests',
                'purchaseRequestPayloads',
                'priceByItemId',
                'priceBySignature'
            )
        );
    }

    /**
     * Menyimpan data Purchase Order baru ke database.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'purchase_request_id' => ['required', 'integer', 'exists:purchase_requests,id'],
            'nomor_po' => ['required', 'string', 'max:50', 'unique:purchase_orders,nomor_po'],
            'tanggal' => ['required', 'date'],
            'supplier_nama' => ['required', 'string', 'max:255'],
            'supplier_kontak' => ['required', 'string', 'max:100'],
            'supplier_alamat' => ['required', 'string'],
            'status' => ['required', 'string', 'in:' . implode(',', array_keys(PurchaseOrder::statusOptions()))],
            'catatan' => ['nullable', 'string'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.purchase_request_item_id' => ['required', 'integer', 'exists:purchase_request_items,id'],
            'items.*.harga' => ['required', 'numeric', 'min:0'],
        ]);

        $purchaseRequest = PurchaseRequest::query()->with('items')->findOrFail($validated['purchase_request_id']);

        if ($purchaseRequest->items->isEmpty()) {
            return back()
                ->withErrors(['purchase_request_id' => 'Purchase Request belum memiliki item.'])
                ->withInput();
        }

        $submittedItems = collect($validated['items']);
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
            return back()
                ->withErrors(['items' => 'Item Purchase Order harus berasal dari Purchase Request yang dipilih.'])
                ->withInput();
        }

        $itemsPayload = $this->buildItemsFromPurchaseRequest($purchaseRequest, $submittedItems);

        $purchaseOrder = PurchaseOrder::create([
            'purchase_request_id' => $validated['purchase_request_id'],
            'nomor_po' => $validated['nomor_po'],
            'tanggal' => $validated['tanggal'],
            'supplier_nama' => $validated['supplier_nama'],
            'supplier_kontak' => $validated['supplier_kontak'],
            'supplier_alamat' => $validated['supplier_alamat'],
            'status' => $validated['status'],
            'catatan' => $validated['catatan'] ?? null,
        ]);

        $purchaseOrder->items()->createMany($itemsPayload);

        return redirect()
            ->route('purchase-orders.index')
            ->with('success', 'Purchase Order berhasil ditambahkan.');
    }

    /**
     * Menampilkan detail Purchase Order.
     */
    public function show(PurchaseOrder $purchaseOrder): View
    {
        $purchaseOrder->load(['purchaseRequest', 'items']);

        return view('purchase-orders.show', compact('purchaseOrder'));
    }

    /**
     * Menampilkan form edit Purchase Order.
     */
    public function edit(PurchaseOrder $purchaseOrder): View
    {
        $purchaseOrder->load('items');
        $statusOptions = PurchaseOrder::statusOptions();
        $purchaseRequests = PurchaseRequest::query()->with('items')->latest()->get();
        $purchaseRequestPayloads = $purchaseRequests
            ->map(function ($purchaseRequest) {
                return [
                    'id' => $purchaseRequest->id,
                    'items' => $purchaseRequest->items
                        ->map(function ($item) {
                            return [
                                'id' => $item->id,
                                'nama_barang' => $item->nama_barang,
                                'sku' => $item->sku,
                                'qty' => $item->qty,
                                'satuan' => $item->satuan,
                            ];
                        })
                        ->values(),
                ];
            })
            ->values();
        $priceByItemId = $purchaseOrder->items
            ->filter(fn ($item) => !empty($item->purchase_request_item_id))
            ->mapWithKeys(fn ($item) => [(string) $item->purchase_request_item_id => (float) $item->harga])
            ->all();
        $priceBySignature = $purchaseOrder->items
            ->mapWithKeys(function ($item) {
                return [
                    $this->buildItemSignature(
                        $item->nama_barang,
                        $item->sku,
                        (int) $item->qty,
                        $item->satuan
                    ) => (float) $item->harga,
                ];
            })
            ->all();

        return view(
            'purchase-orders.edit',
            compact(
                'purchaseOrder',
                'statusOptions',
                'purchaseRequests',
                'purchaseRequestPayloads',
                'priceByItemId',
                'priceBySignature'
            )
        );
    }

    /**
     * Memperbarui data Purchase Order.
     */
    public function update(Request $request, PurchaseOrder $purchaseOrder): RedirectResponse
    {
        $validated = $request->validate([
            'purchase_request_id' => ['required', 'integer', 'exists:purchase_requests,id'],
            'nomor_po' => ['required', 'string', 'max:50', 'unique:purchase_orders,nomor_po,' . $purchaseOrder->id],
            'tanggal' => ['required', 'date'],
            'supplier_nama' => ['required', 'string', 'max:255'],
            'supplier_kontak' => ['required', 'string', 'max:100'],
            'supplier_alamat' => ['required', 'string'],
            'status' => ['required', 'string', 'in:' . implode(',', array_keys(PurchaseOrder::statusOptions()))],
            'catatan' => ['nullable', 'string'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.purchase_request_item_id' => ['required', 'integer', 'exists:purchase_request_items,id'],
            'items.*.harga' => ['required', 'numeric', 'min:0'],
        ]);

        $purchaseRequest = PurchaseRequest::query()->with('items')->findOrFail($validated['purchase_request_id']);

        if ($purchaseRequest->items->isEmpty()) {
            return back()
                ->withErrors(['purchase_request_id' => 'Purchase Request belum memiliki item.'])
                ->withInput();
        }

        $submittedItems = collect($validated['items']);
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
            return back()
                ->withErrors(['items' => 'Item Purchase Order harus berasal dari Purchase Request yang dipilih.'])
                ->withInput();
        }

        $itemsPayload = $this->buildItemsFromPurchaseRequest($purchaseRequest, $submittedItems);

        $purchaseOrder->update([
            'purchase_request_id' => $validated['purchase_request_id'],
            'nomor_po' => $validated['nomor_po'],
            'tanggal' => $validated['tanggal'],
            'supplier_nama' => $validated['supplier_nama'],
            'supplier_kontak' => $validated['supplier_kontak'],
            'supplier_alamat' => $validated['supplier_alamat'],
            'status' => $validated['status'],
            'catatan' => $validated['catatan'] ?? null,
        ]);

        $purchaseOrder->items()->delete();
        $purchaseOrder->items()->createMany($itemsPayload);

        return redirect()
            ->route('purchase-orders.index')
            ->with('success', 'Purchase Order berhasil diperbarui.');
    }

    /**
     * Menghapus data Purchase Order.
     */
    public function destroy(PurchaseOrder $purchaseOrder): RedirectResponse
    {
        $purchaseOrder->delete();

        return redirect()
            ->route('purchase-orders.index')
            ->with('success', 'Purchase Order berhasil dihapus.');
    }

    /**
     * Normalisasi item agar subtotal konsisten.
     *
     * @param array<int, array<string, mixed>> $items
     * @return array<int, array<string, mixed>>
     */
    private function buildItemSignature(string $namaBarang, ?string $sku, int $qty, string $satuan): string
    {
        return $namaBarang . '|' . ($sku ?? '') . '|' . $qty . '|' . $satuan;
    }

    /**
     * Bangun item PO dari item PR dan harga yang diinput user.
     *
     * @param PurchaseRequest $purchaseRequest
     * @param \Illuminate\Support\Collection<int, array<string, mixed>> $submittedItems
     * @return array<int, array<string, mixed>>
     */
    private function buildItemsFromPurchaseRequest(PurchaseRequest $purchaseRequest, $submittedItems): array
    {
        $priceByItemId = $submittedItems
            ->mapWithKeys(function ($item) {
                return [(int) $item['purchase_request_item_id'] => (float) $item['harga']];
            });

        return $purchaseRequest->items
            ->map(function ($item) use ($priceByItemId) {
                $harga = $priceByItemId->get($item->id, 0);

                return [
                    'purchase_request_item_id' => $item->id,
                    'nama_barang' => $item->nama_barang,
                    'sku' => $item->sku,
                    'qty' => $item->qty,
                    'satuan' => $item->satuan,
                    'harga' => $harga,
                    'subtotal' => $item->qty * $harga,
                ];
            })
            ->values()
            ->all();
    }
}
