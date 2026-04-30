<!DOCTYPE html>
<!--
    Landing page utama InvenTrack.
    Fungsi file ini:
    1) Menjelaskan value aplikasi (digitalisasi pengadaan).
    2) Menyediakan navigasi section dengan smooth scroll.
    3) Menyediakan entry point role-based login (Admin dan Kasir).
-->

<html class="dark" lang="id"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>InvenTrack | Digitalisasi Pengadaan Manufaktur Elektronik</title>
<!-- Tailwind CDN + plugin forms/container-queries untuk styling tanpa build lokal. -->
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<!-- Font untuk heading dan body agar konsisten dengan branding. -->
<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&amp;family=Inter:wght@300;400;500;600&amp;display=swap" rel="stylesheet"/>
<!-- Ikon Material Symbols untuk ikon visual di section fitur dan flow. -->
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<script id="tailwind-config">
    // Kustomisasi tema Tailwind untuk palet warna dark-futuristic InvenTrack.
        tailwind.config = {
          darkMode: "class",
          theme: {
            extend: {
              colors: {
                "surface-tint": "#69daff",
                "primary-dim": "#00c0ea",
                "on-secondary-fixed-variant": "#006544",
                "error-dim": "#d7383b",
                "secondary-fixed-dim": "#58e7ab",
                "tertiary-fixed-dim": "#bacae4",
                "on-surface": "#dee5ff",
                "on-primary-fixed-variant": "#004a5c",
                "on-secondary-fixed": "#00452d",
                "error": "#ff716c",
                "on-background": "#dee5ff",
                "background": "#060e20",
                "secondary-container": "#006c49",
                "on-tertiary-container": "#3c4c61",
                "tertiary": "#d7e6ff",
                "secondary": "#69f6b8",
                "primary-fixed-dim": "#00c0ea",
                "tertiary-container": "#c8d8f3",
                "outline": "#6d758c",
                "inverse-on-surface": "#4d556b",
                "on-primary-container": "#004050",
                "on-primary": "#004a5d",
                "secondary-fixed": "#69f6b8",
                "on-tertiary-fixed": "#29394e",
                "surface-dim": "#060e20",
                "on-secondary-container": "#e1ffec",
                "surface-bright": "#1f2b49",
                "on-tertiary": "#45546a",
                "primary-fixed": "#00cffc",
                "outline-variant": "#40485d",
                "secondary-dim": "#58e7ab",
                "surface": "#060e20",
                "surface-container-low": "#091328",
                "tertiary-fixed": "#c8d8f3",
                "primary-container": "#00cffc",
                "on-error-container": "#ffa8a3",
                "inverse-surface": "#faf8ff",
                "tertiary-dim": "#bacae4",
                "surface-variant": "#192540",
                "surface-container-highest": "#192540",
                "on-secondary": "#005a3c",
                "on-primary-fixed": "#002a35",
                "on-tertiary-fixed-variant": "#46556b",
                "surface-container-lowest": "#000000",
                "surface-container": "#0f1930",
                "surface-container-high": "#141f38",
                "on-error": "#490006",
                "primary": "#69daff",
                "error-container": "#9f0519",
                "on-surface-variant": "#a3aac4",
                "inverse-primary": "#006880"
              },
              fontFamily: {
                "headline": ["Space Grotesk"],
                "body": ["Inter"],
                "label": ["Inter"]
              },
              borderRadius: {"DEFAULT": "0.125rem", "lg": "0.25rem", "xl": "0.5rem", "full": "0.75rem"},
            },
          },
        }
    </script>
