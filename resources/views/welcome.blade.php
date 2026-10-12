<!DOCTYPE html>
<html lang="id" data-role="landing">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ekskul Hub - Absensi dan Kegiatan Ekstrakurikuler Sekolah</title>
    <link rel="icon" type="image/svg+xml" href="/favicon.svg">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-paper font-sans text-on-surface antialiased">
@php
    // Lapisan ekstrusi judul 3D (hijau gelap), dari yang paling dekat ke paling jauh.
    $lapisanEkstrusi = ['#4E8A1F', '#477F1C', '#40741A', '#396818', '#325C15', '#2B5012', '#244410', '#1D380D', '#162C0A', '#102107'];
@endphp

<!-- Header -->
<header class="sticky top-0 z-40 border-b border-outline-variant bg-paper/95">
    <div class="mx-auto flex max-w-container-max items-center justify-between gap-4 px-4 py-4 md:px-8">
        <a href="/" class="flex items-center gap-3">
            <span class="flex size-10 items-center justify-center rounded-xl bg-primary-container text-surface">
                <x-icon name="graduation-cap" class="size-[22px]" />
            </span>
            <span class="text-title-md font-bold">Ekskul Hub</span>
        </a>

        <nav class="hidden items-center gap-8 text-label-lg text-on-surface-variant md:flex">
            <a href="#fitur" class="transition-colors hover:text-primary-container">Fitur</a>
            <a href="#alur" class="transition-colors hover:text-primary-container">Alur</a>
            <a href="#peran" class="transition-colors hover:text-primary-container">Untuk siapa</a>
        </nav>

        <a href="{{ route('login') }}" class="inline-flex rounded-full bg-primary-container px-5 py-2.5 text-label-lg text-surface transition-opacity hover:opacity-90">Login siswa</a>
    </div>
</header>

