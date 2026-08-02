<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Ekskul - Manajemen Kegiatan Sekolah</title>
<style>
        @layer base {
            html, body {
                margin: 0;
                padding: 0;
            }
            body {
                overscroll-behavior: none;
            }
            main > :first-child {
                margin-top: 0 !important;
            }
            main > :last-child {
                margin-bottom: 0 !important;
            }
        }
        ::-webkit-scrollbar {
            display: none;
        }
    </style>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "tertiary-container": "#b090ff",
                        "secondary-fixed": "#dbe4ea",
                        "secondary-fixed-dim": "#bfc8ce",
                        "outline-variant": "#c1c7d3",
                        "primary": "#0ea5e9",
                        "surface-container": "#e5eeff",
                        "error-container": "#ffdad6",
                        "surface-container-highest": "#d3e4fe",
                        "tertiary-fixed-dim": "#d0bcff",
                        "tertiary": "#6d3bd7",
                        "primary-fixed": "#d4e3ff",
                        "inverse-on-surface": "#eaf1ff",
                        "on-tertiary": "#ffffff",
                        "surface-container-low": "#eff4ff",
                        "primary-container": "#60a5fa",
                        "on-error": "#ffffff",
                        "on-secondary-fixed-variant": "#3f484d",
                        "on-primary-fixed": "#001c39",
                        "surface-container-lowest": "#ffffff",
                        "on-secondary": "#ffffff",
                        "inverse-surface": "#213145",
                        "on-tertiary-fixed": "#23005c",
                        "secondary": "#576065",
                        "primary-fixed-dim": "#a4c9ff",
                        "surface-tint": "#0060ac",
                        "on-tertiary-fixed-variant": "#5516be",
                        "surface-bright": "#f8f9ff",
                        "on-secondary-container": "#5d666b",
                        "on-primary": "#ffffff",
                        "on-background": "#0b1c30",
                        "error": "#ba1a1a",
                        "tertiary-fixed": "#e9ddff",
                        "on-primary-container": "#003a6b",
                        "inverse-primary": "#a4c9ff",
                        "secondary-container": "#dbe4ea",
                        "surface-variant": "#d3e4fe",
                        "surface-dim": "#cbdbf5",
                        "on-tertiary-container": "#4600a7",
                        "on-error-container": "#93000a",
                        "outline": "#717783",
                        "background": "#f8f9ff",
                        "on-secondary-fixed": "#141d21",
                        "on-surface": "#0b1c30",
                        "surface-container-high": "#dce9ff",
                        "surface": "#f8f9ff",
                        "on-surface-variant": "#414751",
                        "on-primary-fixed-variant": "#004883"
                    },
                    "borderRadius": {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "full": "9999px"
                    },
                    "spacing": {
                        "xs": "4px",
                        "lg": "24px",
                        "2xl": "48px",
                        "md": "16px",
                        "sm": "8px",
                        "3xl": "64px",
                        "xl": "32px",
                        "margin-desktop": "40px",
                        "margin-mobile": "16px",
                        "base": "4px",
                        "gutter": "24px"
                    },
                    "fontFamily": {
                        "body-md": ["Inter"],
                        "title-md": ["Plus Jakarta Sans"],
                        "headline-lg": ["Plus Jakarta Sans"],
                        "label-sm": ["Inter"],
                        "headline-lg-mobile": ["Plus Jakarta Sans"],
                        "display-lg": ["Plus Jakarta Sans"],
                        "label-md": ["Inter"],
                        "body-lg": ["Inter"]
                    },
                    "fontSize": {
                        "body-md": ["16px", {"lineHeight": "24px", "fontWeight": "400"}],
                        "title-md": ["20px", {"lineHeight": "28px", "fontWeight": "600"}],
                        "headline-lg": ["32px", {"lineHeight": "40px", "letterSpacing": "-0.01em", "fontWeight": "600"}],
                        "label-sm": ["12px", {"lineHeight": "16px", "letterSpacing": "0.03em", "fontWeight": "600"}],
                        "headline-lg-mobile": ["24px", {"lineHeight": "32px", "fontWeight": "600"}],
                        "display-lg": ["48px", {"lineHeight": "56px", "letterSpacing": "-0.02em", "fontWeight": "700"}],
                        "label-md": ["14px", {"lineHeight": "20px", "letterSpacing": "0.01em", "fontWeight": "500"}],
                        "body-lg": ["18px", {"lineHeight": "28px", "fontWeight": "400"}]
                    }
                }
            }
        }
    </script>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&amp;family=Plus+Jakarta+Sans:wght@100..900&amp;display=swap" rel="stylesheet"/>
