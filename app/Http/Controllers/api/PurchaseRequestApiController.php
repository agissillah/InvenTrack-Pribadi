<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PurchaseRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PurchaseRequestApiController extends Controller
{
    /**
     * GET /api/purchase-requests
     * Daftar semua Purchase Request (dengan pagination).
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = $request->integer('per_page', 10);

        $purchaseRequests = PurchaseRequest::query()
            ->with('items')
            ->latest()
            ->paginate($perPage);

        return response()->json($purchaseRequests);
    }

    /**
     * POST /api/purchase-requests
     * Tambah Purchase Request baru.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nomor_pr'          => ['required', 'string', 'max:50', 'unique:purchase_requests,nomor_pr'],
            'tanggal'           => ['required', 'date'],
            'requester'         => ['required', 'string', 'max:255'],
            'status'            => ['required', 'string', 'in:' . implode(',', array_keys(PurchaseRequest::statusOptions()))],
            'catatan'           => ['nullable', 'string'],
            'items'             => ['required', 'array', 'min:1'],
            'items.*.nama_barang' => ['required', 'string', 'max:255'],
            'items.*.sku'       => ['nullable', 'string', 'max:100'],
            'items.*.qty'       => ['required', 'integer', 'min:1'],
            'items.*.satuan'    => ['required', 'string', 'max:50'],
        ]);

        $purchaseRequest = PurchaseRequest::create([
            'nomor_pr'  => $validated['nomor_pr'],
            'tanggal'   => $validated['tanggal'],
            'requester' => $validated['requester'],
            'status'    => $validated['status'],
            'catatan'   => $validated['catatan'] ?? null,
        ]);

        $purchaseRequest->items()->createMany(
            $this->prepareItems($validated['items'])
        );

        $purchaseRequest->load('items');

        return response()->json($purchaseRequest, 201);
    }

    /**
     * GET /api/purchase-requests/{id}
     * Detail Purchase Request.
     */
    public function show(PurchaseRequest $purchaseRequest): JsonResponse
    {
        $purchaseRequest->load('items');

        return response()->json($purchaseRequest);
    }

    /**
     * PUT /api/purchase-requests/{id}
     * Update Purchase Request.
     */
    public function update(Request $request, PurchaseRequest $purchaseRequest): JsonResponse
    {
        $validated = $request->validate([
            'nomor_pr'          => ['required', 'string', 'max:50', 'unique:purchase_requests,nomor_pr,' . $purchaseRequest->id],
            'tanggal'           => ['required', 'date'],
            'requester'         => ['required', 'string', 'max:255'],
            'status'            => ['required', 'string', 'in:' . implode(',', array_keys(PurchaseRequest::statusOptions()))],
            'catatan'           => ['nullable', 'string'],
            'items'             => ['required', 'array', 'min:1'],
            'items.*.nama_barang' => ['required', 'string', 'max:255'],
            'items.*.sku'       => ['nullable', 'string', 'max:100'],
            'items.*.qty'       => ['required', 'integer', 'min:1'],
            'items.*.satuan'    => ['required', 'string', 'max:50'],
        ]);

        $purchaseRequest->update([
            'nomor_pr'  => $validated['nomor_pr'],
            'tanggal'   => $validated['tanggal'],
            'requester' => $validated['requester'],
            'status'    => $validated['status'],
            'catatan'   => $validated['catatan'] ?? null,
        ]);

        $purchaseRequest->items()->delete();
        $purchaseRequest->items()->createMany(
            $this->prepareItems($validated['items'])
        );

        $purchaseRequest->load('items');

        return response()->json($purchaseRequest);
    }

    /**
     * DELETE /api/purchase-requests/{id}
     * Hapus Purchase Request.
     */
    public function destroy(PurchaseRequest $purchaseRequest): JsonResponse
    {
        $purchaseRequest->delete();

        return response()->json(['message' => 'Purchase Request berhasil dihapus.']);
    }

    /**
     * Normalisasi item sebelum disimpan.
     */
    private function prepareItems(array $items): array
    {
        return collect($items)
            ->map(fn ($item) => [
                'nama_barang' => $item['nama_barang'],
                'sku'         => ($item['sku'] ?? '') !== '' ? $item['sku'] : null,
                'qty'         => (int) $item['qty'],
                'satuan'      => $item['satuan'],
            ])
            ->values()
            ->all();
    }
}