<main>
    <!-- Hero -->
    <section class="px-4 pt-4 md:px-8">
        <div id="hero" class="relative mx-auto max-w-container-max overflow-hidden rounded-[2rem] bg-primary-container px-6 pb-14 pt-12 text-surface md:rounded-[2.5rem] md:px-12 md:pb-16 md:pt-16">
            <!-- Cincin dekoratif -->
            <div class="pointer-events-none absolute -right-28 -top-28 size-96 rounded-full border-[36px] border-primary/10"></div>
            <div class="pointer-events-none absolute -bottom-36 -left-24 size-[28rem] rounded-full border-[36px] border-primary/10"></div>

            <div class="relative grid items-center gap-12 lg:grid-cols-2">
                <div>
                    <span class="inline-flex rounded-full bg-primary px-4 py-1.5 text-label-md text-on-primary">Halo! Kami adalah</span>

                    <!-- Judul 3D yang bisa digeser -->
                    <div id="title-stage" class="title-stage relative mt-6 flex justify-start rounded-3xl px-2 focus-visible:outline-2 focus-visible:outline-primary" tabindex="0" role="img" aria-label="Ekskul Hub. Judul ini bisa digeser dengan mouse atau sentuhan.">
                        <div id="title-3d" class="title-3d text-[48px] font-extrabold leading-none sm:text-[64px] lg:text-[68px] xl:text-[80px]">
                            <span class="title-layer title-front relative">Ekskul Hub</span>
                            @foreach($lapisanEkstrusi as $i => $warna)
                                <span class="title-layer is-extrude" aria-hidden="true"
                                      style="color: {{ $warna }}; transform: translate3d({{ round(($i + 1) * 0.8, 1) }}px, {{ round(($i + 1) * 1.2, 1) }}px, {{ -round(($i + 1) * 1.5, 1) }}px)">Ekskul Hub</span>
                            @endforeach
                        </div>
                    </div>
                    <p class="relative mt-4 text-body-sm text-surface/60">Seret judulnya untuk menggeser dan memiringkannya.</p>

                    <p class="relative mt-6 max-w-xl text-body-lg text-surface/85">
                        Sistem absensi dan manajemen ekstrakurikuler sekolah. Siswa mendaftar dan absen, guru mengatur jadwal dan menilai.
                    </p>

                    <div class="relative mt-8 grid max-w-md grid-cols-2 gap-3 sm:flex sm:max-w-none sm:flex-wrap">
                        <a href="{{ route('login') }}" class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-full bg-primary px-4 py-3 text-label-lg text-on-primary shadow-lg transition-transform hover:-translate-y-0.5 md:px-6">
                            Login siswa <x-icon name="arrow-right" class="size-[18px]" />
                        </a>
                        <a href="{{ route('login.guru') }}" class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-full bg-surface px-4 py-3 text-label-lg text-on-surface shadow-lg transition-transform hover:-translate-y-0.5 md:px-6">
                            Login guru <x-icon name="arrow-right" class="size-[18px]" />
                        </a>
                        <a href="{{ route('register') }}" class="inline-flex items-center justify-center whitespace-nowrap rounded-full border-2 border-surface/40 px-4 py-3 text-label-lg text-surface transition-colors hover:bg-surface/10 md:px-6">
                            Daftar siswa
                        </a>
                        <a href="{{ route('register.guru') }}" class="inline-flex items-center justify-center whitespace-nowrap rounded-full border-2 border-surface/40 px-4 py-3 text-label-lg text-surface transition-colors hover:bg-surface/10 md:px-6">
                            Daftar guru
                        </a>
                    </div>
                </div>

                <!-- Gambar hero dengan dua label melayang -->
                <div class="relative mx-auto w-full max-w-md">
                    <div class="overflow-hidden rounded-[2rem] bg-surface/5 shadow-2xl">
                        <img src="/images/landing/hero-phone.png" alt="Ilustrasi layar absensi QR pada ponsel siswa" class="aspect-[4/5] w-full object-cover" loading="eager">
                    </div>
                    <div class="absolute -left-2 top-8 flex items-center gap-2 rounded-2xl bg-surface px-4 py-3 text-label-md text-on-surface shadow-lg md:-left-6">
                        <x-icon name="qr-code" class="size-[18px] text-primary-container" /> Absensi QR
                    </div>
                    <div class="absolute -right-2 bottom-10 flex items-center gap-2 rounded-2xl bg-primary px-4 py-3 text-label-md text-on-primary shadow-lg md:-right-6">
                        <x-icon name="camera" class="size-[18px]" /> Selfie sebagai bukti
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Fitur -->
    <section id="fitur" class="px-4 py-16 md:py-24">
        <div class="mx-auto flex max-w-container-max flex-col gap-6 md:flex-row md:items-end md:justify-between">
            <div>
                <span class="inline-flex rounded-full bg-primary px-4 py-1.5 text-label-md text-on-primary">Fitur</span>
                <h2 class="mt-4 max-w-2xl text-headline-md font-bold md:text-headline-lg">Yang bisa dilakukan di Ekskul Hub</h2>
            </div>
            <p class="max-w-xs text-body-md text-on-surface-variant">Fitur yang sudah tersedia di sistem saat ini.</p>
        </div>

        <div class="mx-auto mt-12 grid max-w-container-max gap-5 sm:grid-cols-2 lg:grid-cols-4">
            <article class="flex min-h-[30rem] flex-col justify-between overflow-hidden rounded-[2rem] bg-surface border border-outline-variant p-6 text-on-surface transition-transform duration-300 hover:-translate-y-1">
                <h3 class="text-headline-sm font-bold">Absensi QR</h3>
                <img src="/images/landing/fitur-qr.png" alt="Guru menampilkan QR di laptop, siswa memindainya dengan ponsel" class="mt-6 aspect-[4/3] w-full rounded-2xl object-cover" loading="lazy">
                <p class="text-body-sm text-on-surface-variant">Guru membuka sesi dan menampilkan QR. Siswa memindai langsung dari ponselnya.</p>
            </article>

            <article class="flex min-h-[30rem] flex-col justify-between overflow-hidden rounded-[2rem] bg-surface border border-outline-variant p-6 text-on-surface transition-transform duration-300 hover:-translate-y-1">
                <h3 class="text-headline-sm font-bold">Selfie dan bukti</h3>
                <img src="/images/landing/fitur-selfie.png" alt="Siswa mengambil selfie sebagai bukti kehadiran" class="mt-6 aspect-[4/3] w-full rounded-2xl object-cover" loading="lazy">
                <p class="text-body-sm text-on-surface-variant">Siswa bisa mengirim selfie atau lampiran (JPG, PNG, atau PDF) sebagai bukti kehadiran.</p>
            </article>

            <article class="flex min-h-[30rem] flex-col justify-between overflow-hidden rounded-[2rem] bg-surface border border-outline-variant p-6 text-on-surface transition-transform duration-300 hover:-translate-y-1">
                <h3 class="text-headline-sm font-bold">Jadwal dan pendaftaran</h3>
                <img src="/images/landing/fitur-jadwal.png" alt="Kalender dengan jam dan ikon kegiatan ekskul" class="mt-6 aspect-[4/3] w-full rounded-2xl object-cover" loading="lazy">
                <p class="text-body-sm text-on-surface-variant">Guru mengatur jadwal ekskul. Siswa mendaftar dan memantau status persetujuannya.</p>
            </article>

            <article class="flex min-h-[30rem] flex-col justify-between overflow-hidden rounded-[2rem] bg-surface border border-outline-variant p-6 text-on-surface transition-transform duration-300 hover:-translate-y-1">
                <h3 class="text-headline-sm font-bold">Penilaian dan rekap</h3>
                <img src="/images/landing/fitur-penilaian.png" alt="Laporan dengan grafik batang dan piala" class="mt-6 aspect-[4/3] w-full rounded-2xl object-cover" loading="lazy">
                <p class="text-body-sm text-on-surface-variant">Penilaian per komponen, dan rekap hasil yang bisa diunduh guru.</p>
            </article>
        </div>
    </section>

    <!-- Alur -->
    <section id="alur" class="px-4 py-16 md:py-24">
        <div class="mx-auto grid max-w-container-max gap-6 lg:grid-cols-2 lg:items-stretch">
            <div class="flex min-h-[28rem] flex-col justify-between rounded-[2rem] bg-primary-container p-8 text-surface md:p-12">
                <div>
                    <span class="inline-flex rounded-full bg-primary px-4 py-1.5 text-label-md text-on-primary">Alur absensi</span>
                    <p class="mt-6 text-headline-md font-bold md:text-headline-lg">Dari sesi dibuka sampai kehadiran tercatat.</p>
                </div>
                <div class="mt-10 grid grid-cols-3 gap-3 text-center">
                    <div class="rounded-2xl bg-surface/10 p-4">
                        <span class="block text-headline-md font-bold text-primary">1</span>
                        <span class="mt-1 block text-label-xs text-surface/80">Sesi dibuka</span>
                    </div>
                    <div class="rounded-2xl bg-surface/10 p-4">
                        <span class="block text-headline-md font-bold text-primary">2</span>
                        <span class="mt-1 block text-label-xs text-surface/80">Siswa absen</span>
                    </div>
                    <div class="rounded-2xl bg-surface/10 p-4">
                        <span class="block text-headline-md font-bold text-primary">3</span>
                        <span class="mt-1 block text-label-xs text-surface/80">Tercatat</span>
                    </div>
                </div>
            </div>

            <ol class="flex flex-col gap-5">
                <li class="flex gap-5 rounded-[2rem] bg-surface p-6 shadow-sm">
                    <span class="flex size-12 shrink-0 items-center justify-center rounded-full bg-primary text-title-md font-bold text-on-primary">1</span>
                    <div>
                        <h3 class="text-title-md font-bold">Guru membuka sesi</h3>
                        <p class="mt-1 text-body-md text-on-surface-variant">Sesi dibuat untuk satu kegiatan dan menampilkan QR yang berlaku sementara.</p>
                    </div>
                </li>
                <li class="flex gap-5 rounded-[2rem] bg-primary-fixed p-6">
                    <span class="flex size-12 shrink-0 items-center justify-center rounded-full bg-primary-container text-title-md font-bold text-surface">2</span>
                    <div>
                        <h3 class="text-title-md font-bold text-on-primary-fixed">Siswa memindai atau mengirim selfie</h3>
                        <p class="mt-1 text-body-md text-on-primary-fixed/80">Siswa memindai QR dari ponselnya, atau mengunggah selfie sebagai bukti hadir.</p>
                    </div>
                </li>
                <li class="flex gap-5 rounded-[2rem] bg-surface p-6 shadow-sm">
                    <span class="flex size-12 shrink-0 items-center justify-center rounded-full bg-primary-container text-title-md font-bold text-surface">3</span>
                    <div>
                        <h3 class="text-title-md font-bold">Kehadiran tercatat</h3>
                        <p class="mt-1 text-body-md text-on-surface-variant">Status tampil di sesi untuk guru, dan di riwayat absensi untuk siswa.</p>
                    </div>
                </li>
            </ol>
        </div>
    </section>

    <!-- Untuk siapa -->
    <section id="peran" class="px-4 pb-16 md:pb-24">
        <div class="mx-auto max-w-container-max">
            <h2 class="text-headline-md font-bold md:text-headline-lg">Untuk siswa dan guru</h2>

            <div class="mt-12 grid gap-6 lg:grid-cols-2">
                <div class="flex flex-col justify-between rounded-[2rem] bg-primary p-8 text-on-primary md:p-10">
                    <div>
                        <span class="flex size-14 items-center justify-center rounded-2xl bg-on-primary/10">
                            <x-icon name="graduation-cap" class="size-[26px]" />
                        </span>
                        <h3 class="mt-6 text-headline-sm font-bold">Siswa</h3>
                        <ul class="mt-5 space-y-3 text-body-md">
                            <li class="flex gap-3"><x-icon name="check" class="mt-0.5 size-[18px] shrink-0" /><span>Daftar ke kegiatan ekskul yang diminati.</span></li>
                            <li class="flex gap-3"><x-icon name="check" class="mt-0.5 size-[18px] shrink-0" /><span>Catat kehadiran lewat QR atau selfie.</span></li>
                            <li class="flex gap-3"><x-icon name="check" class="mt-0.5 size-[18px] shrink-0" /><span>Lihat status pendaftaran dan penilaian.</span></li>
                        </ul>
                    </div>
                    <div class="mt-8 flex flex-wrap items-center gap-4">
                        <a href="{{ route('login') }}" class="inline-flex items-center gap-2 whitespace-nowrap rounded-full bg-primary-container px-5 py-3 text-label-lg text-surface transition-opacity hover:opacity-90">
                            Login siswa <x-icon name="arrow-right" class="size-[18px]" />
                        </a>
                        <a href="{{ route('register') }}" class="text-label-lg text-on-primary hover:underline">Daftar siswa</a>
                    </div>
                </div>

                <div class="flex flex-col justify-between rounded-[2rem] bg-primary-container p-8 text-surface md:p-10">
                    <div>
                        <span class="flex size-14 items-center justify-center rounded-2xl bg-surface/10">
                            <x-icon name="users" class="size-[26px]" />
                        </span>
                        <h3 class="mt-6 text-headline-sm font-bold">Guru dan pembina</h3>
                        <ul class="mt-5 space-y-3 text-body-md text-surface/85">
                            <li class="flex gap-3"><x-icon name="check" class="mt-0.5 size-[18px] shrink-0 text-primary" /><span>Atur jadwal ekskul dan buka sesi absensi.</span></li>
                            <li class="flex gap-3"><x-icon name="check" class="mt-0.5 size-[18px] shrink-0 text-primary" /><span>Tinjau pendaftar dan kelola data peserta.</span></li>
                            <li class="flex gap-3"><x-icon name="check" class="mt-0.5 size-[18px] shrink-0 text-primary" /><span>Input penilaian dan unduh rekap.</span></li>
                        </ul>
                    </div>
                    <div class="mt-8 flex flex-wrap items-center gap-4">
                        <a href="{{ route('login.guru') }}" class="inline-flex items-center gap-2 whitespace-nowrap rounded-full bg-primary px-5 py-3 text-label-lg text-on-primary transition-opacity hover:opacity-90">
                            Login guru <x-icon name="arrow-right" class="size-[18px]" />
                        </a>
                        <a href="{{ route('register.guru') }}" class="text-label-lg text-surface hover:underline">Daftar guru</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<!-- Footer -->
