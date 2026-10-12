{{-- Bagian atas halaman masuk dan daftar: warna penuh per peran (siswa hijau cerah, guru hijau tua) dan judul. --}}
@props(['role', 'title', 'subtitle' => null, 'altHref', 'altLabel'])

<header class="{{ $role === 'siswa' ? 'bg-primary text-on-primary' : 'bg-primary-container text-on-primary-container' }} px-6 pb-16 pt-5 sm:px-8">
    <div class="flex items-center justify-between gap-4">
        <a href="/" aria-label="Kembali ke beranda"
           class="flex size-10 items-center justify-center rounded-full bg-surface/15 transition-colors hover:bg-surface/25">
            <x-icon name="arrow-left" class="size-[20px]" />
        </a>
        <a href="{{ $altHref }}" class="rounded-full bg-surface/15 px-4 py-2 text-label-lg transition-colors hover:bg-surface/25">{{ $altLabel }}</a>
    </div>

    <h1 class="mt-6 text-headline-md">{{ $title }}</h1>
    @if ($subtitle)
        <p class="mt-2 max-w-sm text-body-md opacity-80">{{ $subtitle }}</p>
    @endif
</header>
