<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Kasir | InvenTrack</title>
    <!-- Tailwind CDN dipakai untuk styling cepat tanpa build step tambahan. -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-slate-950 text-slate-100 flex items-center justify-center p-6">
    <!-- Card utama form login kasir. -->
    <div class="w-full max-w-md bg-slate-900 border border-emerald-500/30 rounded-xl shadow-2xl p-8">
        <div class="mb-6 text-center">
            <h1 class="text-3xl font-bold text-emerald-400">Login Kasir</h1>
            <p class="text-slate-400 mt-2">Masuk sebagai kasir untuk transaksi harian.</p>
        </div>

        <!-- Form masih placeholder (action="#"). Nanti diarahkan ke endpoint autentikasi kasir. -->
        <form action="#" method="POST" class="space-y-4">
            <!-- Token CSRF wajib pada form POST Laravel untuk keamanan request. -->
            @csrf
            <div>
                <label for="username" class="block text-sm text-slate-300 mb-1">Username Kasir</label>
                <input id="username" name="username" type="text" required class="w-full rounded-md border border-slate-700 bg-slate-800 px-4 py-3 text-slate-100 focus:outline-none focus:ring-2 focus:ring-emerald-400" placeholder="kasir01">
            </div>

            <div>
                <label for="password" class="block text-sm text-slate-300 mb-1">Password</label>
                <input id="password" name="password" type="password" required class="w-full rounded-md border border-slate-700 bg-slate-800 px-4 py-3 text-slate-100 focus:outline-none focus:ring-2 focus:ring-emerald-400" placeholder="••••••••">
            </div>

            <button type="submit" class="w-full rounded-md bg-emerald-400 text-slate-900 font-semibold py-3 hover:bg-emerald-300 transition">
                Masuk Kasir
            </button>
        </form>

        <div class="mt-6 text-center">
            <!-- Navigasi kembali ke landing page. -->
            <a href="{{ url('/') }}" class="text-sm text-emerald-400 hover:text-emerald-300">Kembali ke Beranda</a>
        </div>
    </div>
</body>
</html>
