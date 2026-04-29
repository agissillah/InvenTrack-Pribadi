<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Barang | InvenTrack</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen">
    {{-- Halaman CREATE: menampilkan form input barang baru. --}}
    <main class="max-w-3xl mx-auto px-6 py-10">
        <header class="mb-8">
            <p class="text-cyan-400 text-sm font-semibold">Create</p>
            <h1 class="text-3xl font-bold">Tambah Barang</h1>
        </header>

        <section class="rounded-xl border border-slate-800 bg-slate-900/60 p-6">
            {{-- Submit menuju route store (POST /barang). --}}
            <form action="{{ route('barang.store') }}" method="POST" enctype="multipart/form-data">
                {{-- Reuse partial agar field form konsisten dengan halaman edit. --}}
                @include('barang._form')
            </form>
        </section>
    </main>
</body>
</html>
