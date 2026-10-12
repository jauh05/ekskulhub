{{-- Header halaman siswa (terang): tombol kembali opsional, subjudul, dan judul. --}}
@props(['title', 'subtitle' => null, 'back' => null])

<header class="flex items-center gap-3">
    @if ($back)
        <a href="{{ $back }}" aria-label="Kembali" class="flex size-10 shrink-0 items-center justify-center rounded-full border border-outline-variant bg-surface text-on-surface transition-colors hover:bg-surface-container">
            <x-icon name="arrow-left" class="size-[18px]" />
        </a>
    @endif
    <div class="min-w-0">
        @if ($subtitle)
            <p class="truncate text-body-sm text-secondary">{{ $subtitle }}</p>
        @endif
        <h2 class="truncate text-title-lg font-bold text-on-surface md:text-headline-md">{{ $title }}</h2>
    </div>
</header>