<footer class="bg-primary-container px-4 py-12 text-surface">
    <div class="mx-auto flex max-w-container-max flex-col gap-8 md:flex-row md:items-center md:justify-between">
        <div>
            <p class="text-title-md font-bold">Ekskul Hub</p>
            <p class="mt-1 text-body-sm text-surface/75">Sistem presensi dan manajemen ekstrakurikuler sekolah.</p>
        </div>
        <nav class="flex flex-wrap gap-x-6 gap-y-2 text-label-md text-surface/85">
            <a href="{{ route('login') }}" class="hover:text-primary">Login siswa</a>
            <a href="{{ route('login.guru') }}" class="hover:text-primary">Login guru</a>
            <a href="{{ route('register') }}" class="hover:text-primary">Daftar siswa</a>
            <a href="{{ route('register.guru') }}" class="hover:text-primary">Daftar guru</a>
        </nav>
    </div>
    <p class="mx-auto mt-10 max-w-container-max border-t border-surface/15 pt-6 text-body-xs text-surface/70">© {{ date('Y') }} Ekskul Hub.</p>
</footer>

<script>
    // Judul 3D: bisa digeser (mouse, sentuhan, atau panah keyboard), ikut miring saat digeser, dan bisa dikembalikan dengan double-click.
    (() => {
        const stage = document.getElementById('title-stage');
        const judul = document.getElementById('title-3d');
        const hero = document.getElementById('hero');
        const batas = (nilai, min, maks) => Math.min(maks, Math.max(min, nilai));

        let sedangDigeser = false;
        let x = 0, y = 0, rx = 0, ry = 0;
        let mulaiX = 0, mulaiY = 0, lastX = 0, lastY = 0;

        const tampilkan = () => {
            judul.style.transform = `translate3d(${x}px, ${y}px, 0) rotateX(${rx}deg) rotateY(${ry}deg)`;
        };

        const ruangGerak = () => {
            const h = hero.getBoundingClientRect();
            return { mx: h.width * 0.28, my: h.height * 0.22 };
        };

        stage.addEventListener('pointerdown', (e) => {
            sedangDigeser = true;
            stage.setPointerCapture(e.pointerId);
            mulaiX = e.clientX - x;
            mulaiY = e.clientY - y;
            lastX = e.clientX;
            lastY = e.clientY;
            judul.style.transition = 'none';
        });

        stage.addEventListener('pointermove', (e) => {
            if (!sedangDigeser) return;
            const b = ruangGerak();
            x = batas(e.clientX - mulaiX, -b.mx, b.mx);
            y = batas(e.clientY - mulaiY, -b.my, b.my);
            ry = batas((e.clientX - lastX) * 1.2, -28, 28);
            rx = batas(-(e.clientY - lastY) * 1.2, -28, 28);
            lastX = e.clientX;
            lastY = e.clientY;
            tampilkan();
        });

        const lepas = () => {
            if (!sedangDigeser) return;
            sedangDigeser = false;
            rx = 0;
            ry = 0;
            judul.style.transition = 'transform 0.5s cubic-bezier(0.22, 1, 0.36, 1)';
            tampilkan();
        };
        stage.addEventListener('pointerup', lepas);
        stage.addEventListener('pointercancel', lepas);

        stage.addEventListener('dblclick', () => {
            x = 0;
            y = 0;
            judul.style.transition = 'transform 0.5s cubic-bezier(0.22, 1, 0.36, 1)';
            tampilkan();
        });

        stage.addEventListener('keydown', (e) => {
            const langkah = 24;
            const arah = { ArrowLeft: [-langkah, 0], ArrowRight: [langkah, 0], ArrowUp: [0, -langkah], ArrowDown: [0, langkah] }[e.key];
            if (!arah) return;
            e.preventDefault();
            const b = ruangGerak();
            x = batas(x + arah[0], -b.mx, b.mx);
            y = batas(y + arah[1], -b.my, b.my);
            judul.style.transition = 'transform 0.25s ease-out';
            tampilkan();
        });
    })();
</script>
</body>
</html>
