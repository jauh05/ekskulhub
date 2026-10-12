@php
    $field = 'w-full rounded-2xl border border-outline-variant bg-surface-container-low px-4 py-3.5 text-body-md text-on-surface placeholder:text-outline transition-colors focus:border-primary-container focus:bg-surface focus:outline-none focus:ring-2 focus:ring-primary/30';
    $pilihan = 'w-full appearance-none rounded-2xl border border-outline-variant bg-surface-container-low py-3.5 pl-4 pr-11 text-body-md text-on-surface transition-colors focus:border-primary-container focus:bg-surface focus:outline-none focus:ring-2 focus:ring-primary/30';
    $etiket = 'mb-2 block text-label-md text-on-surface-variant';
    $judulLangkah = ['Akun Pengguna', 'Data Diri', 'Kode Kelas', 'Konfirmasi'];

    // Langkah yang memuat kolom bermasalah, supaya form terbuka di sana setelah server menolak data.
    $grupLangkah = [
        1 => ['name', 'email', 'whatsapp', 'password', 'password_confirmation'],
        2 => ['school_name', 'class', 'gender', 'dob'],
        3 => ['class_code'],
    ];
    $langkahAwal = 1;
    foreach ($grupLangkah as $nomor => $kolom) {
        if (collect($kolom)->contains(fn ($k) => $errors->has($k))) {
            $langkahAwal = $nomor;
            break;
        }
    }
@endphp