</head>
<body class="bg-background font-body-md text-on-background">
<header class="fixed top-0 w-full z-50 bg-surface-container-lowest/80 backdrop-blur-xl transition-all duration-300 border-b border-outline-variant/10">
<div class="h-16 max-w-[1280px] mx-auto px-margin-mobile lg:px-margin-desktop flex items-center justify-between">
<div class="flex items-center gap-md">
<span class="material-symbols-outlined text-primary text-[32px]">school</span>
</div>
<nav class="hidden lg:flex items-center gap-lg">
<a aria-current="page" class="transition-colors text-primary font-bold" data-path="beranda" href="#">Beranda</a>
<a class="font-body-md text-body-md text-on-surface-variant hover:text-on-surface transition-colors" data-path="tentang" href="#">Tentang</a>
<a class="font-body-md text-body-md text-on-surface-variant hover:text-on-surface transition-colors" data-path="kontak" href="#">Kontak</a>
</nav>
<div class="flex items-center gap-md">
    @auth
        <a href="{{ url('/dashboard') }}" class="text-primary font-bold hover:underline pr-4">Ke Dashboard</a>
    @else
        <a class="hidden sm:block font-label-md text-label-md text-on-surface-variant hover:text-on-surface" data-path="login-guru" href="{{ route('login.guru') }}">Login Guru</a>
        <a href="{{ route('login') }}" class="bg-primary text-on-primary px-lg py-2 rounded-full font-label-md hover:scale-[1.02] transition-transform shadow-md shadow-primary/20 flex items-center justify-center">Masuk Siswa</a>
    @endauth
<div class="w-8 h-8 rounded-full bg-primary flex items-center justify-center">
<span class="material-symbols-outlined text-on-primary text-[18px]">person</span>
</div>
</div>
</div>
</header>
<main class="w-full pt-16">
<div class="flex flex-col w-full">
<!-- Hero Section -->
<section class="relative overflow-hidden bg-surface pb-xl lg:pb-32 lg:-mt-16 pt-32 lg:pt-48">
<!-- Decorative Ambient Background -->
<div class="absolute top-0 right-0 -translate-y-1/4 translate-x-1/4 w-[600px] h-[600px] bg-primary-container/10 rounded-full blur-3xl pointer-events-none"></div>
<div class="absolute bottom-0 left-0 translate-y-1/4 -translate-x-1/4 w-[400px] h-[400px] bg-secondary-container/10 rounded-full blur-3xl pointer-events-none"></div>
<div class="max-w-[1280px] mx-auto px-margin-mobile lg:px-margin-desktop grid grid-cols-1 lg:grid-cols-2 gap-gutter items-center">
<div class="relative z-10 space-y-lg">
<div class="inline-flex items-center gap-2 bg-primary-fixed text-on-primary-fixed px-4 py-1.5 rounded-full">
<span class="relative flex h-2 w-2">
<span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary opacity-75"></span>
<span class="relative inline-flex rounded-full h-2 w-2 bg-primary"></span>
</span>
<span class="font-label-md text-label-md">Pendaftaran 2024 Dibuka</span>
</div>
<h1 class="font-display-lg text-display-lg text-on-surface max-w-xl">
                            Temukan dan Ikuti Kegiatan <span class="text-primary-container italic">Ekskul</span> dengan Lebih Mudah
                        </h1>
<p class="font-body-lg text-body-lg text-on-surface-variant max-w-lg">
                            Daftar sebagai peserta, pilih kegiatan yang diminati, dan kelola proses pendaftaran melalui satu platform yang terintegrasi.
                        </p>
<div class="flex flex-wrap items-center gap-md pt-sm">
<a href="{{ route('register') }}" class="bg-primary text-on-primary px-8 py-4 rounded-full font-label-md text-[16px] shadow-lg shadow-primary/30 hover:scale-105 transition-all duration-300 inline-block text-center">
                                Daftar Siswa
                            </a>
