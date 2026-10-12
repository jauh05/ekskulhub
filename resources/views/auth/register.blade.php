<x-guest-layout role="student" wide>
    <x-slot name="title">Registrasi Siswa - Ekskul</x-slot>

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
                <p class="text-label-md uppercase tracking-wide">Registrasi Siswa</p>
                <h1 class="mt-3 text-headline-lg-mobile sm:text-headline-lg">Mulai Perjalanan Bakatmu di Sini</h1>
                <p class="mt-4 text-body-lg">Bergabunglah dengan komunitas ekskul sekolah dan kembangkan potensi terbaikmu melalui berbagai kegiatan pilihan.</p>

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
                            <span>Nama sekolah, kelas, dan jenis kelamin</span>
                        </li>
                        <li class="flex gap-3">
                            <x-icon name="check" class="mt-0.5 size-[18px] shrink-0" />
                            <span>Kode kelas dari pembina (opsional)</span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="space-y-3 text-body-md">
                <p>Sudah punya akun?</p>
                <div class="flex flex-wrap gap-x-5 gap-y-2 font-semibold">
                    <a href="{{ route('login') }}" class="underline underline-offset-4">Masuk sebagai siswa</a>
                    <a href="{{ route('login.guru') }}" class="underline underline-offset-4">Masuk sebagai guru</a>
                </div>
            </div>
        </section>

        <!-- Form -->
        <section class="flex items-start justify-center bg-background p-6 sm:p-12 lg:items-center">
            <div class="w-full max-w-2xl" id="registration-container">
                <div class="rounded-xl border border-outline-variant bg-surface p-6 shadow-sm sm:p-8">
                    <!-- Indikator langkah -->
                    <div class="grid grid-cols-2 gap-x-4 gap-y-4 border-b border-outline-variant pb-6 sm:grid-cols-4">
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
                        <div class="step-indicator flex items-center gap-3 opacity-60" data-step="3">
                            <div class="step-dot flex size-8 shrink-0 items-center justify-center rounded-full border-2 border-outline-variant bg-surface text-on-surface-variant transition-all duration-300">
                                <x-icon name="trophy" class="size-[18px]" />
                            </div>
                            <div class="flex min-w-0 flex-col">
                                <span class="step-label text-label-xs uppercase text-secondary">Langkah 3</span>
                                <span class="text-title-sm text-on-surface">Kode Kelas</span>
                            </div>
                        </div>
                        <div class="step-indicator flex items-center gap-3 opacity-60" data-step="4">
                            <div class="step-dot flex size-8 shrink-0 items-center justify-center rounded-full border-2 border-outline-variant bg-surface text-on-surface-variant transition-all duration-300">
                                <x-icon name="badge-check" class="size-[18px]" />
                            </div>
                            <div class="flex min-w-0 flex-col">
                                <span class="step-label text-label-xs uppercase text-secondary">Langkah 4</span>
                                <span class="text-title-sm text-on-surface">Konfirmasi</span>
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

                    <form method="POST" action="{{ route('register') }}" id="regForm" class="mt-8">
                        @csrf
                        <!-- STEP 1: AKUN -->
                        <div class="step-content space-y-5" id="step-1-content">
                            <div class="grid gap-5 xl:grid-cols-2">
                                <div class="flex flex-col gap-1.5">
                                    <label for="reg_name" class="text-label-md text-on-surface-variant">Nama Lengkap</label>
                                    <input id="reg_name" class="w-full rounded-lg border border-outline-variant bg-surface px-3 py-2.5 text-body-md text-on-surface placeholder:text-outline focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20" name="name" placeholder="Masukkan nama sesuai raport" type="text" value="{{ old('name') }}" required/>
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
                                <div class="flex flex-col gap-1.5">
                                    <label for="reg_school_name" class="text-label-md text-on-surface-variant">Nama Sekolah</label>
                                    <select id="reg_school_name" class="w-full appearance-none rounded-lg border border-outline-variant bg-surface px-3 py-2.5 text-body-md text-on-surface focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20" name="school_name" required>
                                        <option value="">Pilih Nama Sekolah</option>
                                        @foreach($schools as $school)
                                            <option value="{{ $school }}">{{ $school }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="flex flex-col gap-1.5">
                                    <label for="reg_class" class="text-label-md text-on-surface-variant">Kelas</label>
                                    <select id="reg_class" class="w-full appearance-none rounded-lg border border-outline-variant bg-surface px-3 py-2.5 text-body-md text-on-surface focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20" name="class" required>
                                        <option value="">Pilih Kelas</option>
                                        @foreach(['7','8','9'] as $grade)
                                            @foreach(['A','B','C','D','E','F'] as $letter)
                                                <option value="{{ $grade }} {{ $letter }}">Kelas {{ $grade }} {{ $letter }}</option>
                                            @endforeach
                                        @endforeach
                                    </select>
                                </div>
                                <div class="flex flex-col gap-1.5">
                                    <label for="reg_gender" class="text-label-md text-on-surface-variant">Jenis Kelamin</label>
                                    <select id="reg_gender" class="w-full appearance-none rounded-lg border border-outline-variant bg-surface px-3 py-2.5 text-body-md text-on-surface focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20" name="gender" required>
                                        <option value="">Pilih Jenis Kelamin</option>
                                        <option value="male">Laki-Laki</option>
                                        <option value="female">Perempuan</option>
                                    </select>
                                </div>
                                <div class="flex flex-col gap-1.5">
                                    <label for="reg_dob" class="text-label-md text-on-surface-variant">Tanggal Lahir</label>
                                    <input id="reg_dob" class="w-full rounded-lg border border-outline-variant bg-surface px-3 py-2.5 text-body-md text-on-surface focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20" name="dob" type="date"/>
                                </div>
                            </div>
                        </div>

                        <!-- STEP 3: KODE KELAS -->
                        <div class="step-content hidden space-y-5" id="step-3-content">
                            <div class="rounded-xl border border-outline-variant bg-surface-container-low p-5">
                                <label for="class_code" class="mb-1 block text-label-md text-on-surface">Kode Kelas (Opsional)</label>
                                <p class="mb-4 text-body-xs text-on-surface-variant">Masukkan kode kelas dari pembina jika ada, atau lewati dan isi nanti.</p>
                                <input id="class_code" class="w-full rounded-lg border border-outline-variant bg-surface px-3 py-2.5 font-mono text-body-md uppercase tracking-widest text-on-surface placeholder:text-outline focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20" name="class_code" placeholder="MISAL: ROB-2024" type="text"/>
                            </div>
                            <div class="flex justify-center pt-2">
                                <button type="button" onclick="document.getElementById('nextBtn').click()" class="inline-flex items-center justify-center rounded-lg border border-outline-variant bg-surface px-4 py-2.5 text-label-lg text-on-surface transition-colors hover:bg-surface-container">
                                    Isi Nanti
                                </button>
                            </div>
                        </div>

                        <!-- STEP 4: KONFIRMASI -->
                        <div class="step-content hidden space-y-5" id="step-4-content">
                            <div class="rounded-xl border border-outline-variant bg-surface-container-low p-5">
                                <div class="mb-5 flex items-center gap-4">
                                    <div class="flex size-16 shrink-0 items-center justify-center rounded-full bg-primary-container text-headline-md uppercase text-on-primary-container" id="confirm-avatar">
                                        AN
                                    </div>
                                    <div class="min-w-0">
                                        <h3 class="text-title-md text-on-surface" id="confirm-name">Andi Pratama</h3>
                                        <p class="text-label-sm text-on-surface-variant" id="confirm-subinfo">Sekolah: - • Kelas -</p>
                                    </div>
                                </div>
                                <div class="grid gap-4 border-t border-outline-variant pt-5 sm:grid-cols-2">
                                    <div class="space-y-1">
                                        <p class="text-label-xs uppercase text-secondary">Email</p>
                                        <p class="text-body-md text-on-surface" id="confirm-email">andi.pratama@email.com</p>
                                    </div>
                                    <div class="space-y-1">
                                        <p class="text-label-xs uppercase text-secondary">WhatsApp</p>
                                        <p class="text-body-md text-on-surface" id="confirm-whatsapp">0812-3456-7890</p>
                                    </div>
                                </div>
                            </div>
                            <label class="group flex cursor-pointer items-start gap-3">
                                <input class="mt-0.5 size-5 rounded border-outline-variant text-primary focus:ring-primary" type="checkbox"/>
                                <span class="text-body-md text-on-surface-variant transition-colors group-hover:text-on-surface">Saya menyatakan bahwa data yang saya isi sudah benar dan bersedia mengikuti aturan yang berlaku di kegiatan ekstrakurikuler yang saya pilih.</span>
                            </label>
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
            lucideSvg('<path d="M10 14.66V17a1 1 0 0 1-1 1 2 2 0 0 0-2 2v2"/><path d="M14 14.66V17a1 1 0 0 0 1 1 2 2 0 0 1 2 2v2"/><path d="M17.916 10H19.5A2.5 2.5 0 0 0 22 7.5V5a1 1 0 0 0-1-1h-3"/><path d="M4 22h16"/><path d="M6 9a6 6 0 0 0 12 0V3a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1z"/><path d="M6.084 10H4.5A2.5 2.5 0 0 1 2 7.5V5a1 1 0 0 1 1-1h3"/>'),
            lucideSvg('<path d="M3.85 8.62a4 4 0 0 1 4.78-4.77 4 4 0 0 1 6.74 0 4 4 0 0 1 4.78 4.78 4 4 0 0 1 0 6.74 4 4 0 0 1-4.77 4.78 4 4 0 0 1-6.75 0 4 4 0 0 1-4.78-4.77 4 4 0 0 1 0-6.76Z"/><path d="m16 9-5.5 5.5L8 12"/>'),
        ];
        const ICON_CHECK = lucideSvg('<path d="M20 6 9 17l-5-5"/>');
        const ICON_SEND = lucideSvg('<path d="M14.536 21.686a.5.5 0 0 0 .937-.024l6.5-19a.496.496 0 0 0-.635-.635l-19 6.5a.5.5 0 0 0-.024.937l7.93 3.18a2 2 0 0 1 1.112 1.11z"/><path d="m21.854 2.147-10.94 10.939"/>');
        const ICON_ARROW_RIGHT = lucideSvg('<path d="M5 12h14"/><path d="m12 5 7 7-7 7"/>');

        let currentStep = 1;
        const totalSteps = 4;
        const form = document.getElementById('regForm');
        const nextBtn = document.getElementById('nextBtn');
        const prevBtn = document.getElementById('prevBtn');
        const indicators = document.querySelectorAll('.step-indicator');
        const contents = document.querySelectorAll('.step-content');

        function updateUI() {
            if (currentStep === 4) {
                const name = document.querySelector('input[name="name"]').value || '-';
                const schoolName = document.querySelector('select[name="school_name"]').value || '-';
                const className = document.querySelector('select[name="class"]').value || '-';
                const email = document.querySelector('input[name="email"]').value || '-';
                const whatsapp = document.querySelector('input[name="whatsapp"]').value || '-';

                const initials = name !== '-' ? name.split(' ').map(n => n[0]).slice(0, 2).join('').toUpperCase() : 'AN';

                document.getElementById('confirm-avatar').textContent = initials;
                document.getElementById('confirm-name').textContent = name;
                document.getElementById('confirm-subinfo').textContent = 'Sekolah: ' + schoolName + ' • Kelas ' + className;
                document.getElementById('confirm-email').textContent = email;
                document.getElementById('confirm-whatsapp').textContent = whatsapp;
            }

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
