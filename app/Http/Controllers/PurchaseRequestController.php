<?php

namespace App\Http\Controllers;

use App\Models\PurchaseRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Controller resource untuk modul Purchase Request.
 */
class PurchaseRequestController extends Controller
{
    /**
     * Menampilkan daftar Purchase Request dengan pagination.
     */
    public function index(): View
    {
        $purchaseRequests = PurchaseRequest::query()
            ->with('items')
            ->latest()
            ->paginate(10);

        return view('purchase-requests.index', compact('purchaseRequests'));
    }

    /**
     * Menampilkan form tambah Purchase Request.
     */
    public function create(): View
    {
        $purchaseRequest = new PurchaseRequest();
        $items = [
            [
                'nama_barang' => '',
                'sku' => '',
                'qty' => 1,
                'satuan' => '',
            ],
        ];

        $statusOptions = PurchaseRequest::statusOptions();

        return view('purchase-requests.create', compact('purchaseRequest', 'items', 'statusOptions'));
    }

    /**
     * Menyimpan data Purchase Request baru ke database.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nomor_pr' => ['required', 'string', 'max:50', 'unique:purchase_requests,nomor_pr'],
            'tanggal' => ['required', 'date'],
            'requester' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'in:' . implode(',', array_keys(PurchaseRequest::statusOptions()))],
            'catatan' => ['nullable', 'string'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.nama_barang' => ['required', 'string', 'max:255'],
            'items.*.sku' => ['nullable', 'string', 'max:100'],
            'items.*.qty' => ['required', 'integer', 'min:1'],
            'items.*.satuan' => ['required', 'string', 'max:50'],
        ]);

        $purchaseRequest = PurchaseRequest::create([
            'nomor_pr' => $validated['nomor_pr'],
            'tanggal' => $validated['tanggal'],
            'requester' => $validated['requester'],
            'status' => $validated['status'],
            'catatan' => $validated['catatan'] ?? null,
        ]);

        $purchaseRequest->items()->createMany($this->prepareItems($validated['items']));

        return redirect()
            ->route('purchase-requests.index')
            ->with('success', 'Purchase Request berhasil ditambahkan.');
    }

    /**
     * Menampilkan detail Purchase Request.
     */
    public function show(PurchaseRequest $purchaseRequest): View
    {
        $purchaseRequest->load('items');

        return view('purchase-requests.show', compact('purchaseRequest'));
    }

    /**
     * Menampilkan form edit Purchase Request.
     */
    public function edit(PurchaseRequest $purchaseRequest): View
    {
        $purchaseRequest->load('items');

        $items = $purchaseRequest->items
            ->map(function ($item) {
                return [
                    'nama_barang' => $item->nama_barang,
                    'sku' => $item->sku,
                    'qty' => $item->qty,
                    'satuan' => $item->satuan,
                ];
            })
            ->toArray();

        $statusOptions = PurchaseRequest::statusOptions();

        return view('purchase-requests.edit', compact('purchaseRequest', 'items', 'statusOptions'));
    }

    /**
     * Memperbarui data Purchase Request.
     */
    public function update(Request $request, PurchaseRequest $purchaseRequest): RedirectResponse
    {
        $validated = $request->validate([
            'nomor_pr' => ['required', 'string', 'max:50', 'unique:purchase_requests,nomor_pr,' . $purchaseRequest->id],
            'tanggal' => ['required', 'date'],
            'requester' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'in:' . implode(',', array_keys(PurchaseRequest::statusOptions()))],
            'catatan' => ['nullable', 'string'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.nama_barang' => ['required', 'string', 'max:255'],
            'items.*.sku' => ['nullable', 'string', 'max:100'],
            'items.*.qty' => ['required', 'integer', 'min:1'],
            'items.*.satuan' => ['required', 'string', 'max:50'],
        ]);

        $purchaseRequest->update([
            'nomor_pr' => $validated['nomor_pr'],
            'tanggal' => $validated['tanggal'],
            'requester' => $validated['requester'],
            'status' => $validated['status'],
            'catatan' => $validated['catatan'] ?? null,
        ]);

        $purchaseRequest->items()->delete();
        $purchaseRequest->items()->createMany($this->prepareItems($validated['items']));

        return redirect()
            ->route('purchase-requests.index')
            ->with('success', 'Purchase Request berhasil diperbarui.');
    }

    /**
     * Menghapus data Purchase Request.
     */
    public function destroy(PurchaseRequest $purchaseRequest): RedirectResponse
    {
        $purchaseRequest->delete();

        return redirect()
            ->route('purchase-requests.index')
            ->with('success', 'Purchase Request berhasil dihapus.');
    }

    /**
     * Normalisasi item agar subtotal konsisten.
     *
     * @param array<int, array<string, mixed>> $items
     * @return array<int, array<string, mixed>>
     */
    private function prepareItems(array $items): array
    {
        return collect($items)
            ->map(function ($item) {
                $qty = (int) $item['qty'];

                return [
                    'nama_barang' => $item['nama_barang'],
                    'sku' => ($item['sku'] ?? '') !== '' ? $item['sku'] : null,
                    'qty' => $qty,
                    'satuan' => $item['satuan'],
                ];
            })
            ->values()
            ->all();
    }
}
