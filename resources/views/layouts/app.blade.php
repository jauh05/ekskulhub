<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'EkskulHub' }}</title>
    <link rel="icon" type="image/svg+xml" href="/favicon.svg">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            background-color: #F7F8FA;
        }
        .card-shadow {
            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.03);
        }
        .glass-nav {
            background: rgba(248, 249, 255, 0.8);
            backdrop-filter: blur(10px);
        }
    </style>
</head>
<body class="text-on-surface" x-data="{ sidebarOpen: window.innerWidth >= 768 }" @resize.window="if(window.innerWidth >= 768) { sidebarOpen = true } else { sidebarOpen = false }">
    @include('components.sidebar')

    <main class="min-h-screen transition-all duration-300" :style="sidebarOpen && window.innerWidth >= 768 ? 'padding-left: 16rem;' : 'padding-left: 0;'">
        @include('components.topbar')

        <div class="pt-24 px-4 md:px-8 pb-12">
            <!-- Flash Message -->
            @if(session('success'))
                <div class="mb-4 p-4 bg-tertiary-container text-on-tertiary-container rounded-lg">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mb-4 p-4 bg-error-container text-on-error-container rounded-lg">
                    {{ session('error') }}
                </div>
            @endif

            {{ $slot }}
        </div>
    </main>
    
    @stack('scripts')
</body>
</html>