<style>
        /* Smooth scroll untuk perpindahan antar section via anchor navbar. */
        html {
            scroll-behavior: smooth;
        }
        /* Gaya default link menu navbar. */
        .nav-link {
            color: #94a3b8;
            padding-bottom: 0.25rem;
            border-bottom: 2px solid transparent;
            transition: color 0.2s ease, border-color 0.2s ease;
        }
        /* Efek hover link navbar. */
        .nav-link:hover {
            color: #ffffff;
        }
        /* Status aktif navbar: teks biru + garis bawah. */
        .nav-link-active {
            color: #00D1FF !important;
            border-bottom-color: #00D1FF;
        }
        /* Setelan ikon Material Symbols agar proporsional di semua section. */
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        /* Pola grid halus untuk memperkuat nuansa industrial dashboard. */
        .grid-underlay {
            background-image: linear-gradient(to right, rgba(64, 72, 93, 0.08) 1px, transparent 1px),
                              linear-gradient(to bottom, rgba(64, 72, 93, 0.08) 1px, transparent 1px);
            background-size: 24px 24px;
        }
        /* Kartu efek glass untuk list fitur utama. */
        .glass-card {
            background: rgba(25, 37, 64, 0.6);
            backdrop-filter: blur(12px);
            border-left: 2px solid transparent;
            transition: all 0.3s ease;
        }
        /* Highlight kartu saat hover untuk memberi feedback interaktif. */
        .glass-card:hover {
            border-left-color: #69daff;
            background: rgba(25, 37, 64, 0.8);
        }
        /* Cahaya lembut pada gambar hero agar terasa fokus sebagai hero visual. */
        .hero-glow {
            box-shadow: 0 0 50px rgba(105, 218, 255, 0.15);
        }
        /* Efek pulse untuk step yang ingin ditonjolkan dalam alur proses. */
        .step-pulse {
            box-shadow: 0 0 0 0 rgba(0, 207, 252, 0.4);
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(0, 207, 252, 0.7); }
            70% { transform: scale(1); box-shadow: 0 0 0 10px rgba(0, 207, 252, 0); }
            100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(0, 207, 252, 0); }
        }
    </style>
</head>
<body class="bg-background text-on-background font-body grid-underlay min-h-screen">
<!--
    Top Navbar (fixed):
    - Navigasi ke section konten halaman.
    - Tombol login role-based ke route Laravel.
-->
<nav class="fixed top-0 z-50 w-full bg-[#060e20]/60 backdrop-blur-xl shadow-[0_0_15px_rgba(0,209,255,0.1)]">
<div class="flex justify-between items-center w-full px-8 py-4 max-w-7xl mx-auto">
<div class="text-2xl font-bold tracking-tighter text-[#00D1FF] font-headline">InvenTrack</div>
<!-- Menu section internal (anchor link) untuk one-page navigation. -->
<div class="hidden md:flex items-center space-x-8 font-headline tracking-tight">
<a class="nav-link" data-nav-link="true" href="#tentang-perusahaan">Tentang Perusahaan</a>
<a class="nav-link" data-nav-link="true" href="#solusi-digital">Solusi Digital</a>
<a class="nav-link nav-link-active" data-nav-link="true" href="#fitur-utama">Fitur Utama</a>
</div>
<!-- Aksi utama dipindah ke menu titik tiga agar navbar tidak tabrakan. -->
<div class="hidden md:flex items-center relative">
    <button
        id="action-menu-button"
        type="button"
        aria-controls="action-menu"
        aria-expanded="false"
        aria-label="Buka menu aksi"
        class="inline-flex items-center justify-center w-11 h-11 rounded-md border border-[#00D1FF]/50 text-[#00D1FF] hover:bg-[#00D1FF]/10 transition-colors"
    >
        <span class="material-symbols-outlined">more_horiz</span>
    </button>
    <div
        id="action-menu"
        class="hidden absolute right-0 top-full mt-3 w-64 rounded-xl border border-[#192540] bg-[#0b142b]/95 backdrop-blur-xl p-2 shadow-[0_20px_50px_rgba(6,14,32,0.6)]"
    >
        <a class="flex items-center gap-3 rounded-lg px-4 py-2 text-sm font-semibold text-[#00D1FF] hover:bg-[#00D1FF]/10" href="{{ route('login.admin') }}">
            Login Admin
        </a>
        <a class="flex items-center gap-3 rounded-lg px-4 py-2 text-sm font-semibold text-[#7dd3fc] hover:bg-[#00D1FF]/10" href="{{ route('login.kasir') }}">
            Login Kasir
        </a>
        <a class="flex items-center gap-3 rounded-lg px-4 py-2 text-sm font-semibold text-[#69f6b8] hover:bg-[#69f6b8]/10" href="{{ route('purchase-requests.index') }}">
            CRUD Purchase Request
        </a>
        <a class="flex items-center gap-3 rounded-lg px-4 py-2 text-sm font-semibold text-[#f5b66a] hover:bg-[#f5b66a]/10" href="{{ route('purchase-orders.index') }}">
            CRUD Purchase Order
        </a>
    </div>