<a href="{{ route('register.guru') }}" class="bg-emerald-500 text-white px-8 py-4 rounded-full font-label-md text-[16px] shadow-lg shadow-emerald-500/30 hover:scale-105 transition-all duration-300 inline-block text-center">
                                Daftar Guru
                            </a>
</div>
<div class="pt-md">
<a class="font-body-md text-on-surface-variant hover:text-primary flex items-center gap-1 group" href="{{ route('login.guru') }}">
                                Anda seorang guru? <span class="font-bold underline group-hover:no-underline">Masuk sebagai Guru</span>
<span class="material-symbols-outlined text-[18px]">arrow_forward</span>
</a>
</div>
</div>
<div class="relative lg:h-[600px] flex items-center justify-center">
<div class="relative w-full max-w-[420px]">
<!-- Main Card -->
<div class="bg-surface-container-lowest rounded-[2.5rem] p-6 shadow-2xl border border-surface-container-high relative z-20">
<div class="flex items-center justify-between mb-8">
<div class="flex items-center gap-3">
<div class="w-12 h-12 rounded-full bg-primary-fixed flex items-center justify-center">
<span class="material-symbols-outlined text-primary">person</span>
</div>
<div>
<p class="font-label-md text-label-md text-on-surface-variant">Halo, Siswa</p>
<h3 class="font-title-md text-title-md">Andi Pratama</h3>
</div>
</div>
<span class="material-symbols-outlined text-on-surface-variant">notifications</span>
</div>
<div class="space-y-4">
<div class="p-4 bg-surface-container-low rounded-2xl flex items-center justify-between group cursor-pointer hover:bg-primary/5 transition-colors border border-outline-variant/10">
<div class="flex items-center gap-4">
<div class="w-10 h-10 rounded-xl bg-primary text-on-primary flex items-center justify-center">
<span class="material-symbols-outlined">sports_basketball</span>
</div>
<span class="font-title-md text-[16px]">Basket Ball</span>
</div>
<span class="text-primary-container material-symbols-outlined">check_circle</span>
</div>
<div class="p-4 bg-surface-container-low rounded-2xl flex items-center justify-between border border-outline-variant/10">
<div class="flex items-center gap-4">
<div class="w-10 h-10 rounded-xl bg-secondary text-on-primary flex items-center justify-center">
<span class="material-symbols-outlined">music_note</span>
</div>
<span class="font-title-md text-[16px]">Klub Musik</span>
</div>
<span class="text-on-surface-variant/40 material-symbols-outlined">radio_button_unchecked</span>
</div>
</div>
<div class="mt-8 pt-6 border-t border-outline-variant/30 grid grid-cols-4 gap-2">
<div class="flex flex-col items-center gap-1">
<div class="w-10 h-10 rounded-full bg-primary-fixed/50 flex items-center justify-center text-primary">
<span class="material-symbols-outlined">home</span>
</div>
</div>
<div class="flex flex-col items-center gap-1">
<div class="w-10 h-10 rounded-full flex items-center justify-center text-on-surface-variant">
<span class="material-symbols-outlined">search</span>
</div>
</div>
<div class="flex flex-col items-center gap-1">
<div class="w-10 h-10 rounded-full flex items-center justify-center text-on-surface-variant">
<span class="material-symbols-outlined">calendar_today</span>
</div>
</div>
<div class="flex flex-col items-center gap-1">
<div class="w-10 h-10 rounded-full flex items-center justify-center text-on-surface-variant">
<span class="material-symbols-outlined">settings</span>
</div>
</div>
</div>
</div>
<!-- Floating Decorative Cards -->
<div class="absolute -top-10 -right-8 z-30 bg-white p-4 rounded-2xl shadow-xl animate-bounce [animation-duration:5s] border border-outline-variant/20">
<div class="flex items-center gap-3">
<div class="p-2 bg-green-100 rounded-lg">
<span class="material-symbols-outlined text-green-600">verified</span>
</div>
<div>
<p class="text-[10px] font-label-md uppercase tracking-wider text-on-surface-variant">Status</p>
<p class="text-sm font-bold text-on-surface">Pendaftaran Disetujui</p>
</div>
</div>
</div>
<div class="absolute bottom-10 -left-12 z-30 bg-white p-4 rounded-2xl shadow-xl animate-pulse border border-outline-variant/20">
<div class="flex items-center gap-3">
<div class="p-2 bg-primary-fixed rounded-lg text-primary">
<span class="material-symbols-outlined">schedule</span>
</div>
<div>
<p class="text-[10px] font-label-md uppercase tracking-wider text-on-surface-variant">Jadwal Esok</p>
<p class="text-sm font-bold text-on-surface">Pramuka • 15:00</p>
</div>
</div>
</div>
</div>
</div>
</div>
</section>
<!-- Fitur Utama Section -->
<section class="py-xl bg-surface-container-lowest">
<div class="max-w-[1280px] mx-auto px-margin-mobile lg:px-margin-desktop">
<div class="text-center mb-16">
<span class="text-primary-container font-label-md text-label-md uppercase tracking-widest">Keunggulan Platform</span>
<h2 class="font-headline-lg text-headline-lg mt-2">Semua dalam Satu Genggaman</h2>
</div>
<div class="grid grid-cols-1 md:grid-cols-3 gap-gutter">
<!-- Card 1 -->
<div class="group bg-surface-container-low p-lg rounded-2xl transition-all duration-500 hover:-translate-y-4 hover:shadow-xl border border-outline-variant/10">
<div class="w-16 h-16 bg-primary-fixed rounded-2xl flex items-center justify-center text-primary mb-6 group-hover:scale-110 transition-transform">
<span class="material-symbols-outlined text-[32px]">app_registration</span>
</div>
<h3 class="font-title-md text-title-md mb-4 text-on-surface">Pendaftaran Online</h3>
<p class="font-body-md text-body-md text-on-surface-variant">
                                Siswa dapat membuat akun dan mendaftar kegiatan ekskul secara langsung tanpa perlu formulir kertas yang rumit.
                            </p>
