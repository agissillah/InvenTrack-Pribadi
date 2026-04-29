<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Barang | InvenTrack</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen">
    {{-- Halaman UPDATE: form untuk mengubah data barang yang sudah ada. --}}
    <main class="max-w-3xl mx-auto px-6 py-10">
        <header class="mb-8">
            <p class="text-cyan-400 text-sm font-semibold">Update</p>
            <h1 class="text-3xl font-bold">Edit Barang</h1>
        </header>

        <section class="rounded-xl border border-slate-800 bg-slate-900/60 p-6">
            {{-- Action ke route update. HTML form hanya mendukung GET/POST, jadi dipalsukan ke PUT via @method. --}}
            <form action="{{ route('barang.update', $barang) }}" method="POST" enctype="multipart/form-data">
                @method('PUT')
                {{-- Reuse partial yang sama dengan create agar struktur field seragam. --}}
                @include('barang._form')
            </form>
        </section>
    </main>
</body>
</html>
