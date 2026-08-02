<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Ekskul - Registrasi Siswa</title>
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
<body class="bg-background font-body-md text-on-background">
<header class="fixed top-0 w-full z-50 bg-surface-container-lowest/80 backdrop-blur-xl transition-all duration-300 shadow-[0_1px_8px_rgba(0,0,0,0.04)]">
<div class="h-16 max-w-[1280px] mx-auto px-margin-mobile lg:px-margin-desktop flex items-center justify-between">
<div class="flex items-center gap-md">
<span class="material-symbols-outlined text-primary text-[32px]">school</span>
</div>
<nav class="hidden lg:flex items-center gap-lg">
<a aria-current="page" class="transition-colors text-primary font-bold" data-path="beranda" href="{{ url('/') }}">Beranda</a>
<a class="font-body-md text-on-surface-variant hover:text-on-surface transition-colors" data-path="tentang" href="#">Tentang</a>
<a class="font-body-md text-on-surface-variant hover:text-on-surface transition-colors" data-path="kontak" href="#">Kontak</a>
</nav>
<div class="flex items-center gap-md">
<a class="hidden sm:block font-label-md text-on-surface-variant hover:text-on-surface" data-path="login-guru" href="{{ route('login.guru') }}">Login Guru</a>
<a href="{{ route('login') }}" class="bg-primary text-on-primary px-lg py-2 rounded-full font-label-md hover:scale-[1.02] transition-transform flex items-center justify-center">Masuk Siswa</a>
<div class="w-8 h-8 rounded-full bg-primary flex items-center justify-center">
<span class="material-symbols-outlined text-on-primary text-[18px]">person</span>
</div>
</div>
</div>
</header>
<main class="w-full pt-16">
<div class="flex flex-col w-full">
<!-- Main Form Section -->
<section class="max-w-[1280px] mx-auto px-margin-mobile lg:px-margin-desktop py-xl w-full" id="registration-container">
<div class="grid grid-cols-1 lg:grid-cols-12 gap-gutter">
<!-- Left Column: Progress & Context -->
<div class="lg:col-span-4 flex flex-col gap-xl">
<div class="flex flex-col gap-md">
<span class="font-label-md text-primary uppercase tracking-widest">Registrasi Siswa</span>
<h1 class="font-headline-lg text-on-surface">Mulai Perjalanan Bakatmu di Sini</h1>
<p class="font-body-md text-on-surface-variant">Bergabunglah dengan komunitas ekskul sekolah dan kembangkan potensi terbaikmu melalui berbagai kegiatan pilihan.</p>
</div>
<!-- Progress Indicator -->
<div class="relative flex flex-row lg:flex-col gap-lg lg:gap-lg overflow-x-auto lg:overflow-visible hide-scrollbar snap-x pb-4 lg:pb-0 w-full">
<div class="absolute left-[15px] top-0 bottom-0 w-[2px] bg-surface-container-high hidden lg:block"></div>
<div class="absolute top-[15px] left-0 right-0 h-[2px] bg-surface-container-high lg:hidden z-0"></div>
<div class="step-indicator relative flex flex-col lg:flex-row items-center lg:items-center text-center lg:text-left gap-2 lg:gap-md group min-w-[100px] lg:min-w-0 snap-center shrink-0 z-10" data-step="1">
<div class="step-dot w-8 h-8 rounded-full border-2 border-primary bg-primary text-on-primary flex items-center justify-center z-10 transition-all duration-300 mx-auto lg:mx-0">
<span class="material-symbols-outlined text-[18px]">person_add</span>
</div>
<div class="flex flex-col">
<span class="font-label-sm text-primary">Langkah 1</span>
<span class="font-title-md text-on-surface text-[14px] lg:text-[20px] whitespace-nowrap">Akun Pengguna</span>
</div>
</div>
<div class="step-indicator relative flex flex-col lg:flex-row items-center lg:items-center text-center lg:text-left gap-2 lg:gap-md group min-w-[100px] lg:min-w-0 snap-center shrink-0 z-10 opacity-40" data-step="2">
<div class="step-dot w-8 h-8 rounded-full border-2 border-outline-variant bg-surface-container-lowest text-on-surface-variant flex items-center justify-center z-10 transition-all duration-300 mx-auto lg:mx-0">
<span class="material-symbols-outlined text-[18px]">badge</span>
</div>
<div class="flex flex-col">
<span class="font-label-sm text-on-surface-variant">Langkah 2</span>
<span class="font-title-md text-on-surface text-[14px] lg:text-[20px] whitespace-nowrap">Data Diri</span>
</div>
</div>
<div class="step-indicator relative flex flex-col lg:flex-row items-center lg:items-center text-center lg:text-left gap-2 lg:gap-md group min-w-[100px] lg:min-w-0 snap-center shrink-0 z-10 opacity-40" data-step="3">
<div class="step-dot w-8 h-8 rounded-full border-2 border-outline-variant bg-surface-container-lowest text-on-surface-variant flex items-center justify-center z-10 transition-all duration-300 mx-auto lg:mx-0">
<span class="material-symbols-outlined text-[18px]">sports_soccer</span>
</div>
<div class="flex flex-col">
<span class="font-label-sm text-on-surface-variant">Langkah 3</span>
<span class="font-title-md text-on-surface text-[14px] lg:text-[20px] whitespace-nowrap">Pilih Ekskul</span>
</div>
</div>
<div class="step-indicator relative flex flex-col lg:flex-row items-center lg:items-center text-center lg:text-left gap-2 lg:gap-md group min-w-[100px] lg:min-w-0 snap-center shrink-0 z-10 opacity-40" data-step="4">
<div class="step-dot w-8 h-8 rounded-full border-2 border-outline-variant bg-surface-container-lowest text-on-surface-variant flex items-center justify-center z-10 transition-all duration-300 mx-auto lg:mx-0">
<span class="material-symbols-outlined text-[18px]">verified</span>
</div>
<div class="flex flex-col">
<span class="font-label-sm text-on-surface-variant">Langkah 4</span>
<span class="font-title-md text-on-surface text-[14px] lg:text-[20px] whitespace-nowrap">Konfirmasi</span>
</div>
</div>
</div>
</div>
<!-- Right Column: Form Multi-step -->
<div class="lg:col-span-8">
<div class="bg-surface-container-lowest p-xl rounded-xl shadow-xl min-h-[600px] flex flex-col border border-surface-container-high">
<form method="POST" action="{{ route('register') }}" class="flex-grow" id="regForm">
@csrf
<!-- STEP 1: AKUN -->
<div class="step-content space-y-lg animate-in fade-in slide-in-from-right-4 duration-500" id="step-1-content">
<div class="grid grid-cols-1 md:grid-cols-2 gap-lg">
<div class="flex flex-col gap-xs">
<label class="font-label-md text-on-surface-variant">Nama Lengkap</label>
<input class="w-full h-11 px-4 bg-surface-container-low rounded-lg outline-none focus:ring-2 focus:ring-primary/20 focus:bg-surface-container-lowest border border-outline-variant transition-all" name="name" placeholder="Masukkan nama sesuai raport" type="text" value="{{ old('name') }}" required/>
</div>
<div class="flex flex-col gap-xs">
<label class="font-label-md text-on-surface-variant">Email Aktif</label>
<input class="w-full h-11 px-4 bg-surface-container-low rounded-lg outline-none focus:ring-2 focus:ring-primary/20 focus:bg-surface-container-lowest border border-outline-variant transition-all" name="email" placeholder="contoh@sekolah.sch.id" type="email" value="{{ old('email') }}" required/>
</div>
<div class="flex flex-col gap-xs">
<label class="font-label-md text-on-surface-variant">Nomor WhatsApp</label>
<input class="w-full h-11 px-4 bg-surface-container-low rounded-lg outline-none focus:ring-2 focus:ring-primary/20 focus:bg-surface-container-lowest border border-outline-variant transition-all" name="whatsapp" placeholder="0812..." type="tel"/>
</div>
</div>
<div class="grid grid-cols-1 md:grid-cols-2 gap-lg">
<div class="flex flex-col gap-xs">
<label class="font-label-md text-on-surface-variant">Kata Sandi</label>
<input class="w-full h-11 px-4 bg-surface-container-low rounded-lg outline-none focus:ring-2 focus:ring-primary/20 focus:bg-surface-container-lowest border border-outline-variant transition-all" id="password" name="password" type="password" required/>
<div class="h-1 w-full bg-surface-container-high rounded-full mt-2 overflow-hidden">
<div class="h-full w-1/3 bg-error transition-all duration-500" id="strength-bar"></div>
</div>
<span class="text-[12px] text-on-surface-variant" id="strength-text">Kekuatan: Lemah</span>
</div>
<div class="flex flex-col gap-xs">
<label class="font-label-md text-on-surface-variant">Konfirmasi Kata Sandi</label>
<input class="w-full h-11 px-4 bg-surface-container-low rounded-lg outline-none focus:ring-2 focus:ring-primary/20 focus:bg-surface-container-lowest border border-outline-variant transition-all" name="password_confirmation" type="password" required/>
</div>
</div>
</div>
<!-- STEP 2: DATA DIRI -->
<div class="step-content hidden space-y-lg animate-in fade-in slide-in-from-right-4 duration-500" id="step-2-content">
<div class="grid grid-cols-1 md:grid-cols-3 gap-lg">
<div class="flex flex-col gap-xs">
<label class="font-label-md text-on-surface-variant">NIS / NISN</label>
<input class="w-full h-11 px-4 bg-surface-container-low rounded-lg outline-none focus:ring-2 focus:ring-primary/20 border border-outline-variant transition-all" name="nisn" type="text"/>
</div>
<div class="flex flex-col gap-xs">
<label class="font-label-md text-on-surface-variant">Kelas</label>
<select class="w-full h-11 px-4 bg-surface-container-low rounded-lg outline-none focus:ring-2 focus:ring-primary/20 border border-outline-variant transition-all appearance-none" name="class">
<option>Pilih Kelas</option>
<option>X-1</option>
<option>XI-IPA-1</option>
<option>XII-IPS-2</option>
</select>
</div>
<div class="flex flex-col gap-xs">
<label class="font-label-md text-on-surface-variant">Tanggal Lahir</label>
<input class="w-full h-11 px-4 bg-surface-container-low rounded-lg outline-none focus:ring-2 focus:ring-primary/20 border border-outline-variant transition-all" name="dob" type="date"/>
</div>
</div>