</div>
<button aria-controls="mobile-navbar-menu" aria-expanded="false" aria-label="Buka menu navigasi" class="md:hidden inline-flex items-center justify-center w-10 h-10 rounded-md border border-[#00D1FF]/50 text-[#00D1FF] hover:bg-[#00D1FF]/10 transition-colors" id="mobile-menu-button" type="button">
<span class="material-symbols-outlined">more_horiz</span>
</button>
</div>
<div class="hidden md:hidden px-8 pb-4" id="mobile-navbar-menu">
<div class="flex flex-col gap-3 border-t border-outline-variant/40 pt-4">
<a class="nav-link w-fit" data-nav-link="true" href="#tentang-perusahaan">Tentang Perusahaan</a>
<a class="nav-link w-fit" data-nav-link="true" href="#solusi-digital">Solusi Digital</a>
<a class="nav-link nav-link-active w-fit" data-nav-link="true" href="#fitur-utama">Fitur Utama</a>
<a class="bg-[#00D1FF] text-[#004a5d] text-center font-bold px-4 py-2 rounded-md hover:bg-[#00c0ea] transition-all active:scale-95 duration-150" href="{{ route('login.admin') }}">
                Login Admin
            </a>
<a class="border border-[#00D1FF] text-[#00D1FF] text-center font-bold px-4 py-2 rounded-md hover:bg-[#00D1FF]/10 transition-all active:scale-95 duration-150" href="{{ route('login.kasir') }}">
                Login Kasir
            </a>
<a class="border border-[#69f6b8] text-[#69f6b8] text-center font-bold px-4 py-2 rounded-md hover:bg-[#69f6b8]/10 transition-all active:scale-95 duration-150" href="{{ route('purchase-requests.index') }}">
                CRUD Purchase Request
            </a>
<a class="border border-[#f5b66a] text-[#f5b66a] text-center font-bold px-4 py-2 rounded-md hover:bg-[#f5b66a]/10 transition-all active:scale-95 duration-150" href="{{ route('purchase-orders.index') }}">
                CRUD Purchase Order
            </a>
</div>
</div>
<div class="bg-gradient-to-r from-transparent via-[#192540] to-transparent h-[1px]"></div>
</nav>
<!--
    Hero Section:
    - Menjelaskan proposisi nilai utama produk.
    - Menyediakan CTA cepat ke login role-based.
-->
<section class="pt-32 pb-20 px-6 max-w-7xl mx-auto overflow-hidden">
<div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
<div class="lg:col-span-6 z-10">
<h1 class="font-headline text-4xl lg:text-6xl font-bold tracking-tight text-on-surface leading-tight mb-6">
                    Digitalisasi Pengadaan Barang untuk <span class="text-primary">Industri Manufaktur Elektronik</span>
</h1>
<p class="text-on-surface-variant text-lg lg:text-xl mb-10 max-w-xl">
                    Optimalkan proses pengadaan bahan baku di PT Maju Jaya Manufacturing dengan sistem yang terintegrasi dan real-time.
                </p>
<!-- CTA hero memakai route yang sama dengan navbar agar user flow konsisten. -->
<div class="flex flex-wrap gap-4">
<a class="px-8 py-4 bg-primary text-on-primary font-bold rounded-md shadow-[0_0_15px_rgba(105,218,255,0.4)] hover:bg-primary-dim transition-all" href="{{ route('login.admin') }}">
                        Login Admin
                    </a>
<a class="px-8 py-4 border border-primary text-primary font-bold rounded-md hover:bg-primary/10 transition-all" href="{{ route('login.kasir') }}">
                        Login Kasir
                    </a>
