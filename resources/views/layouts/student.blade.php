<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Ekskul') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            -webkit-tap-highlight-color: transparent;
        }
        .soft-shadow { box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.03); }
        .hide-scrollbar::-webkit-scrollbar { display: none; }
        .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="bg-background text-on-surface min-h-screen pb-24 md:pb-0 md:pl-72">

    <!-- Desktop Navigation -->
    <div class="hidden md:flex flex-col h-screen fixed left-0 top-0 py-lg bg-surface-container-low border-r border-outline-variant w-72 z-50">
        <div class="px-6 mb-10 flex items-center gap-2">
            <span class="material-symbols-outlined text-primary text-[32px]">school</span>
        </div>
        <div class="flex flex-col gap-1 px-2">
            <a class="{{ request()->routeIs('student.dashboard') ? 'bg-primary-container text-on-primary-container font-semibold' : 'text-on-surface-variant hover:bg-surface-variant' }} rounded-full px-4 py-3 flex items-center gap-3 transition-all" href="{{ route('student.dashboard') }}">
                <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">dashboard</span>
                <span class="font-label-md">Dashboard</span>
            </a>
            <a class="{{ request()->routeIs('student.extracurriculars.*') ? 'bg-primary-container text-on-primary-container font-semibold' : 'text-on-surface-variant hover:bg-surface-variant' }} rounded-full px-4 py-3 flex items-center gap-3 transition-all" href="{{ route('student.extracurriculars.index') }}">
                <span class="material-symbols-outlined">sports_basketball</span>
                <span class="font-label-md">Ekskul</span>
            </a>
            <a class="{{ request()->routeIs('student.attendances.*') ? 'bg-primary-container text-on-primary-container font-semibold' : 'text-on-surface-variant hover:bg-surface-variant' }} rounded-full px-4 py-3 flex items-center gap-3 transition-all" href="{{ route('student.attendances.index') }}">
                <span class="material-symbols-outlined">fact_check</span>
                <span class="font-label-md">Absensi</span>
            </a>
            <a class="{{ request()->routeIs('student.assessments.*') ? 'bg-primary-container text-on-primary-container font-semibold' : 'text-on-surface-variant hover:bg-surface-variant' }} rounded-full px-4 py-3 flex items-center gap-3 transition-all" href="{{ route('student.assessments.index') }}">
                <span class="material-symbols-outlined">grade</span>
                <span class="font-label-md">Penilaian</span>
            </a>
            <a class="{{ request()->routeIs('student.profile.*') ? 'bg-primary-container text-on-primary-container font-semibold' : 'text-on-surface-variant hover:bg-surface-variant' }} rounded-full px-4 py-3 flex items-center gap-3 transition-all" href="{{ route('student.profile.index') }}">
                <span class="material-symbols-outlined">account_circle</span>
                <span class="font-label-md">Profil Lengkap</span>
            </a>
        </div>
        <div class="mt-auto px-6 pb-6">
            <x-logout-button />
        </div>
    </div>

    <!-- Mobile Top Header -->
    <header class="md:hidden bg-surface sticky top-0 z-40 border-b border-outline-variant flex justify-between items-center px-margin-mobile py-4 w-full mx-auto">
        <div class="flex items-center gap-2">
            <span class="material-symbols-outlined text-primary text-[28px]">school</span>
        </div>
        <div class="flex items-center gap-4">
            <a href="{{ route('student.profile.index') }}" class="w-10 h-10 rounded-full bg-primary-container overflow-hidden border border-outline-variant flex items-center justify-center text-primary font-bold">
                {{ substr(Auth::user()->name, 0, 1) }}
            </a>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-container-max mx-auto px-margin-mobile md:px-8 pt-6 space-y-8 min-h-screen">
        @if(session('success'))
            <div class="p-4 bg-tertiary-container text-on-tertiary-container rounded-lg font-body-md">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="p-4 bg-error-container text-on-error-container rounded-lg font-body-md">{{ session('error') }}</div>
        @endif
        
        {{ $slot }}
    </main>

    <!-- Mobile BottomNavBar -->
    <nav class="md:hidden fixed bottom-0 w-full flex justify-around items-center py-2 bg-surface border-t border-outline-variant pb-safe z-50 soft-shadow">
        <a class="flex flex-col items-center justify-center {{ request()->routeIs('student.dashboard') ? 'text-primary font-bold' : 'text-on-secondary-container' }} active:scale-90 duration-100 px-4 py-1" href="{{ route('student.dashboard') }}">
            <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">home</span>
            <span class="font-label-sm mt-1">Beranda</span>
        </a>
        <a class="flex flex-col items-center justify-center {{ request()->routeIs('student.extracurriculars.*') ? 'text-primary font-bold' : 'text-on-secondary-container' }} active:scale-90 duration-100 px-4 py-1" href="{{ route('student.extracurriculars.index') }}">
            <span class="material-symbols-outlined">star</span>
            <span class="font-label-sm mt-1">Ekskul</span>
        </a>
        <a class="flex flex-col items-center justify-center {{ request()->routeIs('student.attendances.*') ? 'text-primary font-bold' : 'text-on-secondary-container' }} active:scale-90 duration-100 px-4 py-1" href="{{ route('student.attendances.index') }}">
            <span class="material-symbols-outlined">calendar_month</span>
            <span class="font-label-sm mt-1">Absensi</span>
        </a>
        <a class="flex flex-col items-center justify-center {{ request()->routeIs('student.profile.*') ? 'text-primary font-bold' : 'text-on-secondary-container' }} active:scale-90 duration-100 px-4 py-1" href="{{ route('student.profile.index') }}">
            <span class="material-symbols-outlined">account_circle</span>
            <span class="font-label-sm mt-1">Profil</span>
        </a>
    </nav>
</body>
</html>
