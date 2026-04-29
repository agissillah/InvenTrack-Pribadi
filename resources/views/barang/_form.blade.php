{{-- Token CSRF wajib untuk keamanan form POST/PUT/DELETE di Laravel. --}}
@csrf

<div class="space-y-5">
    {{-- Field nama barang. old() menjaga input user jika validasi gagal. --}}
    <div>
        <label for="nama" class="block text-sm font-semibold text-slate-200 mb-2">Nama Barang</label>
        <input
            id="nama"
            name="nama"
            type="text"
            value="{{ old('nama', $barang->nama ?? '') }}"
            class="w-full rounded-md bg-slate-900 border border-slate-700 text-slate-100 focus:border-cyan-400 focus:ring-cyan-400"
            required
        >
        @error('nama')
            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
        @enderror
    </div>

    {{-- Field SKU sebagai kode unik barang. --}}
    <div>
        <label for="sku" class="block text-sm font-semibold text-slate-200 mb-2">SKU</label>
        <input
            id="sku"
            name="sku"
            type="text"
            value="{{ old('sku', $barang->sku ?? '') }}"
            class="w-full rounded-md bg-slate-900 border border-slate-700 text-slate-100 focus:border-cyan-400 focus:ring-cyan-400"
            required
        >
        @error('sku')
            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
        @enderror
    </div>

    {{-- Grid 2 kolom untuk stok dan harga agar input numerik lebih rapi. --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        <div>
            <label for="stok" class="block text-sm font-semibold text-slate-200 mb-2">Stok</label>
            <input
                id="stok"
                name="stok"
                type="number"
                min="0"
                value="{{ old('stok', $barang->stok ?? 0) }}"
                class="w-full rounded-md bg-slate-900 border border-slate-700 text-slate-100 focus:border-cyan-400 focus:ring-cyan-400"
                required
            >
            @error('stok')
                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="harga" class="block text-sm font-semibold text-slate-200 mb-2">Harga</label>
            <input
                id="harga"
                name="harga"
                type="number"
                step="0.01"
                min="0"
                value="{{ old('harga', $barang->harga ?? 0) }}"
                class="w-full rounded-md bg-slate-900 border border-slate-700 text-slate-100 focus:border-cyan-400 focus:ring-cyan-400"
                required
            >
            @error('harga')
                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
            @enderror
        </div>
    </div>

    {{-- Deskripsi opsional untuk catatan tambahan barang. --}}
    <div>
        <label for="deskripsi" class="block text-sm font-semibold text-slate-200 mb-2">Deskripsi</label>
        <textarea
            id="deskripsi"
            name="deskripsi"
            rows="4"
            class="w-full rounded-md bg-slate-900 border border-slate-700 text-slate-100 focus:border-cyan-400 focus:ring-cyan-400"
        >{{ old('deskripsi', $barang->deskripsi ?? '') }}</textarea>
        @error('deskripsi')
            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="gambar" class="block text-sm font-semibold text-slate-200 mb-2">Gambar Barang</label>
        <input
            id="gambar"
            name="gambar"
            type="file"
            accept="image/*"
            class="block w-full rounded-md bg-slate-900 border border-slate-700 text-slate-100 file:mr-4 file:rounded-md file:border-0 file:bg-slate-800 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-slate-200 hover:file:bg-slate-700"
        >
        <p class="mt-1 text-xs text-slate-400">Format: JPG/PNG/WEBP, maksimal 2 MB.</p>
        @error('gambar')
            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
        @enderror

        @if (!empty($barang) && $barang->gambar)
            <div class="mt-3 flex items-center gap-4">
                <button
                    type="button"
                    data-lightbox-src="{{ asset('storage/' . $barang->gambar) }}"
                    data-lightbox-alt="Gambar {{ $barang->nama }}"
                    class="inline-flex"
                >
                    <img src="{{ asset('storage/' . $barang->gambar) }}" alt="Gambar {{ $barang->nama }}" class="h-20 w-20 rounded-md object-cover border border-slate-700 hover:ring-2 hover:ring-cyan-400/60">
                </button>
                <p class="text-xs text-slate-400">Klik gambar untuk lihat ukuran penuh. Upload baru untuk mengganti.</p>
            </div>
        @endif
    </div>

    {{-- Tombol submit untuk simpan, dan tombol batal kembali ke halaman list. --}}
    <div class="flex flex-wrap gap-3 pt-2">
        <button type="submit" class="px-5 py-2 rounded-md bg-cyan-400 text-slate-900 font-semibold hover:bg-cyan-300 transition-colors">
            Simpan
        </button>
        <a href="{{ route('barang.index') }}" class="px-5 py-2 rounded-md border border-slate-600 text-slate-200 hover:bg-slate-800 transition-colors">
            Batal
        </a>
    </div>
</div>

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
