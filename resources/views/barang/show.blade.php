<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Barang | InvenTrack</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen">
    {{-- Halaman READ detail untuk 1 data barang. --}}
    <main class="max-w-3xl mx-auto px-6 py-10">
        <header class="flex items-center justify-between gap-4 mb-8">
            <div>
                <p class="text-cyan-400 text-sm font-semibold">Read</p>
                <h1 class="text-3xl font-bold">Detail Barang</h1>
            </div>
            <a href="{{ route('barang.index') }}" class="px-4 py-2 rounded-md border border-slate-700 text-slate-200 hover:bg-slate-900 transition-colors">Kembali</a>
        </header>

        {{-- Card detail menampilkan semua atribut penting dari barang. --}}
        <section class="rounded-xl border border-slate-800 bg-slate-900/60 p-6 space-y-4">
            <div>
                <p class="text-sm text-slate-400">Nama</p>
                <p class="text-lg font-semibold">{{ $barang->nama }}</p>
            </div>
            <div>
                <p class="text-sm text-slate-400">SKU</p>
                <p class="text-lg">{{ $barang->sku }}</p>
            </div>
            <div>
                <p class="text-sm text-slate-400">Gambar</p>
                @if ($barang->gambar)
                    <button
                        type="button"
                        data-lightbox-src="{{ asset('storage/' . $barang->gambar) }}"
                        data-lightbox-alt="Gambar {{ $barang->nama }}"
                        class="inline-flex"
                    >
                        <img src="{{ asset('storage/' . $barang->gambar) }}" alt="Gambar {{ $barang->nama }}" class="mt-2 h-48 w-48 rounded-lg object-cover border border-slate-700 hover:ring-2 hover:ring-cyan-400/60">
                    </button>
                @else
                    <p class="text-lg">-</p>
                @endif
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-slate-400">Stok</p>
                    <p class="text-lg">{{ number_format($barang->stok) }}</p>
                </div>
                <div>
                    <p class="text-sm text-slate-400">Harga</p>
                    <p class="text-lg">Rp {{ number_format($barang->harga, 2, ',', '.') }}</p>
                </div>
            </div>
            <div>
                <p class="text-sm text-slate-400">Deskripsi</p>
                {{-- Jika deskripsi kosong/null, tampilkan tanda '-' agar UI tetap informatif. --}}
                <p class="text-lg whitespace-pre-line">{{ $barang->deskripsi ?: '-' }}</p>
            </div>

            {{-- Aksi cepat dari halaman detail ke edit/hapus. --}}
            <div class="pt-3 flex flex-wrap gap-3">
                <a href="{{ route('barang.edit', $barang) }}" class="px-4 py-2 rounded-md border border-amber-700 text-amber-300 hover:bg-amber-500/10">Edit</a>
                <form action="{{ route('barang.destroy', $barang) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data ini?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 rounded-md border border-red-700 text-red-300 hover:bg-red-500/10">Hapus</button>
                </form>
            </div>
        </section>
    </main>

    <div id="image-lightbox" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-950/80 px-6">
        <div class="relative w-full max-w-3xl">
            <button type="button" data-lightbox-close class="absolute -top-10 right-0 text-sm font-semibold text-slate-200 hover:text-white">Tutup</button>
            <img id="image-lightbox-img" src="" alt="" class="w-full max-h-[80vh] rounded-xl border border-slate-700 object-contain bg-slate-950">
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

            document.querySelectorAll('[data-lightbox-src]').forEach((trigger) => {
                trigger.addEventListener('click', () => {
                    openLightbox(trigger.getAttribute('data-lightbox-src'), trigger.getAttribute('data-lightbox-alt'));
                });
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