<x-guest-layout role="student" title="Daftar Siswa - Ekskul" wide>
    <div class="flex min-h-screen items-center justify-center bg-paper p-4 sm:p-8">
        <main class="w-full max-w-sm overflow-hidden rounded-[2.5rem] bg-surface shadow-xl">
            <x-auth-hero
                role="siswa"
                title="Daftar sebagai siswa"
                subtitle="Empat langkah singkat untuk mulai ikut ekskul."
                :alt-href="route('login')"
                alt-label="Masuk" />

            <section class="relative -mt-10 rounded-t-[2.5rem] bg-surface px-6 pb-10 pt-8 sm:px-10">
                <!-- Progres langkah -->
                <div class="flex items-center justify-between gap-4">
                    <p class="text-label-md text-secondary">Langkah <span id="stepNumber">1</span> dari {{ count($judulLangkah) }}</p>
                    <p id="stepTitle" class="text-title-sm text-on-surface">{{ $judulLangkah[0] }}</p>
                </div>
                <div class="mt-3 grid grid-cols-4 gap-2" aria-hidden="true">
                    @foreach ($judulLangkah as $judul)
                        <span data-step-bar class="h-1.5 rounded-full bg-surface-container-high transition-colors duration-300"></span>
                    @endforeach
                </div>

                @if ($errors->any())
                    <div role="alert" class="mt-6 rounded-2xl border border-error/20 bg-error-container px-4 py-3 text-body-md text-on-error-container">
                        <p class="font-semibold">Registrasi gagal</p>
                        <ul class="mt-1 list-disc pl-5 text-body-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('register') }}" id="regForm"
                      data-titles="{{ json_encode($judulLangkah) }}" data-start="{{ $langkahAwal }}" class="mt-8">
                    @csrf

                    <!-- Langkah 1: akun -->
                    <div class="step-content space-y-5" data-step="1">
                        <div>
                            <label for="reg_name" class="{{ $etiket }}">Nama lengkap</label>
                            <input id="reg_name" name="name" type="text" value="{{ old('name') }}" required placeholder="Sesuai raport" class="{{ $field }}">
                        </div>
                        <div class="space-y-5">
                            <div>
                                <label for="reg_email" class="{{ $etiket }}">Email aktif</label>
                                <input id="reg_email" name="email" type="email" value="{{ old('email') }}" required placeholder="contoh@sekolah.sch.id" class="{{ $field }}">
                            </div>
                            <div>
                                <label for="reg_whatsapp" class="{{ $etiket }}">Nomor WhatsApp</label>
                                <input id="reg_whatsapp" name="whatsapp" type="tel" value="{{ old('whatsapp') }}" placeholder="0812..." class="{{ $field }}">
                            </div>
                        </div>
                        <div class="space-y-5">
                            <div>
                                <label for="password" class="{{ $etiket }}">Kata sandi</label>
                                <input id="password" name="password" type="password" required autocomplete="new-password" class="{{ $field }}">
                                <div class="mt-2 h-1.5 w-full overflow-hidden rounded-full bg-surface-container-high">
                                    <div id="strength-bar" class="h-full rounded-full transition-all duration-500" style="width: 0%"></div>
                                </div>
                                <p id="strength-text" class="mt-1.5 text-body-xs text-secondary">Minimal 8 karakter</p>
                            </div>
                            <div>
                                <label for="reg_password_confirmation" class="{{ $etiket }}">Konfirmasi kata sandi</label>
                                <input id="reg_password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password" class="{{ $field }}">
                            </div>
                        </div>
                    </div>

                    <!-- Langkah 2: data diri -->
                    <div class="step-content hidden space-y-5" data-step="2">
                        <div>
                            <label for="reg_school_name" class="{{ $etiket }}">Nama sekolah</label>
                            <div class="relative">
                                <select id="reg_school_name" name="school_name" required class="{{ $pilihan }}">
                                    <option value="">Pilih nama sekolah</option>
                                    @foreach ($schools as $school)
                                        <option value="{{ $school }}" @selected(old('school_name') === $school)>{{ $school }}</option>
                                    @endforeach
                                </select>
                                <x-icon name="chevron-down" class="pointer-events-none absolute right-4 top-1/2 size-[18px] -translate-y-1/2 text-secondary" />
                            </div>
                        </div>
                        <div class="space-y-5">
                            <div>
                                <label for="reg_class" class="{{ $etiket }}">Kelas</label>
                                <div class="relative">
                                    <select id="reg_class" name="class" required class="{{ $pilihan }}">
                                        <option value="">Pilih kelas</option>
                                        @foreach (['7', '8', '9'] as $grade)
                                            <optgroup label="Kelas {{ $grade }}">
                                                @foreach (['A', 'B', 'C', 'D', 'E', 'F'] as $letter)
                                                    <option value="{{ $grade }} {{ $letter }}" @selected(old('class') === $grade.' '.$letter)>Kelas {{ $grade }} {{ $letter }}</option>
                                                @endforeach
                                            </optgroup>
                                        @endforeach
                                    </select>
                                    <x-icon name="chevron-down" class="pointer-events-none absolute right-4 top-1/2 size-[18px] -translate-y-1/2 text-secondary" />
                                </div>
                            </div>
                            <div>
                                <label for="reg_gender" class="{{ $etiket }}">Jenis kelamin</label>
                                <div class="relative">
                                    <select id="reg_gender" name="gender" required class="{{ $pilihan }}">
                                        <option value="">Pilih jenis kelamin</option>
                                        <option value="male" @selected(old('gender') === 'male')>Laki-laki</option>
                                        <option value="female" @selected(old('gender') === 'female')>Perempuan</option>
                                    </select>
                                    <x-icon name="chevron-down" class="pointer-events-none absolute right-4 top-1/2 size-[18px] -translate-y-1/2 text-secondary" />
                                </div>
                            </div>
                        </div>
                        <div>
                            <label for="reg_dob" class="{{ $etiket }}">Tanggal lahir</label>
                            <input id="reg_dob" name="dob" type="date" value="{{ old('dob') }}" class="{{ $field }}">
                        </div>
                    </div>

                    <!-- Langkah 3: kode kelas -->
                    <div class="step-content hidden space-y-5" data-step="3">
                        <div class="rounded-2xl bg-surface-container-low p-5">
                            <div class="flex items-start gap-3">
                                <span class="flex size-10 shrink-0 items-center justify-center rounded-xl bg-primary text-on-primary">
                                    <x-icon name="key-round" class="size-[18px]" />
                                </span>
                                <div>
                                    <label for="class_code" class="block text-title-sm text-on-surface">Kode kelas (opsional)</label>
                                    <p class="mt-1 text-body-sm text-secondary">Masukkan kode dari pembina jika ada. Kalau belum punya, lewati dan isi nanti.</p>
                                </div>
                            </div>
                            <input id="class_code" name="class_code" type="text" value="{{ old('class_code') }}" placeholder="Misal: ROB-2024"
                                   class="{{ $field }} mt-4 font-mono uppercase tracking-widest">
                        </div>
                        <div class="flex justify-center">
                            <button type="button" data-skip-step class="text-label-lg text-primary-container underline-offset-4 hover:underline">Lewati, isi nanti</button>
                        </div>
                    </div>

                    <!-- Langkah 4: konfirmasi -->
                    <div class="step-content hidden space-y-5" data-step="4">
                        <div class="rounded-2xl bg-surface-container-low p-5">
                            <div class="flex items-center gap-4">
                                <div id="confirm-avatar" class="flex size-14 shrink-0 items-center justify-center rounded-full bg-primary-container text-title-md text-on-primary-container">AN</div>
                                <div class="min-w-0">
                                    <p id="confirm-name" class="truncate text-title-md text-on-surface">-</p>
                                    <p id="confirm-subinfo" class="text-body-sm text-secondary">-</p>
                                </div>
                            </div>
                            <dl class="mt-5 space-y-4 border-t border-outline-variant pt-5">
                                <div>
                                    <dt class="text-label-xs uppercase text-secondary">Email</dt>
                                    <dd id="confirm-email" class="mt-1 break-all text-body-md text-on-surface">-</dd>
                                </div>
                                <div>
                                    <dt class="text-label-xs uppercase text-secondary">WhatsApp</dt>
                                    <dd id="confirm-whatsapp" class="mt-1 text-body-md text-on-surface">-</dd>
                                </div>
                            </dl>
                        </div>
                        <label class="flex cursor-pointer items-start gap-3 rounded-2xl border border-outline-variant p-4">
                            <input type="checkbox" class="mt-0.5 size-5 rounded border-outline-variant accent-primary-container">
                            <span class="text-body-md text-on-surface-variant">Saya menyatakan bahwa data yang saya isi sudah benar dan bersedia mengikuti aturan kegiatan ekstrakurikuler yang saya pilih.</span>
                        </label>
                    </div>
                </form>

                <!-- Tombol navigasi -->
                <div class="mt-8 flex items-center justify-between gap-3 border-t border-outline-variant pt-6">
                    <button id="prevBtn" type="button"
                            class="invisible inline-flex items-center gap-2 rounded-full border border-outline-variant bg-surface px-5 py-3 text-label-lg text-on-surface transition-colors hover:bg-surface-container">
                        <x-icon name="arrow-left" class="size-[18px]" />
                        Kembali
                    </button>
                    <button id="nextBtn" type="button"
                            class="inline-flex items-center justify-center gap-2 rounded-full bg-primary-container px-6 py-3 text-label-lg text-on-primary-container transition-opacity hover:opacity-90 disabled:opacity-60">
                        <span data-next-label>Lanjut</span>
                        <x-icon name="arrow-right" class="size-[18px]" data-next-icon />
                        <x-icon name="send" class="hidden size-[18px]" data-send-icon />
                    </button>
                </div>

                <p class="mt-6 text-center text-body-md text-secondary">
                    Sudah punya akun?
                    <a href="{{ route('login') }}" class="font-semibold text-primary-container hover:underline">Masuk sebagai siswa</a>
                </p>
            </section>
        </main>
    </div>

    @push('scripts')
        @include('auth.partials.register-steps')
    @endpush
</x-guest-layout>
