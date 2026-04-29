<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD Barang | InvenTrack</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen">
    {{-- Kontainer utama halaman daftar barang (READ list). --}}
    <main class="max-w-6xl mx-auto px-6 py-10">
        {{-- Header berisi judul halaman + aksi global. --}}
        <header class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
            <div>
                <p class="text-cyan-400 text-sm font-semibold">Manajemen Data</p>
                <h1 class="text-3xl font-bold">CRUD Barang</h1>
            </div>
            <div class="flex flex-wrap gap-3">
                {{-- Tombol ke halaman CREATE. --}}
                <a href="{{ route('barang.create') }}" class="px-4 py-2 rounded-md bg-cyan-400 text-slate-900 font-semibold hover:bg-cyan-300 transition-colors">Tambah Barang</a>
                <a href="{{ url('/') }}" class="px-4 py-2 rounded-md border border-slate-700 text-slate-200 hover:bg-slate-900 transition-colors">Kembali ke Landing</a>
            </div>
        </header>

        {{-- Flash message sukses setelah create/update/delete. --}}
        @if (session('success'))
            <div class="mb-6 rounded-md border border-emerald-500/40 bg-emerald-500/10 px-4 py-3 text-emerald-300">
                {{ session('success') }}
            </div>
        @endif

        {{-- List card untuk mobile agar lebih rapi. --}}
        <div class="md:hidden space-y-4">
            @forelse ($barangs as $barang)
                <article class="rounded-xl border border-slate-800 bg-slate-900/60 p-4">
                    <div class="flex items-start gap-4">
                        <div class="h-16 w-16 rounded-md border border-slate-700 bg-slate-900/80 flex items-center justify-center overflow-hidden">
                            @php
                                $gambarUrl = null;

                                if (!empty($barang->gambar)) {
                                    $gambarPath = $barang->gambar;

                                    if (\Illuminate\Support\Str::startsWith($gambarPath, ['http://', 'https://'])) {
                                        $gambarUrl = $gambarPath;
                                    } else {
                                        $gambarPath = ltrim($gambarPath, '/');
                                        $gambarUrl = \Illuminate\Support\Str::startsWith($gambarPath, 'storage/')
                                            ? asset($gambarPath)
                                            : asset('storage/' . $gambarPath);
                                    }
                                }
                            @endphp
                            @if ($gambarUrl)
                                <button
                                    type="button"
                                    data-lightbox-src="{{ $gambarUrl }}"
                                    data-lightbox-alt="Gambar {{ $barang->nama }}"
                                    class="inline-flex"
                                >
                                    <img src="{{ $gambarUrl }}" alt="Gambar {{ $barang->nama }}" class="h-16 w-16 object-cover">
                                </button>
                            @else
                                <span class="text-xs text-slate-500">No Img</span>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <h2 class="text-lg font-semibold truncate">{{ $barang->nama }}</h2>
                            <p class="text-xs text-slate-400">SKU: {{ $barang->sku }}</p>
                            <div class="mt-3 grid grid-cols-2 gap-3 text-sm">
                                <div>
                                    <p class="text-slate-400">Stok</p>
                                    <p class="font-semibold">{{ number_format($barang->stok) }}</p>
                                </div>
                                <div>
                                    <p class="text-slate-400">Harga</p>
                                    <p class="font-semibold">Rp {{ number_format($barang->harga, 2, ',', '.') }}</p>
                                </div>
                            </div>
                            <p class="mt-3 text-sm text-slate-300">
                                {{ \Illuminate\Support\Str::limit($barang->deskripsi, 80) }}
                            </p>
                        </div>
                    </div>
                    <div class="mt-4 flex flex-wrap gap-2">
                        <a href="{{ route('barang.show', $barang) }}" class="px-3 py-1 rounded border border-cyan-700 text-cyan-300 hover:bg-cyan-500/10">Detail</a>
                        <a href="{{ route('barang.edit', $barang) }}" class="px-3 py-1 rounded border border-amber-700 text-amber-300 hover:bg-amber-500/10">Edit</a>
                        <form action="{{ route('barang.destroy', $barang) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-3 py-1 rounded border border-red-700 text-red-300 hover:bg-red-500/10">Hapus</button>
                        </form>
                    </div>
                </article>
            @empty
                <div class="rounded-xl border border-slate-800 bg-slate-900/60 px-4 py-8 text-center text-slate-400">
                    Belum ada data barang. Klik Tambah Barang untuk mulai input data.
                </div>
            @endforelse
        </div>

        {{-- Tabel data barang untuk layar md ke atas. --}}
        <div class="hidden md:block overflow-x-auto rounded-xl border border-slate-800 bg-slate-900/60">
            <table class="min-w-full text-sm">
                <thead class="bg-slate-900">
                    <tr>
                        <th class="text-left px-4 py-3">Nama</th>
                        <th class="text-left px-4 py-3">Gambar</th>
                        <th class="text-left px-4 py-3">SKU</th>
                        <th class="text-right px-4 py-3">Stok</th>
                        <th class="text-right px-4 py-3">Harga</th>
                        <th class="text-left px-4 py-3">Deskripsi</th>
                        <th class="text-center px-4 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- Jika data ada, render per baris. Jika kosong, tampilkan state kosong. --}}
                    @forelse ($barangs as $barang)
                        <tr class="border-t border-slate-800">
                            <td class="px-4 py-3 font-semibold">{{ $barang->nama }}</td>
                            <td class="px-4 py-3">
                                @php
                                    $gambarUrl = null;

                                    if (!empty($barang->gambar)) {
                                        $gambarPath = $barang->gambar;

                                        if (\Illuminate\Support\Str::startsWith($gambarPath, ['http://', 'https://'])) {
                                            $gambarUrl = $gambarPath;
                                        } else {
                                            $gambarPath = ltrim($gambarPath, '/');
                                            $gambarUrl = \Illuminate\Support\Str::startsWith($gambarPath, 'storage/')
                                                ? asset($gambarPath)
                                                : asset('storage/' . $gambarPath);
                                        }
                                    }
                                @endphp
                                @if ($gambarUrl)
                                    <button
                                        type="button"
                                        data-lightbox-src="{{ $gambarUrl }}"
                                        data-lightbox-alt="Gambar {{ $barang->nama }}"
                                        class="inline-flex"
                                    >
                                        <img src="{{ $gambarUrl }}" alt="Gambar {{ $barang->nama }}" class="h-12 w-12 rounded-md object-cover border border-slate-700 hover:ring-2 hover:ring-cyan-400/60">
                                    </button>
                                @else
                                    <span class="text-slate-500">-</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-slate-300">{{ $barang->sku }}</td>
                            <td class="px-4 py-3 text-right">{{ number_format($barang->stok) }}</td>
                            <td class="px-4 py-3 text-right">Rp {{ number_format($barang->harga, 2, ',', '.') }}</td>
                            <td class="px-4 py-3 text-slate-300">{{ \Illuminate\Support\Str::limit($barang->deskripsi, 50) }}</td>
                            <td class="px-4 py-3">
                                {{-- Aksi READ detail, UPDATE edit, dan DELETE hapus. --}}
                                <div class="flex justify-center gap-2">
                                    <a href="{{ route('barang.show', $barang) }}" class="px-3 py-1 rounded border border-cyan-700 text-cyan-300 hover:bg-cyan-500/10">Detail</a>
                                    <a href="{{ route('barang.edit', $barang) }}" class="px-3 py-1 rounded border border-amber-700 text-amber-300 hover:bg-amber-500/10">Edit</a>
                                    {{-- Form delete memakai method spoofing DELETE + konfirmasi browser. --}}
                                    <form action="{{ route('barang.destroy', $barang) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-3 py-1 rounded border border-red-700 text-red-300 hover:bg-red-500/10">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-slate-400">Belum ada data barang. Klik Tambah Barang untuk mulai input data.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Navigasi pagination dari Laravel (next/prev/nomor halaman). --}}
        <div class="mt-6">
            {{ $barangs->links() }}
        </div>
    </main>

    <div id="image-lightbox" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-950/80 px-6 py-8">
        <div class="relative inline-flex flex-col items-center">
            <button type="button" data-lightbox-close class="absolute -top-8 right-0 text-sm font-semibold text-slate-200 hover:text-white">Tutup</button>
            <img id="image-lightbox-img" src="" alt="" class="max-h-[85vh] max-w-[90vw] w-auto h-auto rounded-xl border border-slate-700 object-contain bg-slate-950">
        </div>
    </div>

    <script>
        (function () {
            const lightbox = document.getElementById('image-lightbox');
            const lightboxImg = document.getElementById('image-lightbox-img');
            const closeButton = lightbox ? lightbox.querySelector('[data-lightbox-close]') : null;

            if (!lightbox || !lightboxImg) {
                return;
            }

            const openLightbox = (src, alt) => {
                lightboxImg.src = src;
                lightboxImg.alt = alt || 'Gambar';
                lightbox.classList.remove('hidden');
                lightbox.classList.add('flex');
                document.body.classList.add('overflow-hidden');
            };

            const closeLightbox = () => {
                lightbox.classList.add('hidden');
                lightbox.classList.remove('flex');
                lightboxImg.src = '';
                lightboxImg.alt = '';
                document.body.classList.remove('overflow-hidden');
            };

            document.addEventListener('click', (event) => {
                const trigger = event.target.closest('[data-lightbox-src]');

                if (!trigger) {
                    return;
                }

                event.preventDefault();
                openLightbox(trigger.getAttribute('data-lightbox-src'), trigger.getAttribute('data-lightbox-alt'));
            });

            lightbox.addEventListener('click', (event) => {
                if (event.target === lightbox) {
                    closeLightbox();
                }
            });

            if (closeButton) {
                closeButton.addEventListener('click', closeLightbox);
            }

            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape' && !lightbox.classList.contains('hidden')) {
                    closeLightbox();
                }
            });
        })();
    </script>
</body>
</html>
