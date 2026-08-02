<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Ekskul - Login Guru</title>
<style>
        @layer base {
            html, body { margin: 0; padding: 0; }
            body { overscroll-behavior: none; }
            main > :first-child { margin-top: 0 !important; }
            main > :last-child { margin-bottom: 0 !important; }
        }
        ::-webkit-scrollbar { display: none; }
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
                        "primary": "#10b981",
                        "surface-container": "#e5eeff",
                        "error-container": "#ffdad6",
                        "surface-container-highest": "#d3e4fe",
                        "tertiary-fixed-dim": "#d0bcff",
                        "tertiary": "#6d3bd7",
                        "primary-fixed": "#a7f3d0",
                        "inverse-on-surface": "#eaf1ff",
                        "on-tertiary": "#ffffff",
                        "surface-container-low": "#eff4ff",
                        "primary-container": "#059669",
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
                        "on-primary-container": "#ffffff",
                        "inverse-primary": "#6ee7b7",
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
                        "gutter": "24px",
                        "unit-sm": "8px",
                        "unit-md": "16px",
                        "unit-lg": "24px",
                        "unit-xl": "48px",
                        "unit-xs": "4px"
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
                        "body-md": ["16px", { "lineHeight": "24px", "fontWeight": "400" }],
                        "title-md": ["20px", { "lineHeight": "28px", "fontWeight": "600" }],
                        "headline-lg": ["32px", { "lineHeight": "40px", "letterSpacing": "-0.01em", "fontWeight": "600" }],
                        "label-sm": ["12px", { "lineHeight": "16px", "letterSpacing": "0.03em", "fontWeight": "600" }],
                        "headline-lg-mobile": ["24px", { "lineHeight": "32px", "fontWeight": "600" }],
                        "display-lg": ["48px", { "lineHeight": "56px", "letterSpacing": "-0.02em", "fontWeight": "700" }],
                        "label-md": ["14px", { "lineHeight": "20px", "letterSpacing": "0.01em", "fontWeight": "500" }],
                        "body-lg": ["18px", { "lineHeight": "28px", "fontWeight": "400" }]
                    }
                },
            },
        }
    </script>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&amp;family=Plus+Jakarta+Sans:wght@100..900&amp;display=swap" rel="stylesheet"/>
</head>
<body class="bg-background font-body-md text-on-background min-h-screen flex items-center justify-center">
<main class="w-full max-w-[1280px] mx-auto px-margin-mobile lg:px-margin-desktop">
<div class="flex flex-col w-full">
<div class="flex flex-col lg:flex-row min-h-[90vh] w-full overflow-hidden rounded-xl shadow-xl bg-surface-container-lowest">
<!-- Left Side: Login Form -->
<div class="w-full lg:w-1/2 flex flex-col justify-center px-unit-lg lg:px-unit-xl py-unit-xl relative z-10">
<div class="max-w-[440px] mx-auto w-full">
<!-- Brand Identity -->
<div class="flex items-center gap-unit-md mb-unit-xl">
<span class="material-symbols-outlined text-primary text-[48px]">school</span>
<div class="flex flex-col">
<span class="font-label-md text-label-md text-primary-container tracking-widest uppercase">Portal Guru</span>
<span class="font-title-md text-title-md text-on-surface">EkskulHub</span>
</div>
</div>
<!-- Header -->
<div class="mb-unit-xl">
<h1 class="font-display-lg text-display-lg text-on-surface mb-unit-sm">Masuk ke Ruang Guru</h1>
<p class="font-body-lg text-body-lg text-on-surface-variant">Kelola peserta, jadwal, absensi, dan penilaian kegiatan ekskul dalam satu platform terintegrasi.</p>
</div>
<!-- Form -->
<form class="space-y-unit-lg" method="POST" action="{{ route('login') }}" id="loginForm">
@csrf