</div>
<!-- STEP 3: PILIH EKSKUL -->
<div class="step-content hidden space-y-lg animate-in fade-in slide-in-from-right-4 duration-500" id="step-3-content">
<div class="bg-surface-container-low p-md rounded-xl border border-outline-variant mb-md">
    <label class="font-label-md text-on-surface-variant block mb-2">Kode Kelas (Opsional)</label>
    <p class="text-[12px] text-on-surface-variant mb-3">Masukkan kode kelas dari pembina jika ada, atau lewati dan isi nanti.</p>
    <input class="w-full h-11 px-4 bg-surface-container-lowest rounded-lg outline-none focus:ring-2 focus:ring-primary/20 border border-outline-variant transition-all font-mono tracking-widest uppercase" name="class_code" placeholder="MISAL: ROB-2024" type="text"/>
</div>
<div class="flex justify-center mt-xl">
    <button type="button" onclick="document.getElementById('nextBtn').click()" class="px-6 py-3 border border-outline-variant rounded-xl font-label-md text-on-surface-variant hover:bg-surface-container hover:text-on-surface transition-all">
        Isi Nanti
    </button>
</div>

</div>
<!-- STEP 4: KONFIRMASI -->
<div class="step-content hidden space-y-lg animate-in fade-in slide-in-from-right-4 duration-500" id="step-4-content">
<div class="bg-surface-container-low p-lg rounded-xl border border-outline-variant">
<div class="flex items-center gap-lg mb-lg">
<div class="w-20 h-20 rounded-full bg-surface-dim border-2 border-primary-fixed overflow-hidden flex-shrink-0">
<img class="w-full h-full object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuB1e9biaceJJst8Yo8C4QcknIWkZC9tvCG_vVbRpQa4r_kv4nWXn29Qio5cs80xTEnTUYaMPr-iI7whbiTeZLsMH-teKGfv-RjkvBlNkKlOE0rdRR7RptEmkgfwgq5TV_KAYnSfb9iA2tAYumdD5Fmyt6s4ucmyEIjfI5liiFhMJMHxHBNN94OhcfDlWwEiNy6OXDq5Oo9UC8HvrCaSw2CM7ieO1tMe7D28L1sb5OW9WQtiLZ5BCRGG"/>
</div>
<div class="flex-grow">
<h3 class="font-title-md text-on-surface">Andi Pratama</h3>
<p class="text-on-surface-variant font-label-sm">NISN: 0045239121 • Kelas XI-IPA-1</p>
</div>
</div>
<div class="grid grid-cols-1 md:grid-cols-2 gap-md text-sm">
<div class="space-y-1">
<p class="text-on-surface-variant uppercase font-label-sm text-[10px]">Email</p>
<p class="font-body-md text-on-surface">andi.pratama@email.com</p>
</div>
<div class="space-y-1">
<p class="text-on-surface-variant uppercase font-label-sm text-[10px]">WhatsApp</p>
<p class="font-body-md text-on-surface">0812-3456-7890</p>
</div>

