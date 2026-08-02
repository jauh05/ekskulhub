<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Ekskul - Login Siswa</title>
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
<div class="flex flex-col lg:flex-row min-h-[calc(100vh-64px)] w-full overflow-hidden rounded-xl shadow-xl bg-surface-container-lowest">
<!-- Left Panel: Immersive Visuals -->
<div class="relative hidden lg:flex lg:w-3/5 bg-surface-container-low overflow-hidden">
<!-- Decorative Background Elements -->
<div class="absolute top-[-10%] right-[-10%] w-96 h-96 bg-primary-container/20 rounded-full blur-3xl animate-pulse"></div>
<div class="absolute bottom-[-5%] left-[-5%] w-64 h-64 bg-secondary-container/30 rounded-full blur-2xl"></div>
<div class="relative z-10 flex flex-col justify-center px-xl w-full">
<!-- Floating Schedule Cards -->
<div class="absolute top-24 right-12 w-64 p-md bg-surface-container-lowest/90 backdrop-blur-md rounded-xl shadow-xl transform rotate-3 hover:rotate-0 transition-transform duration-500">
<div class="flex items-center gap-sm mb-sm">
<span class="material-symbols-outlined text-primary-container text-title-md">calendar_today</span>
<span class="font-label-md text-on-surface-variant">JADWAL HARI INI</span>
</div>
<div class="space-y-sm">
<div class="h-12 w-full bg-surface-container rounded-lg flex items-center px-3 gap-3">
<div class="w-2 h-8 bg-primary-container rounded-full"></div>
<div>
<p class="font-label-md text-on-surface">Basket</p>
<p class="text-[10px] text-on-surface-variant">15:00 • Lapangan A</p>
</div>
</div>
</div>
</div>
<div class="absolute bottom-32 left-8 w-56 p-md bg-surface-container-lowest/90 backdrop-blur-md rounded-xl shadow-xl transform -rotate-6 hover:rotate-0 transition-transform duration-500">
<div class="flex items-center gap-sm mb-sm">
<span class="material-symbols-outlined text-secondary text-title-md">stars</span>
<span class="font-label-md text-on-surface-variant">REKOMENDASI</span>
</div>
<div class="flex flex-col gap-2">
<div class="bg-secondary-container/20 p-2 rounded-lg">
<p class="font-label-md text-on-secondary-fixed-variant">Robotika AI</p>
<p class="text-[10px] text-on-secondary-fixed-variant">Sisa 3 Kuota</p>
</div>
</div>
</div>
<!-- Hero Illustration Area -->
<div class="relative w-full aspect-square max-w-lg mx-auto">
<div class="absolute inset-0 bg-gradient-to-tr from-primary-container/20 to-transparent rounded-full scale-110"></div>
<img class="relative z-20 w-full h-full object-contain mix-blend-multiply opacity-90" data-alt="A vibrant digital illustration of Indonesian high school students in modern uniforms engaged in various extracurricular activities: one playing guitar, one holding a basketball, and one using a laptop for coding. The background is a minimalist school campus with soft blue and white architectural elements. Soft, natural morning sunlight, clean lines, professional flat design style with depth through subtle shadows." src="https://lh3.googleusercontent.com/aida-public/AB6AXuBW-I7BinBBWGrN2Gf26EPKBDFx8zrdXRchrEiKEEacAp5dJH5E0seyE1S4ftyBgl8vEUOywyg4Ozw_P8Cl7Q5YUrNRBK7blwu2yf5nckVyQbJATSClAz8B-MEpKbXlojN2nuSqmNJugVenjSfsKnDhx4P14myRYh1FmtrkPPMTJZyHRP2om4ZkU_d-yBBeeVEdXYkxYcmIoIyl__VS5WIH7b4mo4gm7P4DJv116VcRuBgNBFZngyE2"/>
</div>
<!-- Hero Text -->
<div class="mt-xl text-center">
<h2 class="font-display-lg text-headline-lg text-on-surface">Temukan kegiatan yang sesuai dengan minatmu.</h2>
<p class="mt-md font-body-lg text-on-surface-variant max-w-md mx-auto">
                                Eksplorasi bakat, jalin koneksi, dan bangun portofolio non-akademikmu dalam satu platform terintegrasi.
                            </p>
</div>
</div>
</div>
<!-- Right Panel: Login Form -->
<div class="flex flex-col w-full lg:w-2/5 p-lg lg:p-xl justify-center bg-surface-container-lowest">
<div class="w-full max-w-md mx-auto">
<!-- Brand & Header -->
<div class="flex flex-col items-center lg:items-start mb-xl">
<span class="material-symbols-outlined text-primary text-[64px] mb-lg">school</span>
<span class="px-3 py-1 rounded-full bg-primary-fixed text-on-primary-fixed font-label-md text-[12px] mb-sm uppercase tracking-widest">Portal Siswa</span>
<h1 class="font-headline-lg text-headline-lg text-on-surface">Selamat Datang Kembali</h1>
<p class="font-body-md text-on-surface-variant mt-2 text-center lg:text-left">Silakan masuk untuk mengakses dasbor kegiatanmu.</p>
</div>