@if ($errors->any())
    <div class="p-4 mb-4 bg-red-100 text-red-700 rounded-xl">
        <ul class="list-disc list-inside text-sm">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('login') }}" class="space-y-4">
    @csrf
    <div class="space-y-unit-xs">
        <label class="font-label-md text-label-md text-on-surface-variant" for="email">Email atau NIP</label>
        <input class="w-full h-12 px-unit-md rounded-lg bg-surface-container-low border-none focus:ring-2 focus:ring-primary-container transition-all font-body-md text-on-surface outline-none" id="email" name="email" value="{{ old('email') }}" required autofocus placeholder="contoh: admin@sekolah.sch.id..." type="text"/>
    </div>
<div class="space-y-unit-xs relative">
<label class="font-label-md text-label-md text-on-surface-variant" for="password">Kata Sandi</label>
<div class="relative">
<input class="w-full h-12 px-unit-md rounded-lg bg-surface-container-low border-none focus:ring-2 focus:ring-primary-container transition-all font-body-md text-on-surface outline-none" id="password" name="password" required autocomplete="current-password" placeholder="••••••••" type="password"/>
<button class="absolute right-unit-md top-1/2 -translate-y-1/2 text-on-surface-variant hover:text-primary-container transition-colors" onclick="togglePassword()" type="button">
<span class="material-symbols-outlined" id="passIcon">visibility</span>
</button>
</div>
</div>
<div class="flex items-center justify-between">
<label class="flex items-center gap-unit-sm cursor-pointer group">
<div class="relative flex items-center justify-center">
<input class="peer appearance-none w-5 h-5 border-2 border-outline rounded bg-transparent checked:bg-primary-container checked:border-primary-container transition-all cursor-pointer" type="checkbox" id="remember" name="remember"/>
<span class="material-symbols-outlined absolute text-[16px] text-on-primary opacity-0 peer-checked:opacity-100 transition-opacity pointer-events-none">check</span>
</div>
<span class="font-label-md text-label-md text-on-surface-variant group-hover:text-on-surface transition-colors">Ingat saya</span>
</label>
@if (Route::has('password.request'))
<a class="font-label-md text-label-md text-primary-container hover:underline underline-offset-4" href="{{ route('password.request') }}">Lupa Kata Sandi?</a>
@endif
</div>
<button class="w-full h-14 bg-primary-container text-on-primary font-title-md text-body-md rounded-xl shadow-lg hover:shadow-xl hover:bg-primary transition-all flex items-center justify-center gap-unit-sm group" type="submit">
                                Masuk sebagai Guru
                                <span class="material-symbols-outlined group-hover:translate-x-1 transition-transform">arrow_forward</span>
</button>
</form>
<div class="mt-unit-xl pt-unit-lg text-center flex flex-col gap-unit-md items-center">
<p class="font-body-md text-on-surface-variant">
    Belum memiliki akun guru? 
    <a class="text-primary-container font-semibold hover:underline" href="{{ route('register.guru') }}">Daftar Sekarang</a>
</p>
<div class="w-full h-[1px] bg-outline-variant"></div>
<a class="inline-flex items-center gap-unit-xs font-label-md text-label-md text-on-surface-variant hover:text-primary-container transition-colors" href="{{ url('/') }}">
<span class="material-symbols-outlined text-[18px]">west</span>
                                Kembali ke Beranda
                            </a>
