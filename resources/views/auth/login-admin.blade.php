<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin | InvenTrack</title>
    <!-- Tailwind CDN dipakai untuk styling cepat tanpa build step tambahan. -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-slate-950 text-slate-100 flex items-center justify-center p-6">
    <!-- Card utama form login admin. -->
    <div class="w-full max-w-md bg-slate-900 border border-cyan-500/30 rounded-xl shadow-2xl p-8">
        <div class="mb-6 text-center">
            <h1 class="text-3xl font-bold text-cyan-400">Login Admin</h1>
            <p class="text-slate-400 mt-2">Masuk sebagai administrator InvenTrack.</p>
        </div>

        <!-- Form masih placeholder (action="#"). Nanti diarahkan ke endpoint autentikasi admin. -->
        <form action="#" method="POST" class="space-y-4">
            <!-- Token CSRF wajib pada form POST Laravel untuk keamanan request. -->
            @csrf
            <div>
                <label for="email" class="block text-sm text-slate-300 mb-1">Email Admin</label>
                <input id="email" name="email" type="email" required class="w-full rounded-md border border-slate-700 bg-slate-800 px-4 py-3 text-slate-100 focus:outline-none focus:ring-2 focus:ring-cyan-400" placeholder="admin@company.com">
            </div>

            <div>
                <label for="password" class="block text-sm text-slate-300 mb-1">Password</label>
                <input id="password" name="password" type="password" required class="w-full rounded-md border border-slate-700 bg-slate-800 px-4 py-3 text-slate-100 focus:outline-none focus:ring-2 focus:ring-cyan-400" placeholder="••••••••">
            </div>

            <button type="submit" class="w-full rounded-md bg-cyan-400 text-slate-900 font-semibold py-3 hover:bg-cyan-300 transition">
                Masuk Admin
            </button>
        </form>

        <div class="mt-6 text-center">
            <!-- Navigasi kembali ke landing page. -->
            <a href="{{ url('/') }}" class="text-sm text-cyan-400 hover:text-cyan-300">Kembali ke Beranda</a>
        </div>
    </div>
</body>
</html>
