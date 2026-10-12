@php
    // wide: halaman mengatur layoutnya sendiri (mis. login dengan panel samping).
    $wide = $attributes->has('wide');
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-role="{{ $attributes->get('role', 'teacher') }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $attributes->get('title', 'Ekskul') }}</title>
    <link rel="icon" type="image/svg+xml" href="/favicon.svg">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-background font-sans text-on-surface antialiased">
@if ($wide)
    {{ $slot }}
@else
    <div class="flex min-h-screen flex-col items-center justify-center px-4 py-10">
        <a href="/" class="mb-8 flex items-center gap-3">
            <span class="flex size-10 items-center justify-center rounded-lg bg-primary text-on-primary">
                <x-icon name="graduation-cap" class="size-[22px]" />
            </span>
            <span class="text-title-lg font-bold text-on-surface">Ekskul</span>
        </a>
        <div class="w-full max-w-md rounded-2xl border border-outline-variant bg-surface p-6 shadow-sm sm:p-8">
            {{ $slot }}
        </div>
    </div>
@endif
@stack('scripts')
</body>
</html>
