@php
    $field = 'w-full rounded-2xl border border-outline-variant bg-surface-container-low px-4 py-3.5 text-body-md text-on-surface placeholder:text-outline transition-colors focus:border-primary-container focus:bg-surface focus:outline-none focus:ring-2 focus:ring-primary/30';
    $etiket = 'mb-2 block text-label-md text-on-surface-variant';
    $judulLangkah = ['Akun Pengguna', 'Data Ekskul'];

    // Langkah yang memuat kolom bermasalah, supaya form terbuka di sana setelah server menolak data.
    $grupLangkah = [
        1 => ['name', 'email', 'whatsapp', 'password', 'password_confirmation'],
        2 => ['school_name', 'ekskul_name', 'schedule'],
    ];
    $langkahAwal = 1;
    foreach ($grupLangkah as $nomor => $kolom) {
        if (collect($kolom)->contains(fn ($k) => $errors->has($k))) {
            $langkahAwal = $nomor;
            break;
        }
    }
@endphp

<x-guest-layout role="teacher" title="Daftar Guru - Ekskul" wide>
    <div class="flex min-h-screen items-center justify-center bg-surface p-4 sm:p-8">
        <main class="w-full max-w-sm overflow-hidden rounded-[2.5rem] bg-surface shadow-2xl">
            <x-auth-hero
                role="guru"
                title="Daftar sebagai guru"
                subtitle="Dua langkah singkat untuk mulai mengelola ekskul."
                :alt-href="route('login.guru')"
                alt-label="Masuk" />

            <section class="relative -mt-10 rounded-t-[2.5rem] bg-surface px-6 pb-10 pt-8 sm:px-10">
                <!-- Progres langkah -->
                <div class="flex items-center justify-between gap-4">
                    <p class="text-label-md text-secondary">Langkah <span id="stepNumber">1</span> dari {{ count($judulLangkah) }}</p>
                    <p id="stepTitle" class="text-title-sm text-on-surface">{{ $judulLangkah[0] }}</p>
                </div>
                <div class="mt-3 grid grid-cols-2 gap-2" aria-hidden="true">
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

                <form method="POST" action="{{ route('register.guru') }}" id="regForm"
                      data-titles="{{ json_encode($judulLangkah) }}" data-start="{{ $langkahAwal }}" class="mt-8">
                    @csrf

                    <!-- Langkah 1: akun -->
                    <div class="step-content space-y-5" data-step="1">
                        <div>
                            <label for="reg_name" class="{{ $etiket }}">Nama lengkap</label>
                            <input id="reg_name" name="name" type="text" value="{{ old('name') }}" required placeholder="Lengkap beserta gelar" class="{{ $field }}">
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

                    <!-- Langkah 2: data ekskul -->
                    <div class="step-content hidden space-y-5" data-step="2">
                        <div>
                            <label for="reg_school_name" class="{{ $etiket }}">Nama sekolah</label>
                            <input id="reg_school_name" name="school_name" type="text" value="{{ old('school_name') }}" placeholder="Misal: SMAN 1 Jakarta" class="{{ $field }}">
                        </div>
                        <div class="space-y-5">
                            <div>
                                <label for="reg_ekskul_name" class="{{ $etiket }}">Nama ekskul</label>
                                <input id="reg_ekskul_name" name="ekskul_name" type="text" value="{{ old('ekskul_name') }}" placeholder="Misal: Robotik" class="{{ $field }}">
                            </div>
                            <div>
                                <label for="reg_schedule" class="{{ $etiket }}">Jadwal ekskul</label>
                                <input id="reg_schedule" name="schedule" type="text" value="{{ old('schedule') }}" placeholder="Misal: Jumat, 14:00" class="{{ $field }}">
                            </div>
                        </div>
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
                    <a href="{{ route('login.guru') }}" class="font-semibold text-primary-container hover:underline">Masuk sebagai guru</a>
                </p>
            </section>
        </main>
    </div>

    @push('scripts')
        @include('auth.partials.register-steps')
    @endpush
</x-guest-layout>
