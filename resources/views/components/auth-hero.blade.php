{{-- Bagian atas halaman masuk dan daftar: warna penuh per peran (siswa hijau cerah, guru hijau tua), judul, dan ilustrasi berbentuk blob. --}}
@props(['role', 'title', 'subtitle' => null, 'image', 'alt' => '', 'compact' => false, 'altHref', 'altLabel'])

@php
    $siswa = $role === 'siswa';
    $blob = 'border-radius: 42% 58% 61% 39% / 45% 42% 58% 55%';
@endphp

<header class="{{ $siswa ? 'bg-primary text-on-primary' : 'bg-primary-container text-on-primary-container' }} px-6 pb-14 pt-5 sm:px-10">
    <div class="flex items-center justify-between gap-4">
        <a href="/" aria-label="Kembali ke beranda"
           class="flex size-10 items-center justify-center rounded-full bg-surface/15 transition-colors hover:bg-surface/25">
            <x-icon name="arrow-left" class="size-[20px]" />
        </a>
        <a href="{{ $altHref }}" class="rounded-full bg-surface/15 px-4 py-2 text-label-lg transition-colors hover:bg-surface/25">{{ $altLabel }}</a>
    </div>

    <h1 class="mt-6 text-headline-lg sm:text-display-md">{{ $title }}</h1>
    @if ($subtitle)
        <p class="mt-2 max-w-sm text-body-md opacity-80">{{ $subtitle }}</p>
    @endif

    <div class="relative mx-auto mt-6 {{ $compact ? 'size-36' : 'size-60 sm:size-64' }}">
        <div class="absolute inset-0 bg-surface/20" style="{{ $blob }}"></div>
        <img src="{{ $image }}" alt="{{ $alt }}" loading="eager" class="relative size-full object-cover" style="{{ $blob }}">
    </div>
</header>