</div>
</div>
<div class="lg:col-span-6 relative">
<div class="relative z-10 hero-glow rounded-xl bg-surface-container-high p-2">
<img alt="Electronics Manufacturing" class="rounded-lg opacity-80" data-alt="Modern high-tech PCB assembly line with robotic arms and glowing blue neon lights in a clean industrial facility" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCfffltNE7hXLCf_mtgIbmzg43nhLjX4T1a8kiftNwO5ni75Ijhg7a3Qsq8B_du6UEUpLTgMvT-PML13Yo8FKg2iovhGlUqzSaS5Ibc4jQO70QIEEw3E2EaFkWNJT0wdfPtIFdSTNgljKnDlsiI1K5aJRBeiiYRsgbSFSZqJez4aZcpxzuX9_Km6r054TJI2vu7A_P9I7yftFJadXksMmYvn_5go2mwzRKB8fXQ9_bQ-TO36NszqKBV07hBYSCH0DvVy-PeDc1XcWLE"/>
<!-- Overlay metrik contoh untuk memberi konteks manfaat monitoring real-time. -->
<div class="absolute -bottom-6 -left-6 bg-surface-container-highest/90 backdrop-blur-md p-6 rounded-xl border border-outline-variant/30 shadow-2xl max-w-xs">
<div class="flex items-center gap-3 mb-4">
<span class="material-symbols-outlined text-secondary">analytics</span>
<span class="font-headline font-bold text-sm uppercase tracking-widest text-on-surface-variant">Live Inventory</span>
</div>
<div class="h-1 w-full bg-outline-variant/20 rounded-full mb-4">
<div class="h-full bg-secondary w-3/4 rounded-full"></div>
</div>
<p class="text-2xl font-headline font-bold text-on-surface">94.2%</p>
<p class="text-xs text-on-surface-variant uppercase tracking-tighter">Production Readiness</p>
</div>
</div>
<!-- Ornamen latar untuk memperkuat fokus visual hero image. -->
<div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[140%] h-[140%] bg-primary/5 rounded-full blur-[100px] -z-10"></div>
</div>
</div>
</section>
<!--
    Section Tentang Perusahaan:
    Menampilkan pain points operasional perusahaan sebagai latar kebutuhan solusi.
    id dipakai sebagai target anchor navbar.
-->
<section class="py-24 bg-surface-container-low scroll-mt-28" id="tentang-perusahaan">
<div class="max-w-7xl mx-auto px-6">
<div class="flex flex-col md:flex-row gap-16 items-center">
<div class="md:w-1/2">
<div class="inline-block py-1 px-3 bg-primary/10 border border-primary/20 rounded-sm mb-6">
<span class="text-xs font-bold text-primary uppercase tracking-widest font-headline">Analisis Kasus: PT Maju Jaya Manufacturing</span>
</div>
<h2 class="font-headline text-3xl font-bold text-on-surface mb-6">Tantangan Manufaktur PCB &amp; Kabel</h2>
<p class="text-on-surface-variant mb-8 leading-relaxed">
                        PT Maju Jaya menghadapi kompleksitas tinggi dalam mengelola ribuan komponen elektronik. Proses manual yang masih menggunakan aplikasi chat dan email menciptakan bottleneck yang menghambat jalur produksi.
                    </p>
<div class="space-y-4">
<div class="flex gap-4 items-start p-4 bg-background/40 rounded-lg">
<span class="material-symbols-outlined text-error mt-1">cancel</span>
<div>
<h4 class="font-bold text-on-surface">Manual PR Flow</h4>
<p class="text-sm text-on-surface-variant">Request material via chat sering terlewat dan tidak terdokumentasi.</p>
</div>
</div>
<div class="flex gap-4 items-start p-4 bg-background/40 rounded-lg">
<span class="material-symbols-outlined text-error mt-1">warning</span>
<div>
<h4 class="font-bold text-on-surface">Inaccurate Stock</h4>
<p class="text-sm text-on-surface-variant">Data stok gudang sering berbeda dengan fisik saat produksi dimulai.</p>
</div>
</div>
<div class="flex gap-4 items-start p-4 bg-background/40 rounded-lg">
<span class="material-symbols-outlined text-error mt-1">inventory_2</span>
<div>
<h4 class="font-bold text-on-surface">Receipt Mismatch</h4>
<p class="text-sm text-on-surface-variant">Barang yang datang tidak sesuai dengan spesifikasi PO yang diminta.</p>
</div>
</div>
</div>
</div>
<div class="md:w-1/2 grid grid-cols-2 gap-4">
<div class="col-span-2 rounded-xl overflow-hidden h-64 relative">
<img alt="Industrial Problems" class="w-full h-full object-cover grayscale opacity-40" data-alt="Atmospheric close-up of a worker hand holding a circuit board in a dimly lit industrial laboratory with shadows" src="https://lh3.googleusercontent.com/aida-public/AB6AXuA-_wcv-PJIsdpWX-DHdb3p647NoJC55fHJOQMZ-Wkr58R7fYjKQt2cJYHw8n88slf_cbu8BQpmlNUxKJndqsbVH-FefS5uD9rReKWC3r60zigSuztx2XKMsJf700xvYRbkMny5VuAMJCwvIkKzuP-3YZNMEtKIaSl3jfXtmq_bJaZ2z5LvTK6bKLSp7qMlf5bmVvolx1ZT52dqWw1QWy6Qz1HIvSO79n6oRRTOWzqDINAb9cbE9wNJDZ4i8D4zAEKOMdZPH58r72Gy"/>
<div class="absolute inset-0 bg-gradient-to-t from-background to-transparent"></div>
</div>
<div class="rounded-xl bg-surface-container-highest p-6 flex flex-col justify-center border-t border-outline-variant/20">
<span class="text-3xl font-headline font-bold text-error mb-1">32%</span>
<span class="text-xs uppercase tracking-widest text-on-surface-variant">Delayed Orders</span>
</div>
<div class="rounded-xl bg-surface-container-highest p-6 flex flex-col justify-center border-t border-outline-variant/20">
<span class="text-3xl font-headline font-bold text-error mb-1">15%</span>
<span class="text-xs uppercase tracking-widest text-on-surface-variant">Waste Material</span>
</div>
</div>
</div>
</div>
</section>
<!--
    Section Solusi Digital:
    Menjelaskan alur end-to-end procurement dari PR hingga live inventory.
