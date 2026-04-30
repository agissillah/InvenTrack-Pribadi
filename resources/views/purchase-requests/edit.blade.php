<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Purchase Request | InvenTrack</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen">
    <main class="max-w-5xl mx-auto px-6 py-10">
        <header class="mb-8">
            <p class="text-cyan-400 text-sm font-semibold">Update</p>
            <h1 class="text-3xl font-bold">Edit Purchase Request</h1>
        </header>

        <section class="rounded-xl border border-slate-800 bg-slate-900/60 p-6">
            <form action="{{ route('purchase-requests.update', $purchaseRequest) }}" method="POST">
                @method('PUT')
                @include('purchase-requests._form')
            </form>
        </section>
    </main>
</body>
</html>