</div>
<!-- Card 2 -->
<div class="group bg-surface-container-low p-lg rounded-2xl transition-all duration-500 hover:-translate-y-4 hover:shadow-xl border border-outline-variant/10">
<div class="w-16 h-16 bg-surface-container-high rounded-2xl flex items-center justify-center text-primary mb-6 group-hover:scale-110 transition-transform">
<span class="material-symbols-outlined text-[32px]">list_alt</span>
</div>
<h3 class="font-title-md text-title-md mb-4 text-on-surface">Pilihan Ekskul</h3>
<p class="font-body-md text-body-md text-on-surface-variant">
                                Jelajahi berbagai kegiatan menarik sesuai minatmu dan lihat informasi jadwal latihan secara mendalam.
                            </p>
</div>
<!-- Card 3 -->
<div class="group bg-surface-container-low p-lg rounded-2xl transition-all duration-500 hover:-translate-y-4 hover:shadow-xl border border-outline-variant/10">
<div class="w-16 h-16 bg-tertiary-fixed rounded-2xl flex items-center justify-center text-tertiary mb-6 group-hover:scale-110 transition-transform">
<span class="material-symbols-outlined text-[32px]">rule</span>
</div>
<h3 class="font-title-md text-title-md mb-4 text-on-surface">Status Pendaftaran</h3>
<p class="font-body-md text-body-md text-on-surface-variant">
                                Pantau progres pendaftaranmu secara real-time: apakah masih menunggu, sudah disetujui, atau ditolak.
                            </p>