-->
<section class="py-24 relative overflow-hidden scroll-mt-28" id="solusi-digital">
<div class="max-w-7xl mx-auto px-6 relative z-10">
<div class="text-center mb-20">
<h2 class="font-headline text-3xl font-bold text-on-surface mb-4">Alur Pengadaan Terintegrasi</h2>
<p class="text-on-surface-variant max-w-2xl mx-auto">Transformasi digital ujung-ke-ujung dari permintaan hingga barang sampai di lini produksi.</p>
</div>
<div class="relative">
<!-- Alur horizontal untuk layar desktop agar mudah dibaca kiri-ke-kanan. -->
<div class="hidden lg:flex justify-between items-start relative">
<div class="absolute top-10 left-0 w-full h-[2px] bg-outline-variant/30 -z-10"></div>
<!-- Step 1 -->
<div class="flex flex-col items-center w-1/5">
<div class="w-20 h-20 bg-surface-container-highest rounded-full flex items-center justify-center mb-6 border border-outline-variant shadow-lg">
<span class="material-symbols-outlined text-primary scale-125">request_page</span>
</div>
<h4 class="font-headline font-bold text-sm uppercase mb-2">PR Management</h4>
<p class="text-xs text-center text-on-surface-variant px-4">Digital submission &amp; tracking</p>
</div>
<!-- Step 2 -->
<div class="flex flex-col items-center w-1/5">
<div class="w-20 h-20 bg-surface-container-highest rounded-full flex items-center justify-center mb-6 border border-outline-variant shadow-lg step-pulse">
<span class="material-symbols-outlined text-primary scale-125">rule</span>
</div>
<h4 class="font-headline font-bold text-sm uppercase mb-2">Approval Flow</h4>
<p class="text-xs text-center text-on-surface-variant px-4">Multi-level digital validation</p>
</div>
<!-- Step 3 -->
<div class="flex flex-col items-center w-1/5">
<div class="w-20 h-20 bg-surface-container-highest rounded-full flex items-center justify-center mb-6 border border-outline-variant shadow-lg">
<span class="material-symbols-outlined text-primary scale-125">shopping_cart</span>
</div>
<h4 class="font-headline font-bold text-sm uppercase mb-2">Auto PO</h4>
<p class="text-xs text-center text-on-surface-variant px-4">Vendor dispatch automation</p>
</div>
<!-- Step 4 -->
<div class="flex flex-col items-center w-1/5">
<div class="w-20 h-20 bg-surface-container-highest rounded-full flex items-center justify-center mb-6 border border-outline-variant shadow-lg">
<span class="material-symbols-outlined text-primary scale-125">package_2</span>
</div>
<h4 class="font-headline font-bold text-sm uppercase mb-2">Goods Receipt</h4>
<p class="text-xs text-center text-on-surface-variant px-4">Validation with QR Scanning</p>
</div>
<!-- Step 5 -->
<div class="flex flex-col items-center w-1/5">
<div class="w-20 h-20 bg-surface-container-highest rounded-full flex items-center justify-center mb-6 border border-secondary shadow-lg">
<span class="material-symbols-outlined text-secondary scale-125">warehouse</span>
</div>
<h4 class="font-headline font-bold text-sm uppercase mb-2">Live Inventory</h4>
<p class="text-xs text-center text-on-surface-variant px-4">Stock updated in real-time</p>
</div>
</div>
<!-- Versi mobile flow dibuat vertikal agar tetap terbaca di layar sempit. -->
<div class="lg:hidden space-y-12 pl-8 border-l-2 border-outline-variant/30">
<div class="relative">
<div class="absolute -left-[41px] top-0 w-8 h-8 bg-surface-container-highest border border-primary rounded-full flex items-center justify-center">
<span class="material-symbols-outlined text-primary text-sm">request_page</span>
</div>
<h4 class="font-headline font-bold text-on-surface">PR Management</h4>
</div>
<div class="relative">
<div class="absolute -left-[41px] top-0 w-8 h-8 bg-surface-container-highest border border-primary rounded-full flex items-center justify-center">
<span class="material-symbols-outlined text-primary text-sm">rule</span>
</div>
<h4 class="font-headline font-bold text-on-surface">Approval System</h4>
</div>
<div class="relative">
<div class="absolute -left-[41px] top-0 w-8 h-8 bg-surface-container-highest border border-primary rounded-full flex items-center justify-center">
<span class="material-symbols-outlined text-primary text-sm">shopping_cart</span>
</div>
<h4 class="font-headline font-bold text-on-surface">Auto PO Generation</h4>
</div>
</div>
</div>
</div>
</section>
<!--
    Section Fitur Utama:
    Grid fitur inti sistem yang dijual ke user bisnis.