@if ($errors->any())
    <div class="mb-4 bg-error-container text-on-error-container p-4 rounded-lg">
        <div class="font-bold mb-2">Login Gagal:</div>
        <ul class="list-disc list-inside text-sm">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('login') }}" class="space-y-6">
    @csrf
    <div class="space-y-sm">
        <label class="block font-label-md text-on-surface-variant" for="email">Alamat Email</label>
        <div class="relative group">
            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant group-focus-within:text-primary-container transition-colors">mail</span>
            <input class="w-full pl-12 pr-4 py-3 bg-surface rounded-xl border border-outline-variant focus:border-primary-container focus:ring-1 focus:ring-primary-container focus:outline-none transition-all font-body-md text-on-surface" id="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="nama@sekolah.sch.id" type="email"/>
        </div>
    </div>
<div class="space-y-sm">
<label class="block font-label-md text-on-surface-variant" for="password">Kata Sandi</label>
<div class="relative group">
<span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant group-focus-within:text-primary-container transition-colors">lock</span>
<input class="w-full pl-12 pr-12 py-3 bg-surface rounded-xl border border-outline-variant focus:border-primary-container focus:ring-1 focus:ring-primary-container focus:outline-none transition-all font-body-md text-on-surface" id="password" name="password" required autocomplete="current-password" placeholder="••••••••" type="password"/>
<button class="absolute right-4 top-1/2 -translate-y-1/2 text-on-surface-variant hover:text-primary-container transition-colors" onclick="togglePassword()" type="button">
<span class="material-symbols-outlined" id="eyeIcon">visibility</span>
</button>
</div>
</div>
<div class="flex items-center justify-between font-label-md">
<label class="flex items-center gap-2 cursor-pointer group">
<div class="relative flex items-center justify-center">
<input class="peer appearance-none w-5 h-5 rounded border border-outline-variant checked:bg-primary-container checked:border-primary-container transition-all" type="checkbox" id="remember" name="remember"/>
<span class="material-symbols-outlined absolute text-[16px] text-on-primary scale-0 peer-checked:scale-100 transition-transform">check</span>
</div>
<span class="text-on-surface-variant group-hover:text-on-surface transition-colors">Ingat saya</span>
</label>
@if (Route::has('password.request'))
<a class="text-primary-container font-semibold hover:underline transition-all" href="{{ route('password.request') }}">Lupa Kata Sandi?</a>
@endif
</div>
<button class="w-full py-4 bg-primary-container text-white font-semibold rounded-xl shadow-md hover:shadow-xl hover:-translate-y-1 active:translate-y-0 transition-all duration-300 flex items-center justify-center gap-3" type="submit">
<span>Masuk sebagai Siswa</span>
<span class="material-symbols-outlined">login</span>
</button>
</form>
<!-- Footer Links -->
<div class="mt-xl flex flex-col items-center gap-md">
<p class="font-body-md text-on-surface-variant">
                                Belum memiliki akun? 
                                <a class="text-primary-container font-semibold hover:underline" href="{{ route('register') }}">Daftar Sekarang</a>
</p>
<div class="w-full h-[1px] bg-outline-variant"></div>
<a class="flex items-center gap-2 text-on-surface-variant hover:text-secondary transition-colors font-label-md group" href="{{ route('login.guru') }}">
<span class="material-symbols-outlined text-[18px]">school</span>
<span>Masuk sebagai Guru</span>
<span class="material-symbols-outlined text-[16px] translate-x-[-4px] opacity-0 group-hover:translate-x-0 group-hover:opacity-100 transition-all">arrow_forward</span>
</a>
</div>
</div>
</div>
</div>
</div>
<script>
            function togglePassword() {
                const input = document.getElementById('password');
                const icon = document.getElementById('eyeIcon');
                if (input.type === 'password') {
                    input.type = 'text';
                    icon.textContent = 'visibility_off';
                } else {
                    input.type = 'password';
                    icon.textContent = 'visibility';
                }
            }

            // We let Laravel handle the actual form submission, so we just add a visual spin class
            document.getElementById('loginForm').addEventListener('submit', function(e) {
                const btn = e.target.querySelector('button[type="submit"]');
                const originalContent = btn.innerHTML;
                
                // Allow the form to submit while showing loading state
                btn.innerHTML = '<span class="material-symbols-outlined animate-spin">progress_activity</span> <span>Memproses...</span>';
            });
        </script>
</main>
</body>
</html>