</div>
</div>
</div>
</section>
<!-- Cara Mendaftar Section -->
<section class="py-xl bg-surface relative overflow-hidden">
<div class="max-w-[1280px] mx-auto px-margin-mobile lg:px-margin-desktop relative z-10">
<h2 class="font-headline-lg text-headline-lg text-center mb-16">Langkah Mudah Bergabung</h2>
<div class="relative">
<!-- Horizontal Line Desktop -->
<div class="hidden lg:block absolute top-12 left-0 w-full h-0.5 bg-primary-container/20"></div>
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-gutter">
<!-- Step 1 -->
<div class="relative flex flex-col items-center text-center">
<div class="w-24 h-24 rounded-full bg-primary-container text-on-primary flex items-center justify-center font-display-lg text-headline-lg mb-6 relative z-10 border-8 border-surface shadow-lg shadow-primary-container/20">1</div>
<h4 class="font-title-md text-title-md mb-2">Buat Akun</h4>
<p class="font-body-md text-on-surface-variant">Gunakan email sekolah atau nomor induk siswa untuk mendaftar.</p>
</div>
<!-- Step 2 -->
<div class="relative flex flex-col items-center text-center">
<div class="w-24 h-24 rounded-full bg-primary-container text-on-primary flex items-center justify-center font-display-lg text-headline-lg mb-6 relative z-10 border-8 border-surface shadow-lg shadow-primary-container/20">2</div>
<h4 class="font-title-md text-title-md mb-2">Lengkapi Data</h4>
<p class="font-body-md text-on-surface-variant">Isi informasi profil dan data pendukung lainnya dengan benar.</p>
</div>
<!-- Step 3 -->
<div class="relative flex flex-col items-center text-center">
<div class="w-24 h-24 rounded-full bg-primary-container text-on-primary flex items-center justify-center font-display-lg text-headline-lg mb-6 relative z-10 border-8 border-surface shadow-lg shadow-primary-container/20">3</div>
<h4 class="font-title-md text-title-md mb-2">Pilih Ekskul</h4>
<p class="font-body-md text-on-surface-variant">Cari dan pilih kegiatan ekstrakurikuler yang paling kamu minati.</p>
</div>
<!-- Step 4 -->
<div class="relative flex flex-col items-center text-center">
<div class="w-24 h-24 rounded-full bg-primary-container text-on-primary flex items-center justify-center font-display-lg text-headline-lg mb-6 relative z-10 border-8 border-surface shadow-lg shadow-primary-container/20">4</div>
<h4 class="font-title-md text-title-md mb-2">Tunggu Approval</h4>
<p class="font-body-md text-on-surface-variant">Pembina akan meninjau datamu dan memberikan konfirmasi segera.</p>
</div>
</div>
</div>
</div>
</section>
<!-- Daftar Ekskul Section -->
<section class="py-xl bg-surface-container-lowest">
<div class="max-w-[1280px] mx-auto px-margin-mobile lg:px-margin-desktop">
<div class="flex flex-col md:flex-row md:items-end justify-between gap-md mb-12"><div><span class="text-primary-container font-label-md text-label-md uppercase tracking-widest">Sistem Kehadiran</span><h2 class="font-headline-lg text-headline-lg mt-2">Alur Presensi Kegiatan</h2><p class="font-body-md text-on-surface-variant mt-2">Proses pencatatan kehadiran yang cepat, akurat, dan transparan bagi seluruh siswa.</p></div></div>
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-gutter"><div class="group bg-surface-container-low p-8 rounded-3xl border border-outline-variant/10 hover:shadow-xl transition-all duration-500"><div class="w-16 h-16 bg-primary-fixed rounded-2xl flex items-center justify-center text-primary mb-6"><span class="material-symbols-outlined text-[32px]">qr_code_scanner</span></div><h3 class="font-title-md text-title-md mb-4">1. Scan QR Kehadiran</h3><p class="font-body-md text-on-surface-variant">Siswa melakukan scan QR code yang disediakan pembina di lokasi kegiatan secara langsung.</p></div><div class="group bg-surface-container-low p-8 rounded-3xl border border-outline-variant/10 hover:shadow-xl transition-all duration-500"><div class="w-16 h-16 bg-surface-container-high rounded-2xl flex items-center justify-center text-primary mb-6"><span class="material-symbols-outlined text-[32px]">location_on</span></div><h3 class="font-title-md text-title-md mb-4">2. Verifikasi Lokasi</h3><p class="font-body-md text-on-surface-variant">Sistem memastikan siswa berada di radius lokasi kegiatan yang ditentukan melalui koordinat GPS.</p></div><div class="group bg-surface-container-low p-8 rounded-3xl border border-outline-variant/10 hover:shadow-xl transition-all duration-500"><div class="w-16 h-16 bg-tertiary-fixed rounded-2xl flex items-center justify-center text-tertiary mb-6"><span class="material-symbols-outlined text-[32px]">task_alt</span></div><h3 class="font-title-md text-title-md mb-4">3. Presensi Berhasil</h3><p class="font-body-md text-on-surface-variant">Status kehadiran tercatat otomatis dalam sistem dan dapat dipantau secara real-time oleh orang tua.</p></div></div>
</div>
</section>
<!-- CTA Section -->
<section class="py-xl px-margin-mobile">
<div class="max-w-[1280px] mx-auto bg-primary-container rounded-[2.5rem] p-8 lg:p-16 relative overflow-hidden text-center text-on-primary">
<!-- Background Decoration -->
<div class="absolute top-0 left-0 w-full h-full overflow-hidden pointer-events-none opacity-20">
<div class="absolute -top-24 -left-24 w-96 h-96 bg-white rounded-full blur-3xl"></div>
<div class="absolute -bottom-24 -right-24 w-96 h-96 bg-surface-container-high rounded-full blur-3xl"></div>
</div>
<div class="relative z-10 max-w-2xl mx-auto space-y-8">
<h2 class="font-display-lg text-display-lg">Siap Bergabung dengan Kegiatan Ekskul?</h2>
<p class="font-body-lg text-body-lg text-white/90">
                            Tunggu apa lagi? Buat akun sekarang, pilih kegiatan yang sesuai dengan minatmu, dan kembangkan potensimu bersama teman-teman baru.
                        </p>