</div>
</div>
</div>
<!-- Right Side: Visual Showcase -->
<div class="hidden lg:flex w-1/2 bg-surface-container relative overflow-hidden items-center justify-center p-unit-xl">
<!-- Decorative background elements -->
<div class="absolute top-[-10%] right-[-10%] w-[500px] h-[500px] bg-primary-container/20 rounded-full blur-[100px] animate-pulse"></div>
<div class="absolute bottom-[-5%] left-[-5%] w-[400px] h-[400px] bg-primary/10 rounded-full blur-[80px]"></div>
<div class="relative z-10 w-full max-w-lg flex flex-col items-center">
<!-- Main Illustration -->
<div class="relative w-full aspect-square mb-unit-xl">
<div class="absolute inset-0 rounded-[40px] rotate-3 bg-white/10 backdrop-blur-sm"></div>
<div class="absolute inset-0 rounded-[40px] -rotate-3 bg-white/20 backdrop-blur-sm"></div>
<div class="relative w-full h-full rounded-[40px] overflow-hidden shadow-2xl">
<img alt="Professional teacher illustration" class="w-full h-full object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuA9NZEIMvnTPbe0OqIiNjbrLQY2LyxznHAbGAkVu6xuwR-X6arJR1lguPB6VXd9Xjk6qcOFUNuefPPNM3WkjQi71xhYjqt5G75k8NhIFTtpySKaQ8JM1eY1aWaey2xxhd9Ia1Yq7xYKSJoJe2JFAOW3HmUZCZLV7lozCyQhPVanmXwVVn6vUSzcXuXj0WPLnwY-FqddqUEBWYoN9twW5iRu084g1gTDlBCKkUKrDxUpjgFYxHxVa84T"/>
</div>
<!-- Floating Card 1: Students -->
<div class="absolute -left-12 top-1/4 animate-bounce" style="animation-duration: 4s;">
<div class="bg-surface-container-lowest p-unit-md rounded-xl shadow-xl flex items-center gap-unit-md">
<div class="w-10 h-10 rounded-full bg-primary-container/10 flex items-center justify-center text-primary-container">
<span class="material-symbols-outlined">group</span>
</div>
<div>
<p class="font-label-sm text-[10px] text-on-surface-variant uppercase tracking-tighter">Total Peserta</p>
<p class="font-title-md text-on-surface">124 Siswa</p>
</div>
</div>
</div>
<!-- Floating Card 2: Schedule -->
<div class="absolute -right-8 bottom-1/4 animate-bounce" style="animation-duration: 5s;">
<div class="bg-surface-container-lowest p-unit-md rounded-xl shadow-xl flex items-center gap-unit-md">
<div class="w-10 h-10 rounded-full bg-secondary/10 flex items-center justify-center text-secondary">
<span class="material-symbols-outlined">calendar_today</span>
</div>
<div>
<p class="font-label-sm text-[10px] text-on-surface-variant uppercase tracking-tighter">Jadwal Hari Ini</p>
<p class="font-title-md text-on-surface">Basket &amp; Tari</p>
</div>
</div>
</div>
<!-- Floating Card 3: Attendance -->
<div class="absolute left-1/4 -bottom-6">
<div class="bg-surface-container-lowest px-unit-lg py-unit-sm rounded-full shadow-xl flex items-center gap-unit-sm">
<div class="w-3 h-3 rounded-full bg-green-500 animate-pulse"></div>
<p class="font-label-md text-on-surface font-semibold">Absensi Selesai: 98%</p>
</div>
</div>
</div>
<div class="text-center text-on-surface max-w-sm">
<h2 class="font-headline-lg-mobile text-headline-lg-mobile mb-unit-sm">Kelola kegiatan ekskul dengan lebih teratur.</h2>
<p class="font-body-md opacity-70">Pantau perkembangan minat dan bakat siswa secara real-time dari satu dashboard.</p>
</div>
</div>
</div>
</div>
</div>
<script>
            function togglePassword() {
                const input = document.getElementById('password');
                const icon = document.getElementById('passIcon');
                if (input.type === 'password') {
                    input.type = 'text';
                    icon.innerText = 'visibility_off';
                } else {
                    input.type = 'password';
                    icon.innerText = 'visibility';
                }
            }

            // Basic interaction for inputs
            document.querySelectorAll('input').forEach(input => {
                input.addEventListener('focus', () => {
                    input.parentElement.classList.add('scale-[1.01]');
                });
                input.addEventListener('blur', () => {
                    input.parentElement.classList.remove('scale-[1.01]');
                });
            });
        </script>
</main>
</body></html>