-->
<section class="py-24 bg-surface scroll-mt-28" id="fitur-utama">
<div class="max-w-7xl mx-auto px-6">
<div class="mb-16">
<h2 class="font-headline text-3xl font-bold text-on-surface tracking-tight">Fitur Utama <span class="text-primary">InvenTrack</span></h2>
<div class="h-1 w-24 bg-primary mt-4"></div>
</div>
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
<!-- PR Management -->
<div class="glass-card p-8 rounded-xl">
<span class="material-symbols-outlined text-primary mb-6 text-3xl">list_alt</span>
<h3 class="font-headline font-bold text-xl mb-3">PR Management</h3>
<p class="text-on-surface-variant text-sm leading-relaxed">Kelola semua permintaan barang dalam satu dashboard terpusat. Eliminasi penggunaan chat manual.</p>
</div>
<!-- Approval System -->
<div class="glass-card p-8 rounded-xl">
<span class="material-symbols-outlined text-primary mb-6 text-3xl">verified_user</span>
<h3 class="font-headline font-bold text-xl mb-3">Approval System</h3>
<p class="text-on-surface-variant text-sm leading-relaxed">Hierarki persetujuan otomatis berdasarkan nilai anggaran dan departemen pengguna.</p>
</div>
<!-- PO Tracking -->
<div class="glass-card p-8 rounded-xl">
<span class="material-symbols-outlined text-primary mb-6 text-3xl">local_shipping</span>
<h3 class="font-headline font-bold text-xl mb-3">PO Tracking</h3>
<p class="text-on-surface-variant text-sm leading-relaxed">Pantau status pesanan ke vendor secara real-time. Estimasi kedatangan barang yang akurat.</p>
</div>
<!-- Goods Receipt -->
<div class="glass-card p-8 rounded-xl">
<span class="material-symbols-outlined text-primary mb-6 text-3xl">fact_check</span>
<h3 class="font-headline font-bold text-xl mb-3">Receipt Validation</h3>
<p class="text-on-surface-variant text-sm leading-relaxed">Validasi barang masuk dengan sistem scan untuk memastikan kuantitas dan kualitas sesuai PO.</p>
</div>
<!-- Stock Monitoring -->
<div class="glass-card p-8 rounded-xl">
<span class="material-symbols-outlined text-primary mb-6 text-3xl">monitoring</span>
<h3 class="font-headline font-bold text-xl mb-3">Real-time Stock</h3>
<p class="text-on-surface-variant text-sm leading-relaxed">Visualisasi stok gudang yang diperbarui setiap ada pergerakan barang masuk atau keluar.</p>
</div>
<!-- Audit Log -->
<div class="glass-card p-8 rounded-xl">
<span class="material-symbols-outlined text-primary mb-6 text-3xl">history_edu</span>
<h3 class="font-headline font-bold text-xl mb-3">Activity Audit Log</h3>
<p class="text-on-surface-variant text-sm leading-relaxed">Rekam jejak setiap aksi dalam sistem untuk transparansi dan akuntabilitas total.</p>
</div>
</div>
</div>
</section>
<!-- Section manfaat bisnis sebagai penguat nilai komersial produk. -->
<section class="py-24 bg-surface-container-lowest">
<div class="max-w-7xl mx-auto px-6">
<div class="bg-surface-container-high rounded-3xl overflow-hidden shadow-2xl flex flex-col lg:flex-row border border-outline-variant/10">
<div class="lg:w-1/2 p-12 lg:p-20">
<h2 class="font-headline text-4xl font-bold mb-10 text-on-surface">Mengapa InvenTrack?</h2>
<div class="space-y-8">
<div class="flex items-start gap-5">
<div class="mt-1 w-6 h-6 rounded-full bg-secondary flex items-center justify-center flex-shrink-0">
<span class="material-symbols-outlined text-on-secondary text-sm" style="font-variation-settings: 'FILL' 1;">check</span>
</div>
<div>
<h4 class="font-bold text-on-surface mb-1">Efisiensi Operasional</h4>
<p class="text-on-surface-variant text-sm">Mengurangi waktu proses procurement hingga 60% dibanding sistem manual.</p>
</div>
</div>
<div class="flex items-start gap-5">
<div class="mt-1 w-6 h-6 rounded-full bg-secondary flex items-center justify-center flex-shrink-0">
<span class="material-symbols-outlined text-on-secondary text-sm" style="font-variation-settings: 'FILL' 1;">check</span>
</div>
<div>
<h4 class="font-bold text-on-surface mb-1">Zero Production Delay</h4>
<p class="text-on-surface-variant text-sm">Mencegah lini produksi berhenti akibat kehabisan stok komponen kritikal.</p>
</div>
</div>
<div class="flex items-start gap-5">
<div class="mt-1 w-6 h-6 rounded-full bg-secondary flex items-center justify-center flex-shrink-0">
<span class="material-symbols-outlined text-on-secondary text-sm" style="font-variation-settings: 'FILL' 1;">check</span>
</div>
<div>
<h4 class="font-bold text-on-surface mb-1">Akurasi Data 100%</h4>
<p class="text-on-surface-variant text-sm">Eliminasi kesalahan input manusia melalui integrasi data otomatis.</p>
</div>
</div>
<div class="flex items-start gap-5">
<div class="mt-1 w-6 h-6 rounded-full bg-secondary flex items-center justify-center flex-shrink-0">
<span class="material-symbols-outlined text-on-secondary text-sm" style="font-variation-settings: 'FILL' 1;">check</span>
</div>
<div>
<h4 class="font-bold text-on-surface mb-1">Transparansi Finansial</h4>
<p class="text-on-surface-variant text-sm">Visibilitas penuh atas pengeluaran perusahaan secara real-time.</p>
</div>
</div>
</div>
</div>
<div class="lg:w-1/2 relative min-h-[400px]">
<img alt="Success Industrial" class="w-full h-full object-cover" data-alt="Futuristic electronic components storage with neon lighting and high-tech inventory management scanning device being used" src="https://lh3.googleusercontent.com/aida-public/AB6AXuBPXRQz88zFpaqqpnjOEoCxzbAC--z4Ve3nX4WSczs5qgU7x9IuNWcgqhk27_MqqeDG34c3GCbDjS5NpwNo5IimY-pdTWp_B3it_4SaVTVxei7Fmlk5LLOq9P5HpznXpkwNEC9mfBlHKU1LdLbVCIw0Fex1tV5XBTQc5MEwQLUMCcAy9iuRk_WaXihUCO1Pyb5z_h_woBMqzCbKaQ0PbSx_sCEu35gqCp_YSfUy-6LXp3mKn7F9ta-oQCi1jE5OOHRBkr-SBhkA_cuH"/>
<div class="absolute inset-0 bg-primary/10 mix-blend-overlay"></div>
</div>
</div>
</div>
</section>
<!-- Footer informasi brand, legal links, dan copyright. -->
<footer class="bg-[#091328] w-full border-t border-[#192540]">
<div class="flex flex-col md:flex-row justify-between items-center w-full px-12 py-16 gap-8 max-w-7xl mx-auto">
<div class="flex flex-col items-center md:items-start gap-4">
<div class="text-xl font-black text-[#00D1FF] font-headline tracking-tighter">InvenTrack</div>
<p class="text-slate-500 font-body text-sm text-center md:text-left max-w-xs">
                    Sistem manajemen inventori cerdas untuk akselerasi industri manufaktur masa depan.
                </p>