</div>
</div>
<label class="flex gap-md cursor-pointer group">
<input class="w-5 h-5 rounded border-outline-variant text-primary focus:ring-primary mt-1" type="checkbox"/>
<span class="text-on-surface-variant font-label-sm group-hover:text-on-surface transition-colors">Saya menyatakan bahwa data yang saya isi sudah benar dan bersedia mengikuti aturan yang berlaku di kegiatan ekstrakurikuler yang saya pilih.</span>
</label>
</div>
</form>
<!-- Navigation Buttons -->
<div class="flex items-center justify-between mt-xl pt-lg border-t border-outline-variant">
<button class="hidden font-label-md text-on-surface-variant hover:text-primary flex items-center gap-sm transition-colors" id="prevBtn">
<span class="material-symbols-outlined">arrow_back</span>
                                    Kembali
                                </button>
<div class="flex-grow"></div>
<button class="bg-primary text-on-primary px-xl py-3 rounded-full font-label-md hover:scale-[1.02] transition-all flex items-center gap-sm shadow-md" id="nextBtn" type="button">
                                    Lanjut
                                    <span class="material-symbols-outlined">arrow_forward</span>
</button>
</div>
</div>
</div>
</div>
</section>
<!-- Success Screen (Hidden by default) -->
<section class="hidden max-w-[1280px] mx-auto px-margin-mobile lg:px-margin-desktop py-xl w-full" id="success-screen">
<div class="max-w-[600px] mx-auto text-center flex flex-col items-center gap-lg py-xl animate-in zoom-in-95 duration-700">
<div class="w-24 h-24 rounded-full bg-primary/10 flex items-center justify-center text-primary mb-md relative">
<div class="absolute inset-0 rounded-full border-4 border-primary/20 animate-ping"></div>
<span class="material-symbols-outlined text-[64px]">verified</span>
</div>
<div class="space-y-sm">
<h1 class="font-display-lg text-on-surface">Pendaftaran Berhasil!</h1>
<p class="font-body-lg text-on-surface-variant">Data pendaftaran telah dikirim ke pembimbing ekskul. Kamu akan mendapatkan notifikasi via WhatsApp jika sudah diverifikasi.</p>
</div>
<div class="w-full bg-surface-container-low p-lg rounded-2xl flex flex-col gap-md border border-primary/10">
<div class="flex justify-between items-center px-md">
<span class="text-on-surface-variant font-label-md">ID PENDAFTARAN</span>
<span class="font-title-md text-primary font-mono tracking-widest">REG-2024-0892</span>
</div>
<div class="h-[1px] bg-outline-variant/30"></div>
<div class="flex justify-between items-center px-md">
<span class="text-on-surface-variant font-label-md">EKSKUL</span>
<span class="font-body-md text-on-surface font-bold">Robotik</span>
</div>
<div class="flex justify-between items-center px-md">
<span class="text-on-surface-variant font-label-md">STATUS</span>
<span class="bg-primary/10 text-primary px-3 py-1 rounded-full text-[12px] font-bold">MENUNGGU VERIFIKASI</span>
</div>
</div>
<div class="flex flex-col sm:flex-row gap-md w-full pt-md">
<a href="{{ route('login') }}" class="flex-1 bg-primary text-on-primary py-3 rounded-full font-label-md hover:bg-primary/90 transition-colors inline-flex justify-center items-center">Masuk ke Akun</a>
<a href="{{ url('/') }}" class="flex-1 border border-outline-variant text-on-surface py-3 rounded-full font-label-md hover:bg-surface-container-low transition-colors inline-flex justify-center items-center">Kembali ke Beranda</a>
</div>
</div>
</section>
</div>
</main>
<footer class="w-full bg-surface-container-low py-xl">
<div class="max-w-[1280px] mx-auto px-margin-mobile lg:px-margin-desktop">
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-gutter mb-xl">
<div class="flex items-center gap-2 mb-4">
<span class="material-symbols-outlined text-[#0ea5e9] text-[32px]">school</span>
</div>
<p class="text-on-surface-variant font-label-sm">Kelola kegiatan sekolah lebih mudah dan terorganisir bersama Ekskul.</p>
</div>
<div class="flex flex-col gap-sm">
<span class="font-label-md text-on-surface mb-xs">Akses Utama</span>
<a class="text-on-surface-variant hover:text-[#0ea5e9] transition-colors font-label-sm" data-path="login-siswa" href="{{ route('login') }}">Login Siswa</a>
<a class="text-on-surface-variant hover:text-[#0ea5e9] transition-colors font-label-sm" data-path="login-guru" href="{{ route('login.guru') }}">Login Guru</a>
</div>
<div class="flex flex-col gap-sm">
<span class="font-label-md text-on-surface mb-xs">Informasi</span>
<a class="text-on-surface-variant hover:text-primary transition-colors font-label-sm" data-path="kebijakan-privasi" href="#">Kebijakan Privasi</a>
<a class="text-on-surface-variant hover:text-primary transition-colors font-label-sm" data-path="syarat-ketentuan" href="#">Syarat &amp; Ketentuan</a>
</div>
<div class="flex flex-col gap-sm">
<span class="font-label-md text-on-surface mb-xs">Ikuti Kami</span>
<div class="flex gap-md">
<span class="material-symbols-outlined text-on-surface-variant hover:text-primary cursor-pointer">public</span>
<span class="material-symbols-outlined text-on-surface-variant hover:text-primary cursor-pointer">mail</span>
</div>
</div>
</div>
<div class="border-t border-outline-variant pt-md flex flex-col md:flex-row justify-between items-center gap-md text-on-surface-variant font-label-sm">
<span>© 2024 Ekskul. Seluruh hak cipta dilindungi.</span>
<div class="flex gap-md">
<span class="material-symbols-outlined text-[20px]">verified</span>
<span>Partner Sekolah Resmi</span>
</div>
</div>
</div>
</footer>
<script>
        let currentStep = 1;
        const totalSteps = 4;
        const form = document.getElementById('regForm');
        const nextBtn = document.getElementById('nextBtn');
        const prevBtn = document.getElementById('prevBtn');
        const indicators = document.querySelectorAll('.step-indicator');
        const contents = document.querySelectorAll('.step-content');
        const regContainer = document.getElementById('registration-container');
        const successScreen = document.getElementById('success-screen');

        function updateUI() {
            // Update contents
            contents.forEach((content, idx) => {
                if (idx + 1 === currentStep) {
                    content.classList.remove('hidden');
                } else {
                    content.classList.add('hidden');
                }
            });

            // Update indicators
            indicators.forEach((ind, idx) => {
                const step = idx + 1;
                const dot = ind.querySelector('.step-dot');
                const labelSm = ind.querySelector('.font-label-sm');

                if (step === currentStep) {
                    ind.classList.remove('opacity-40');
                    dot.classList.add('bg-primary', 'text-on-primary', 'border-primary');
                    dot.classList.remove('bg-surface-container-lowest', 'text-on-surface-variant', 'border-outline-variant', 'bg-tertiary');
                    dot.innerHTML = getInitialIcon(step);
                    if(labelSm) labelSm.classList.add('text-primary');
                } else if (step < currentStep) {
                    ind.classList.remove('opacity-40');
                    dot.classList.add('bg-tertiary', 'text-on-tertiary', 'border-tertiary');
                    dot.classList.remove('bg-primary');
                    dot.innerHTML = '<span class="material-symbols-outlined text-[18px]">check</span>';
                    if(labelSm) labelSm.classList.remove('text-primary');
                } else {
                    ind.classList.add('opacity-40');
                    dot.classList.remove('bg-primary', 'bg-tertiary', 'text-on-primary', 'text-on-tertiary', 'border-primary', 'border-tertiary');
                    dot.classList.add('bg-surface-container-lowest', 'text-on-surface-variant', 'border-outline-variant');
                    dot.innerHTML = getInitialIcon(step);
                    if(labelSm) labelSm.classList.remove('text-primary');
                }
            });

            // Update Buttons
            prevBtn.classList.toggle('hidden', currentStep === 1);
            if (currentStep === totalSteps) {
                nextBtn.innerHTML = 'Kirim Pendaftaran <span class="material-symbols-outlined">send</span>';
            } else {
                nextBtn.innerHTML = 'Lanjut <span class="material-symbols-outlined">arrow_forward</span>';
            }
        }

        function getInitialIcon(step) {
            const icons = ['person_add', 'badge', 'sports_soccer', 'verified'];
            return `<span class="material-symbols-outlined text-[18px]">${icons[step-1]}</span>`;
        }

        nextBtn.addEventListener('click', () => {
            if (currentStep < totalSteps) {
                currentStep++;
                updateUI();
                window.scrollTo({ top: 0, behavior: 'smooth' });
            } else {
                form.submit();
            }
        });

        prevBtn.addEventListener('click', () => {
            if (currentStep > 1) {
                currentStep--;
                updateUI();
            }
        });

        // Password Strength Simulation
        const passInput = document.getElementById('password');
        const strengthBar = document.getElementById('strength-bar');
        const strengthText = document.getElementById('strength-text');
        passInput.addEventListener('input', (e) => {
            const val = e.target.value;
            if (val.length === 0) {
                strengthBar.style.width = '0%';
                strengthText.innerText = 'Kekuatan: -';
            } else if (val.length < 4) {
                strengthBar.style.width = '20%';
                strengthBar.className = 'h-full bg-error transition-all duration-500';
                strengthText.innerText = 'Kekuatan: Lemah';
            } else if (val.length < 8) {
                strengthBar.style.width = '50%';
                strengthBar.className = 'h-full bg-tertiary-container transition-all duration-500';
                strengthText.innerText = 'Kekuatan: Sedang';
            } else {
                strengthBar.style.width = '100%';
                strengthBar.className = 'h-full bg-primary transition-all duration-500';
                strengthText.innerText = 'Kekuatan: Kuat';
            }
        });
    </script>
</body>
</html>
