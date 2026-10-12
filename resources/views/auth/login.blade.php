@php
    $input = 'w-full rounded-2xl border border-outline-variant bg-surface-container-low py-3.5 pl-11 pr-4 text-body-md text-on-surface placeholder:text-outline transition-colors focus:border-primary-container focus:bg-surface focus:outline-none focus:ring-2 focus:ring-primary/30';
    $inputSandi = 'w-full rounded-2xl border border-outline-variant bg-surface-container-low py-3.5 pl-11 pr-12 text-body-md text-on-surface placeholder:text-outline transition-colors focus:border-primary-container focus:bg-surface focus:outline-none focus:ring-2 focus:ring-primary/30';
@endphp

<x-guest-layout role="student" title="Masuk Siswa - Ekskul" wide>
    <div class="flex min-h-screen items-center justify-center bg-paper p-4 sm:p-8">
        <main class="w-full max-w-sm overflow-hidden rounded-[2.5rem] bg-surface shadow-xl">
            <x-auth-hero
                role="siswa"
                title="Masuk sebagai siswa"
                subtitle="Lihat jadwal, catat absensi, dan pantau nilaimu."
                image="/images/landing/hero-phone.png"
                alt="Ilustrasi layar absensi QR pada ponsel siswa"
                :alt-href="route('register')"
                alt-label="Daftar" />

            <section class="relative -mt-10 rounded-t-[2.5rem] bg-surface px-6 pb-10 pt-8 sm:px-10">
                @if ($errors->any())
                    <div role="alert" class="mb-6 rounded-2xl border border-error/20 bg-error-container px-4 py-3 text-body-md text-on-error-container">
                        <p class="font-semibold">Login gagal</p>
                        <ul class="mt-1 list-disc pl-5 text-body-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form id="loginForm" method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label for="email" class="mb-2 block text-label-md text-on-surface-variant">Alamat email</label>
                        <div class="relative">
                            <x-icon name="mail" class="pointer-events-none absolute left-4 top-1/2 size-[18px] -translate-y-1/2 text-secondary" />
                            <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                                   placeholder="nama@sekolah.sch.id" class="{{ $input }}">
                        </div>
                    </div>

                    <div>
                        <div class="mb-2 flex items-center justify-between">
                            <label for="password" class="text-label-md text-on-surface-variant">Kata sandi</label>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-label-md text-primary-container hover:underline">Lupa kata sandi?</a>
                            @endif
                        </div>
                        <div class="relative">
                            <x-icon name="lock" class="pointer-events-none absolute left-4 top-1/2 size-[18px] -translate-y-1/2 text-secondary" />
                            <input id="password" name="password" type="password" required autocomplete="current-password" placeholder="••••••••" class="{{ $inputSandi }}">
                            <button type="button" id="togglePassword" aria-label="Tampilkan kata sandi"
                                    class="absolute right-2 top-1/2 flex size-9 -translate-y-1/2 items-center justify-center rounded-full text-secondary transition-colors hover:bg-surface-container hover:text-on-surface">
                                <span data-eye-open><x-icon name="eye" class="size-[18px]" /></span>
                                <span data-eye-closed class="hidden"><x-icon name="eye-off" class="size-[18px]" /></span>
                            </button>
                        </div>
                    </div>

                    <label class="flex items-center gap-2.5 text-body-md text-on-surface-variant">
                        <input type="checkbox" name="remember" class="size-4 rounded border-outline accent-primary-container">
                        <span>Ingat saya</span>
                    </label>

                    <button type="submit"
                            class="flex w-full items-center justify-center gap-2 rounded-full bg-primary-container px-5 py-4 text-label-lg text-on-primary-container transition-opacity hover:opacity-90">
                        <span data-submit-label>Masuk</span>
                        <x-icon name="arrow-right" class="size-[18px]" />
                        <x-icon name="loader-circle" data-submit-spinner class="hidden size-[18px] animate-spin" />
                    </button>
                </form>

                <div class="mt-8 space-y-3 text-center text-body-md text-secondary">
                    <p>Belum punya akun? <a href="{{ route('register') }}" class="font-semibold text-primary-container hover:underline">Daftar sebagai siswa</a></p>
                    <a href="{{ route('login.guru') }}" class="inline-flex items-center gap-2 font-semibold text-on-surface transition-colors hover:text-primary-container">
                        Masuk sebagai guru
                        <x-icon name="arrow-right" class="size-[16px]" />
                    </a>
                </div>
            </section>
        </main>
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
