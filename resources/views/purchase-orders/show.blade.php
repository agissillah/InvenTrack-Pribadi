<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Purchase Order | InvenTrack</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen">
    <main class="max-w-5xl mx-auto px-6 py-10">
        <header class="flex items-center justify-between gap-4 mb-8">
            <div>
                <p class="text-cyan-400 text-sm font-semibold">Read</p>
                <h1 class="text-3xl font-bold">Detail Purchase Order</h1>
            </div>
            <a href="{{ route('purchase-orders.index') }}" class="px-4 py-2 rounded-md border border-slate-700 text-slate-200 hover:bg-slate-900 transition-colors">Kembali</a>
        </header>

        <section class="rounded-xl border border-slate-800 bg-slate-900/60 p-6 space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-slate-400">Nomor PO</p>
                    <p class="text-lg font-semibold">{{ $purchaseOrder->nomor_po }}</p>
                </div>
                <div>
                    <p class="text-sm text-slate-400">Tanggal</p>
                    <p class="text-lg">{{ $purchaseOrder->tanggal }}</p>
                </div>
                <div>
                    <p class="text-sm text-slate-400">PR Terkait</p>
                    <p class="text-lg">{{ $purchaseOrder->purchaseRequest->nomor_pr ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-sm text-slate-400">Status</p>
                    <p class="text-lg capitalize">{{ $purchaseOrder->status }}</p>
                </div>
                <div>
                    <p class="text-sm text-slate-400">Supplier</p>
                    <p class="text-lg">{{ $purchaseOrder->supplier_nama }}</p>
                </div>
                <div>
                    <p class="text-sm text-slate-400">Kontak Supplier</p>
                    <p class="text-lg">{{ $purchaseOrder->supplier_kontak }}</p>
                </div>
                <div class="md:col-span-2">
                    <p class="text-sm text-slate-400">Alamat Supplier</p>
                    <p class="text-lg whitespace-pre-line">{{ $purchaseOrder->supplier_alamat }}</p>
                </div>
                <div class="md:col-span-2">
                    <p class="text-sm text-slate-400">Catatan</p>
                    <p class="text-lg whitespace-pre-line">{{ $purchaseOrder->catatan ?: '-' }}</p>
                </div>
            </div>

            <div>
                <h2 class="text-lg font-semibold mb-3">Item Purchase Order</h2>
                <div class="overflow-x-auto rounded-lg border border-slate-800">
                    <table class="min-w-full text-sm">
                        <thead class="bg-slate-900">
                            <tr>
                                <th class="text-left px-3 py-2">Nama Barang</th>
                                <th class="text-left px-3 py-2">SKU</th>
                                <th class="text-right px-3 py-2">Qty</th>
                                <th class="text-left px-3 py-2">Satuan</th>
                                <th class="text-right px-3 py-2">Harga</th>
                                <th class="text-right px-3 py-2">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $total = 0;
                            @endphp
                            @foreach ($purchaseOrder->items as $item)
                                @php
                                    $total += $item->subtotal;
                                @endphp
                                <tr class="border-t border-slate-800">
                                    <td class="px-3 py-2">{{ $item->nama_barang }}</td>
                                    <td class="px-3 py-2 text-slate-300">{{ $item->sku ?: '-' }}</td>
                                    <td class="px-3 py-2 text-right">{{ number_format($item->qty) }}</td>
                                    <td class="px-3 py-2">{{ $item->satuan }}</td>
                                    <td class="px-3 py-2 text-right">Rp {{ number_format($item->harga, 2, ',', '.') }}</td>
                                    <td class="px-3 py-2 text-right">Rp {{ number_format($item->subtotal, 2, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-slate-900">
                            <tr>
                                <td colspan="5" class="px-3 py-3 text-right font-semibold">Total</td>
                                <td class="px-3 py-3 text-right font-semibold">Rp {{ number_format($total, 2, ',', '.') }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <div class="pt-3 flex flex-wrap gap-3">
                <a href="{{ route('purchase-orders.edit', $purchaseOrder) }}" class="px-4 py-2 rounded-md border border-amber-700 text-amber-300 hover:bg-amber-500/10">Edit</a>
                <form action="{{ route('purchase-orders.destroy', $purchaseOrder) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data ini?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 rounded-md border border-red-700 text-red-300 hover:bg-red-500/10">Hapus</button>
                </form>
            </div>
        </section>
    </main>
</body>
</html>
