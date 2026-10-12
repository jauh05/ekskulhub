<x-guest-layout role="teacher" wide>
    <x-slot name="title">Registrasi Guru - Ekskul</x-slot>

    <div class="grid min-h-screen lg:grid-cols-2">
        <!-- Panel peran -->
        <section class="flex flex-col justify-between gap-10 bg-primary p-8 text-on-primary sm:p-12">
            <a href="{{ url('/') }}" class="flex items-center gap-3 self-start">
                <span class="flex size-10 items-center justify-center rounded-lg bg-on-primary text-primary">
                    <x-icon name="graduation-cap" class="size-[22px]" />
                </span>
                <span class="text-title-lg font-bold">Ekskul</span>
            </a>

            <div class="max-w-md">
                <p class="text-label-md uppercase tracking-wide">Registrasi Guru</p>
                <h1 class="mt-3 text-headline-lg-mobile sm:text-headline-lg">Kelola Ekstrakurikuler dengan Mudah</h1>
                <p class="mt-4 text-body-lg">Bergabunglah sebagai pembina ekstrakurikuler sekolah dan kelola presensi serta penilaian siswa secara efisien.</p>

                <div class="mt-8">
                    <p class="text-label-md uppercase tracking-wide">Siapkan data berikut</p>
                    <ul class="mt-3 space-y-3 text-body-md">
                        <li class="flex gap-3">
                            <x-icon name="check" class="mt-0.5 size-[18px] shrink-0" />
                            <span>Nama lengkap dan email aktif</span>
                        </li>
                        <li class="flex gap-3">
                            <x-icon name="check" class="mt-0.5 size-[18px] shrink-0" />
                            <span>Nomor WhatsApp</span>
                        </li>
                        <li class="flex gap-3">
                            <x-icon name="check" class="mt-0.5 size-[18px] shrink-0" />
                            <span>Kata sandi untuk akun</span>
                        </li>
                        <li class="flex gap-3">
                            <x-icon name="check" class="mt-0.5 size-[18px] shrink-0" />
                            <span>Nama sekolah, nama ekskul, dan jadwal ekskul</span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="space-y-3 text-body-md">
                <p>Sudah punya akun?</p>
                <div class="flex flex-wrap gap-x-5 gap-y-2 font-semibold">
                    <a href="{{ route('login.guru') }}" class="underline underline-offset-4">Masuk sebagai guru</a>
                    <a href="{{ route('login') }}" class="underline underline-offset-4">Masuk sebagai siswa</a>
                </div>
            </div>
        </section>

        <!-- Form -->
        <section class="flex items-start justify-center bg-background p-6 sm:p-12 lg:items-center">
            <div class="w-full max-w-2xl" id="registration-container">
                <div class="rounded-xl border border-outline-variant bg-surface p-6 shadow-sm sm:p-8">
                    <!-- Indikator langkah -->
                    <div class="grid grid-cols-2 gap-x-4 gap-y-4 border-b border-outline-variant pb-6">
                        <div class="step-indicator flex items-center gap-3" data-step="1">
                            <div class="step-dot flex size-8 shrink-0 items-center justify-center rounded-full border-2 border-primary bg-primary text-on-primary transition-all duration-300">
                                <x-icon name="user-plus" class="size-[18px]" />
                            </div>
                            <div class="flex min-w-0 flex-col">
                                <span class="step-label text-label-xs font-bold uppercase text-secondary">Langkah 1</span>
                                <span class="text-title-sm text-on-surface">Akun Pengguna</span>
                            </div>
                        </div>
                        <div class="step-indicator flex items-center gap-3 opacity-60" data-step="2">
                            <div class="step-dot flex size-8 shrink-0 items-center justify-center rounded-full border-2 border-outline-variant bg-surface text-on-surface-variant transition-all duration-300">
                                <x-icon name="id-card" class="size-[18px]" />
                            </div>
                            <div class="flex min-w-0 flex-col">
                                <span class="step-label text-label-xs uppercase text-secondary">Langkah 2</span>
                                <span class="text-title-sm text-on-surface">Data Diri</span>
                            </div>
                        </div>
                    </div>

                    @if ($errors->any())
                        <div class="mt-6 rounded-lg bg-error-container p-4 text-body-md text-on-error-container">
                            <p class="mb-2 font-bold">Registrasi Gagal:</p>
                            <ul class="list-inside list-disc text-body-md">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('register.guru') }}" id="regForm" class="mt-8">
                        @csrf
                        <!-- STEP 1: AKUN -->
                        <div class="step-content space-y-5" id="step-1-content">
                            <div class="grid gap-5 xl:grid-cols-2">
                                <div class="flex flex-col gap-1.5">
                                    <label for="reg_name" class="text-label-md text-on-surface-variant">Nama Lengkap</label>
                                    <input id="reg_name" class="w-full rounded-lg border border-outline-variant bg-surface px-3 py-2.5 text-body-md text-on-surface placeholder:text-outline focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20" name="name" placeholder="Masukkan nama lengkap beserta gelar" type="text" value="{{ old('name') }}" required/>
                                </div>
                                <div class="flex flex-col gap-1.5">
                                    <label for="reg_email" class="text-label-md text-on-surface-variant">Email Aktif</label>
                                    <input id="reg_email" class="w-full rounded-lg border border-outline-variant bg-surface px-3 py-2.5 text-body-md text-on-surface placeholder:text-outline focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20" name="email" placeholder="contoh@sekolah.sch.id" type="email" value="{{ old('email') }}" required/>
                                </div>
                            </div>
                            <div class="grid gap-5 xl:grid-cols-2">
                                <div class="flex flex-col gap-1.5">
                                    <label for="reg_whatsapp" class="text-label-md text-on-surface-variant">Nomor WhatsApp</label>
                                    <input id="reg_whatsapp" class="w-full rounded-lg border border-outline-variant bg-surface px-3 py-2.5 text-body-md text-on-surface placeholder:text-outline focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20" name="whatsapp" placeholder="0812..." type="tel"/>
                                </div>
                            </div>
                            <div class="grid gap-5 xl:grid-cols-2">
                                <div class="flex flex-col gap-1.5">
                                    <label for="password" class="text-label-md text-on-surface-variant">Kata Sandi</label>
                                    <input id="password" class="w-full rounded-lg border border-outline-variant bg-surface px-3 py-2.5 text-body-md text-on-surface placeholder:text-outline focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20" name="password" type="password" required/>
                                    <div class="mt-1 h-1 w-full overflow-hidden rounded-full bg-surface-container-high">
                                        <div class="h-full w-1/3 bg-error transition-all duration-500" id="strength-bar"></div>
                                    </div>
                                    <span class="text-body-xs text-on-surface-variant" id="strength-text">Kekuatan: Lemah</span>
                                </div>
                                <div class="flex flex-col gap-1.5">
                                    <label for="reg_password_confirmation" class="text-label-md text-on-surface-variant">Konfirmasi Kata Sandi</label>
                                    <input id="reg_password_confirmation" class="w-full rounded-lg border border-outline-variant bg-surface px-3 py-2.5 text-body-md text-on-surface placeholder:text-outline focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20" name="password_confirmation" type="password" required/>
                                </div>
                            </div>
                        </div>

                        <!-- STEP 2: DATA DIRI -->
                        <div class="step-content hidden space-y-5" id="step-2-content">
                            <div class="grid gap-5 xl:grid-cols-2">
                                <div class="flex flex-col gap-1.5 xl:col-span-2">
                                    <label for="reg_school_name" class="text-label-md text-on-surface-variant">Nama Sekolah</label>
                                    <input id="reg_school_name" class="w-full rounded-lg border border-outline-variant bg-surface px-3 py-2.5 text-body-md text-on-surface placeholder:text-outline focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20" name="school_name" placeholder="Misal: SMAN 1 Jakarta" type="text"/>
                                </div>
                                <div class="flex flex-col gap-1.5">
                                    <label for="reg_ekskul_name" class="text-label-md text-on-surface-variant">Nama Ekskul</label>
                                    <input id="reg_ekskul_name" class="w-full rounded-lg border border-outline-variant bg-surface px-3 py-2.5 text-body-md text-on-surface placeholder:text-outline focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20" name="ekskul_name" placeholder="Misal: Robotik" type="text"/>
                                </div>
                                <div class="flex flex-col gap-1.5">
                                    <label for="reg_schedule" class="text-label-md text-on-surface-variant">Jadwal Ekskul</label>
                                    <input id="reg_schedule" class="w-full rounded-lg border border-outline-variant bg-surface px-3 py-2.5 text-body-md text-on-surface placeholder:text-outline focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20" name="schedule" placeholder="Misal: Jumat, 14:00" type="text"/>
                                </div>
                            </div>
                        </div>
                    </form>

                    <!-- Tombol navigasi -->
                    <div class="mt-8 flex items-center justify-between gap-3 border-t border-outline-variant pt-6">
                        <button class="hidden inline-flex items-center gap-2 rounded-lg border border-outline-variant bg-surface px-4 py-2.5 text-label-lg text-on-surface transition-colors hover:bg-surface-container" id="prevBtn" type="button">
                            <x-icon name="arrow-left" class="size-[18px]" />
                            Kembali
                        </button>
                        <div class="flex-grow"></div>
                        <button class="inline-flex items-center justify-center gap-2 rounded-lg bg-primary-container px-5 py-2.5 text-label-lg text-on-primary-container transition-opacity hover:opacity-90" id="nextBtn" type="button">
                            Lanjut
                            <x-icon name="arrow-right" class="size-[18px]" />
                        </button>
                    </div>
                </div>

            </div>
        </section>
    </div>

    <script>
        // Ikon Lucide inline untuk string JS (sumber: resources/svg/lucide)
        const lucideSvg = (inner) => `<svg class="lucide size-[18px]" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">${inner}</svg>`;
        const STEP_ICONS = [
            lucideSvg('<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" x2="19" y1="8" y2="14"/><line x1="22" x2="16" y1="11" y2="11"/>'),
            lucideSvg('<path d="M13 19a4 4 0 00-8 0"/><path d="M16 10h2"/><path d="M16 14h2"/><circle cx="9" cy="12" r="3"/><rect x="2" y="5" width="20" height="14" rx="2"/>'),
        ];
        const ICON_CHECK = lucideSvg('<path d="M20 6 9 17l-5-5"/>');
        const ICON_SEND = lucideSvg('<path d="M14.536 21.686a.5.5 0 0 0 .937-.024l6.5-19a.496.496 0 0 0-.635-.635l-19 6.5a.5.5 0 0 0-.024.937l7.93 3.18a2 2 0 0 1 1.112 1.11z"/><path d="m21.854 2.147-10.94 10.939"/>');
        const ICON_ARROW_RIGHT = lucideSvg('<path d="M5 12h14"/><path d="m12 5 7 7-7 7"/>');

        let currentStep = 1;
        const totalSteps = 2;
        const form = document.getElementById('regForm');
        const nextBtn = document.getElementById('nextBtn');
        const prevBtn = document.getElementById('prevBtn');
        const indicators = document.querySelectorAll('.step-indicator');
        const contents = document.querySelectorAll('.step-content');

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
                const labelSm = ind.querySelector('.step-label');

                if (step === currentStep) {
                    ind.classList.remove('opacity-60');
                    dot.classList.remove('bg-surface', 'text-on-surface-variant', 'border-outline-variant', 'bg-tertiary', 'text-on-tertiary', 'border-tertiary');
                    dot.classList.add('bg-primary', 'text-on-primary', 'border-primary');
                    dot.innerHTML = getInitialIcon(step);
                    if (labelSm) labelSm.classList.add('font-bold');
                } else if (step < currentStep) {
                    ind.classList.remove('opacity-60');
                    dot.classList.remove('bg-primary', 'text-on-primary', 'border-primary', 'bg-surface', 'text-on-surface-variant', 'border-outline-variant');
                    dot.classList.add('bg-tertiary', 'text-on-tertiary', 'border-tertiary');
                    dot.innerHTML = ICON_CHECK;
                    if (labelSm) labelSm.classList.remove('font-bold');
                } else {
                    ind.classList.add('opacity-60');
                    dot.classList.remove('bg-primary', 'bg-tertiary', 'text-on-primary', 'text-on-tertiary', 'border-primary', 'border-tertiary');
                    dot.classList.add('bg-surface', 'text-on-surface-variant', 'border-outline-variant');
                    dot.innerHTML = getInitialIcon(step);
                    if (labelSm) labelSm.classList.remove('font-bold');
                }
            });

            // Update Buttons
            prevBtn.classList.toggle('hidden', currentStep === 1);
            if (currentStep === totalSteps) {
                nextBtn.innerHTML = 'Kirim Pendaftaran ' + ICON_SEND;
            } else {
                nextBtn.innerHTML = 'Lanjut ' + ICON_ARROW_RIGHT;
            }
        }

        function getInitialIcon(step) {
            return STEP_ICONS[step - 1];
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
                strengthBar.className = 'h-full bg-warning transition-all duration-500';
                strengthText.innerText = 'Kekuatan: Sedang';
            } else {
                strengthBar.style.width = '100%';
                strengthBar.className = 'h-full bg-primary transition-all duration-500';
                strengthText.innerText = 'Kekuatan: Kuat';
            }
        });
    </script>
</x-guest-layout>
