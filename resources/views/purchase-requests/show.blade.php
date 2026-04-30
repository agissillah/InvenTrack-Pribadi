<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Purchase Request | InvenTrack</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen">
    <main class="max-w-5xl mx-auto px-6 py-10">
        <header class="flex items-center justify-between gap-4 mb-8">
            <div>
                <p class="text-cyan-400 text-sm font-semibold">Read</p>
                <h1 class="text-3xl font-bold">Detail Purchase Request</h1>
            </div>
            <a href="{{ route('purchase-requests.index') }}" class="px-4 py-2 rounded-md border border-slate-700 text-slate-200 hover:bg-slate-900 transition-colors">Kembali</a>
        </header>

        <section class="rounded-xl border border-slate-800 bg-slate-900/60 p-6 space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-slate-400">Nomor PR</p>
                    <p class="text-lg font-semibold">{{ $purchaseRequest->nomor_pr }}</p>
                </div>
                <div>
                    <p class="text-sm text-slate-400">Tanggal</p>
                    <p class="text-lg">{{ $purchaseRequest->tanggal }}</p>
                </div>
                <div>
                    <p class="text-sm text-slate-400">Requester</p>
                    <p class="text-lg">{{ $purchaseRequest->requester }}</p>
                </div>
                <div>
                    <p class="text-sm text-slate-400">Status</p>
                    <p class="text-lg capitalize">{{ $purchaseRequest->status }}</p>
                </div>
                <div>
                    <p class="text-sm text-slate-400">Catatan</p>
                    <p class="text-lg whitespace-pre-line">{{ $purchaseRequest->catatan ?: '-' }}</p>
                </div>
            </div>

            <div>
                <h2 class="text-lg font-semibold mb-3">Item Purchase Request</h2>
                <div class="overflow-x-auto rounded-lg border border-slate-800">
                    <table class="min-w-full text-sm">
                        <thead class="bg-slate-900">
                            <tr>
                                <th class="text-left px-3 py-2">Nama Barang</th>
                                <th class="text-left px-3 py-2">SKU</th>
                                <th class="text-right px-3 py-2">Qty</th>
                                <th class="text-left px-3 py-2">Satuan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $totalQty = 0;
                            @endphp
                            @foreach ($purchaseRequest->items as $item)
                                @php
                                    $totalQty += $item->qty;
                                @endphp
                                <tr class="border-t border-slate-800">
                                    <td class="px-3 py-2">{{ $item->nama_barang }}</td>
                                    <td class="px-3 py-2 text-slate-300">{{ $item->sku ?: '-' }}</td>
                                    <td class="px-3 py-2 text-right">{{ number_format($item->qty) }}</td>
                                    <td class="px-3 py-2">{{ $item->satuan }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-slate-900">
                            <tr>
                                <td colspan="4" class="px-3 py-3 text-right font-semibold">Total Qty</td>
                                <td class="px-3 py-3 text-right font-semibold">{{ number_format($totalQty) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <div class="pt-3 flex flex-wrap gap-3">
                <a href="{{ route('purchase-requests.edit', $purchaseRequest) }}" class="px-4 py-2 rounded-md border border-amber-700 text-amber-300 hover:bg-amber-500/10">Edit</a>
                <form action="{{ route('purchase-requests.destroy', $purchaseRequest) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data ini?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 rounded-md border border-red-700 text-red-300 hover:bg-red-500/10">Hapus</button>
                </form>
            </div>
        </section>
    </main>
</body>
</html>