<div class="pt-4 flex flex-col sm:flex-row items-center justify-center gap-4">
<a href="{{ route('register') }}" class="bg-surface-container-lowest text-primary-container px-10 py-4 rounded-full font-label-md text-[16px] shadow-xl hover:scale-105 transition-transform inline-block text-center">
                                Daftar Siswa
                            </a>
<a href="{{ route('register.guru') }}" class="bg-primary border border-white/20 text-on-primary px-10 py-4 rounded-full font-label-md text-[16px] hover:bg-surface-container-lowest hover:text-primary-container transition-colors inline-block text-center">
                                Daftar Guru
                            </a>
</div>
</div>
</div>
</section>
</div>
<script>
            const observerOptions = {
                threshold: 0.1
            };

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('opacity-100', 'translate-y-0');
                        entry.target.classList.remove('opacity-0', 'translate-y-10');
                    }
                });
            }, observerOptions);

            document.querySelectorAll('.group, .relative.flex.flex-col.items-center').forEach(el => {
                el.classList.add('transition-all', 'duration-700', 'opacity-0', 'translate-y-10');
                observer.observe(el);
            });
        </script>
</main>
<footer class="w-full bg-surface-container-low py-xl">
<div class="max-w-[1280px] mx-auto px-margin-mobile lg:px-margin-desktop">
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-gutter mb-xl">
<div class="space-y-md">
<div class="flex items-center gap-2 mb-4">
<span class="material-symbols-outlined text-primary text-[32px]">school</span>
</div>
<p class="text-on-surface-variant font-body-md">Kelola kegiatan sekolah lebih mudah dan terorganisir bersama Ekskul.</p>
</div>
<div class="flex flex-col gap-sm">
<span class="font-label-md text-label-md text-on-surface mb-xs">Akses Utama</span>
<a class="text-on-surface-variant hover:text-primary transition-colors text-body-md" data-path="login-siswa" href="{{ route('login') }}">Login Siswa</a>
<a class="text-on-surface-variant hover:text-primary transition-colors text-body-md" data-path="login-guru" href="{{ route('login.guru') }}">Login Guru</a>
</div>
<div class="flex flex-col gap-sm">
<span class="font-label-md text-label-md text-on-surface mb-xs">Informasi</span>
<a class="text-on-surface-variant hover:text-primary transition-colors text-body-md" data-path="kebijakan-privasi" href="#">Kebijakan Privasi</a>
<a class="text-on-surface-variant hover:text-primary transition-colors text-body-md" data-path="syarat-ketentuan" href="#">Syarat &amp; Ketentuan</a>
</div>
<div class="flex flex-col gap-sm">
<span class="font-label-md text-label-md text-on-surface mb-xs">Ikuti Kami</span>
<div class="flex gap-md">
<span class="material-symbols-outlined text-on-surface-variant hover:text-primary cursor-pointer">public</span>
<span class="material-symbols-outlined text-on-surface-variant hover:text-primary cursor-pointer">mail</span>
</div>
</div>
</div>
<div class="border-t border-outline-variant/30 pt-md flex flex-col md:flex-row justify-between items-center gap-md text-on-surface-variant text-body-md">
<span>© 2024 Ekskul. Seluruh hak cipta dilindungi.</span>
<div class="flex gap-md items-center">
<span class="material-symbols-outlined text-[20px] text-primary-container">verified</span>
<span>Partner Sekolah Resmi</span>
</div>
</div>
</div>
</footer>
</body>
</html>