</div>
<div class="flex gap-8 font-label text-sm tracking-wide uppercase">
<a class="text-slate-500 hover:text-[#00D1FF] transition-colors" href="#">Kebijakan Privasi</a>
<a class="text-slate-500 hover:text-[#00D1FF] transition-colors" href="#">Syarat &amp; Ketentuan</a>
<a class="text-slate-500 hover:text-[#00D1FF] transition-colors" href="#">Hubungi Kami</a>
</div>
<div class="text-slate-500 font-body text-xs text-center md:text-right">
                © 2024 InvenTrack Industrial Systems. Kinetic Precision Engineering.
            </div>
</div>
</footer>
<script>
        // Inisialisasi setelah DOM siap agar elemen menu sudah tersedia.
        document.addEventListener('DOMContentLoaded', function () {
            // Ambil semua link navbar yang mengontrol active state.
            const navLinks = document.querySelectorAll('[data-nav-link]');
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            const mobileMenu = document.getElementById('mobile-navbar-menu');
            const actionMenuButton = document.getElementById('action-menu-button');
            const actionMenu = document.getElementById('action-menu');

            // Fungsi untuk memindahkan indikator aktif (warna + underline) ke hash target.
            function setActiveLink(targetHash) {
                navLinks.forEach(function (link) {
                    link.classList.toggle('nav-link-active', link.getAttribute('href') === targetHash);
                });
            }

            // Utility untuk menutup menu mobile dan reset state tombol.
            function closeMobileMenu() {
                if (!mobileMenu || !mobileMenuButton) {
                    return;
                }

                mobileMenu.classList.add('hidden');
                mobileMenuButton.setAttribute('aria-expanded', 'false');
            }

            function closeActionMenu() {
                if (!actionMenu || !actionMenuButton) {
                    return;
                }

                actionMenu.classList.add('hidden');
                actionMenuButton.setAttribute('aria-expanded', 'false');
            }

            // Saat link diklik, update active state sesuai section tujuan.
            navLinks.forEach(function (link) {
                link.addEventListener('click', function () {
                    setActiveLink(link.getAttribute('href'));

                    if (window.innerWidth < 768) {
                        closeMobileMenu();
                    }
                });
            });

            // Toggle menu mobile saat tombol titik tiga ditekan.
            if (mobileMenuButton && mobileMenu) {
                mobileMenuButton.addEventListener('click', function () {
                    const isHidden = mobileMenu.classList.contains('hidden');

                    mobileMenu.classList.toggle('hidden');
                    mobileMenuButton.setAttribute('aria-expanded', String(isHidden));
                });

                // Klik di luar area menu akan menutup dropdown pada mobile.
                document.addEventListener('click', function (event) {
                    if (window.innerWidth >= 768 || mobileMenu.classList.contains('hidden')) {
                        return;
                    }

                    const clickedInsideMenu = mobileMenu.contains(event.target);
                    const clickedMenuButton = mobileMenuButton.contains(event.target);

                    if (!clickedInsideMenu && !clickedMenuButton) {
                        closeMobileMenu();
                    }
                });

                // Saat resize ke desktop, paksa menu mobile tertutup.
                window.addEventListener('resize', function () {
                    if (window.innerWidth >= 768) {
                        closeMobileMenu();
                    }
                });
            }

            if (actionMenuButton && actionMenu) {
                actionMenuButton.addEventListener('click', function () {
                    const isHidden = actionMenu.classList.contains('hidden');

                    actionMenu.classList.toggle('hidden');
                    actionMenuButton.setAttribute('aria-expanded', String(isHidden));
                });

                document.addEventListener('click', function (event) {
                    if (actionMenu.classList.contains('hidden')) {
                        return;
                    }

                    const clickedInsideMenu = actionMenu.contains(event.target);
                    const clickedMenuButton = actionMenuButton.contains(event.target);

                    if (!clickedInsideMenu && !clickedMenuButton) {
                        closeActionMenu();
                    }
                });

                window.addEventListener('resize', function () {
                    if (window.innerWidth < 768) {
                        closeActionMenu();
                    }
                });
            }

            // Jika page dibuka dengan hash (misal direct link), set active sejak awal.
            if (window.location.hash) {
                setActiveLink(window.location.hash);
            }
        });
    </script>
</body></html>