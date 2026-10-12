<x-guest-layout role="student" title="Masuk Siswa - Ekskul" wide>
    <div class="grid min-h-screen lg:grid-cols-2">
        <!-- Panel peran -->
        <section class="flex flex-col justify-between gap-10 bg-primary p-8 text-on-primary sm:p-12">
            <a href="/" class="flex items-center gap-3">
                <span class="flex size-10 items-center justify-center rounded-lg bg-on-primary text-primary">
                    <x-icon name="graduation-cap" class="size-[22px]" />
                </span>
                <span class="text-title-lg font-bold">Ekskul</span>
            </a>

            <div class="max-w-md">
                <p class="text-label-md uppercase tracking-wide">Portal Siswa</p>
                <h1 class="mt-3 text-headline-lg">Kegiatan, absensi, dan nilai ekskulmu di satu tempat.</h1>
                <ul class="mt-8 space-y-4 text-body-md">
                    <li class="flex gap-3">
                        <x-icon name="calendar-days" class="mt-0.5 size-[20px] shrink-0" />
                        <span>Lihat jadwal ekskul dan daftar kegiatan yang kamu ikuti.</span>
                    </li>
                    <li class="flex gap-3">
                        <x-icon name="qr-code" class="mt-0.5 size-[20px] shrink-0" />
                        <span>Catat kehadiran dengan memindai QR dari pembina atau mengirim selfie.</span>
                    </li>
                    <li class="flex gap-3">
                        <x-icon name="star" class="mt-0.5 size-[20px] shrink-0" />
                        <span>Pantau status pendaftaran dan penilaian dari pembina.</span>
                    </li>
                </ul>
            </div>

            <p class="text-body-sm">Sistem presensi dan manajemen ekstrakurikuler sekolah.</p>
        </section>

        <!-- Form -->
        <section class="flex items-center justify-center bg-surface p-6 sm:p-12">
            <div class="w-full max-w-sm">
                <h2 class="text-headline-md text-on-surface">Masuk sebagai siswa</h2>
                <p class="mt-2 text-body-md text-secondary">Gunakan email yang sudah terdaftar di sekolahmu.</p>

                @if ($errors->any())
                    <div class="mt-6 rounded-lg border border-error/20 bg-error-container px-4 py-3 text-body-md text-on-error-container">
                        <p class="font-semibold">Login gagal</p>
                        <ul class="mt-1 list-disc pl-5 text-body-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form id="loginForm" method="POST" action="{{ route('login') }}" class="mt-8 space-y-5">
                    @csrf

                    <div>
                        <label for="email" class="text-label-md text-on-surface-variant">Alamat email</label>
                        <div class="relative mt-1.5">
                            <x-icon name="mail" class="pointer-events-none absolute left-3 top-1/2 size-[18px] -translate-y-1/2 text-outline" />
                            <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                                   placeholder="nama@sekolah.sch.id"
                                   class="w-full rounded-lg border border-outline-variant bg-surface py-2.5 pl-10 pr-3 text-body-md text-on-surface placeholder:text-outline focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
                        </div>
                    </div>

                    <div>
                        <div class="flex items-center justify-between">
                            <label for="password" class="text-label-md text-on-surface-variant">Kata sandi</label>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-label-md text-primary-container hover:underline">Lupa kata sandi?</a>
                            @endif
                        </div>
                        <div class="relative mt-1.5">
                            <x-icon name="lock" class="pointer-events-none absolute left-3 top-1/2 size-[18px] -translate-y-1/2 text-outline" />
                            <input id="password" name="password" type="password" required autocomplete="current-password" placeholder="••••••••"
                                   class="w-full rounded-lg border border-outline-variant bg-surface py-2.5 pl-10 pr-11 text-body-md text-on-surface placeholder:text-outline focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
                            <button type="button" id="togglePassword" aria-label="Tampilkan kata sandi"
                                    class="absolute right-2 top-1/2 flex size-8 -translate-y-1/2 items-center justify-center rounded-md text-secondary transition-colors hover:bg-surface-container hover:text-on-surface">
                                <span data-eye-open><x-icon name="eye" class="size-[18px]" /></span>
                                <span data-eye-closed class="hidden"><x-icon name="eye-off" class="size-[18px]" /></span>
                            </button>
                        </div>
                    </div>

                    <label class="flex items-center gap-2 text-body-md text-on-surface-variant">
                        <input type="checkbox" name="remember" class="size-4 rounded border-outline text-primary-container focus:ring-primary/30">
                        <span>Ingat saya</span>
                    </label>

                    <button type="submit"
                            class="flex w-full items-center justify-center gap-2 rounded-lg bg-primary-container px-4 py-3 text-label-lg text-on-primary-container transition-opacity hover:opacity-90">
                        <span data-submit-label>Masuk</span>
                        <x-icon name="arrow-right" class="size-[18px]" />
                        <x-icon name="loader-circle" data-submit-spinner class="hidden size-[18px] animate-spin" />
                    </button>
                </form>

                <div class="mt-8 space-y-4 border-t border-outline-variant pt-6 text-center text-body-md text-secondary">
                    <p>Belum punya akun? <a href="{{ route('register') }}" class="font-semibold text-primary-container hover:underline">Daftar sebagai siswa</a></p>
                    <a href="{{ route('login.guru') }}" class="inline-flex items-center gap-2 font-semibold text-on-surface transition-colors hover:text-primary-container">
                        Masuk sebagai guru
                        <x-icon name="arrow-right" class="size-[16px]" />
                    </a>
                </div>
            </div>
        </section>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const form = document.getElementById('loginForm');
                const password = document.getElementById('password');
                const toggle = document.getElementById('togglePassword');

                toggle.addEventListener('click', () => {
                    const show = password.type === 'password';
                    password.type = show ? 'text' : 'password';
                    toggle.setAttribute('aria-label', show ? 'Sembunyikan kata sandi' : 'Tampilkan kata sandi');
                    toggle.querySelector('[data-eye-open]').classList.toggle('hidden', show);
                    toggle.querySelector('[data-eye-closed]').classList.toggle('hidden', !show);
                });

                form.addEventListener('submit', () => {
                    form.querySelector('[data-submit-spinner]').classList.remove('hidden');
                    form.querySelector('[data-submit-label]').textContent = 'Memproses...';
                });
            });
        </script>
    @endpush
</x-guest-layout>
