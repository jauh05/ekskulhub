<x-app-layout>
    <x-slot name="header">
        <h2 class="text-headline-md text-on-surface">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    @php
        $inisialAkun = collect(explode(' ', trim($user->name)))->filter()->take(2)->map(fn ($bagian) => mb_strtoupper(mb_substr($bagian, 0, 1)))->implode('');
    @endphp

    <div class="flex w-full max-w-2xl flex-col gap-6">
        <section>
            <p class="text-body-md text-secondary">Akun</p>
            <h2 class="mt-1 text-headline-lg-mobile font-bold text-on-surface md:text-headline-lg">Profil Saya</h2>
            <p class="mt-1 text-body-md text-secondary">Kelola informasi akun, kata sandi, dan penghapusan akun kamu.</p>
        </section>

        <!-- Kartu profil -->
        <section class="rounded-xl border border-outline-variant bg-surface p-5 md:p-6">
            <div class="flex items-center gap-4">
                <span class="flex size-20 shrink-0 items-center justify-center rounded-full bg-primary-fixed text-headline-sm font-bold text-on-primary-fixed">{{ $inisialAkun }}</span>
                <div class="min-w-0">
                    <p class="truncate text-title-lg font-bold text-on-surface">{{ $user->name }}</p>
                    <p class="truncate text-body-md text-secondary">{{ $user->email }}</p>
                </div>
            </div>

            <div class="mt-5 grid grid-cols-2 gap-3">
                <div class="flex min-w-0 flex-col justify-between gap-3 rounded-xl bg-primary-container p-4 text-on-primary-container">
                    <span class="text-label-md opacity-90">Bergabung</span>
                    <p class="text-title-lg font-bold">{{ $user->created_at?->translatedFormat('M Y') ?? '-' }}</p>
                </div>
                <div class="flex min-w-0 flex-col justify-between gap-3 rounded-xl border border-outline-variant bg-surface-container-low p-4">
                    <span class="text-label-md text-secondary">Status email</span>
                    @if(filled($user->email_verified_at))
                        <p class="text-title-lg font-bold text-on-surface">Terverifikasi</p>
                    @else
                        <p class="text-title-lg font-bold text-warning">Belum</p>
                    @endif
                </div>
            </div>
        </section>

        <section class="rounded-xl border border-outline-variant bg-surface p-5 md:p-6">
            @include('profile.partials.update-profile-information-form')
        </section>

        <section class="rounded-xl border border-outline-variant bg-surface p-5 md:p-6">
            @include('profile.partials.update-password-form')
        </section>

        <section class="rounded-xl border border-error/30 bg-surface p-5 md:p-6">
            @include('profile.partials.delete-user-form')
        </section>
    </div>
</x-app-layout>
