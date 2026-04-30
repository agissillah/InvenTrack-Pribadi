<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase Order | InvenTrack</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen">
    <main class="max-w-6xl mx-auto px-6 py-10">
        <header class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
            <div>
                <p class="text-cyan-400 text-sm font-semibold">Manajemen Pengadaan</p>
                <h1 class="text-3xl font-bold">Purchase Order</h1>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('purchase-orders.create') }}" class="px-4 py-2 rounded-md bg-cyan-400 text-slate-900 font-semibold hover:bg-cyan-300 transition-colors">Tambah Purchase Order</a>
                <a href="{{ url('/') }}" class="px-4 py-2 rounded-md border border-slate-700 text-slate-200 hover:bg-slate-900 transition-colors">Kembali ke Landing</a>
            </div>
        </header>

        @if (session('success'))
            <div class="mb-6 rounded-md border border-emerald-500/40 bg-emerald-500/10 px-4 py-3 text-emerald-300">
                {{ session('success') }}
            </div>
        @endif

        @php
            $statusStyles = [
                'draft' => 'bg-slate-700/60 text-slate-200',
                'approved' => 'bg-emerald-500/20 text-emerald-300',
                'ordered' => 'bg-cyan-500/20 text-cyan-300',
                'received' => 'bg-indigo-500/20 text-indigo-300',
                'canceled' => 'bg-red-500/20 text-red-300',
            ];
        @endphp

        <div class="overflow-x-auto rounded-xl border border-slate-800 bg-slate-900/60">
            <table class="min-w-full text-sm">
                <thead class="bg-slate-900">
                    <tr>
                        <th class="text-left px-4 py-3">Nomor PO</th>
                        <th class="text-left px-4 py-3">Tanggal</th>
                        <th class="text-left px-4 py-3">PR Terkait</th>
                        <th class="text-left px-4 py-3">Supplier</th>
                        <th class="text-left px-4 py-3">Status</th>
                        <th class="text-right px-4 py-3">Total</th>
                        <th class="text-center px-4 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($purchaseOrders as $purchaseOrder)
                        @php
                            $total = $purchaseOrder->items->sum('subtotal');
                        @endphp
                        <tr class="border-t border-slate-800">
                            <td class="px-4 py-3 font-semibold">{{ $purchaseOrder->nomor_po }}</td>
                            <td class="px-4 py-3 text-slate-300">{{ $purchaseOrder->tanggal }}</td>
                            <td class="px-4 py-3 text-slate-300">{{ $purchaseOrder->purchaseRequest->nomor_pr ?? '-' }}</td>
                            <td class="px-4 py-3">{{ $purchaseOrder->supplier_nama }}</td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold {{ $statusStyles[$purchaseOrder->status] ?? 'bg-slate-700/60 text-slate-200' }}">
                                    {{ ucfirst($purchaseOrder->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-right">Rp {{ number_format($total, 2, ',', '.') }}</td>
                            <td class="px-4 py-3">
                                <div class="flex justify-center gap-2">
                                    <a href="{{ route('purchase-orders.show', $purchaseOrder) }}" class="px-3 py-1 rounded border border-cyan-700 text-cyan-300 hover:bg-cyan-500/10">Detail</a>
                                    <a href="{{ route('purchase-orders.edit', $purchaseOrder) }}" class="px-3 py-1 rounded border border-amber-700 text-amber-300 hover:bg-amber-500/10">Edit</a>
                                    <form action="{{ route('purchase-orders.destroy', $purchaseOrder) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-3 py-1 rounded border border-red-700 text-red-300 hover:bg-red-500/10">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-slate-400">Belum ada Purchase Order. Klik Tambah untuk mulai input data.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $purchaseOrders->links() }}
        </div>
    </main>
</body>
</html>
