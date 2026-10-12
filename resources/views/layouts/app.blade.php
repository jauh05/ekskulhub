@php
    $role = match (Auth::user()?->role) {
        'admin' => 'admin',
        'student' => 'student',
        default => 'teacher',
    };

    // Menu dihitung sekali di sini, lalu dipakai sidebar dan bottom-nav.
    $navItems = match ($role) {
        'admin' => [
            ['match' => 'admin.dashboard', 'route' => 'admin.dashboard', 'icon' => 'layout-dashboard', 'label' => 'Dashboard', 'short' => 'Beranda'],
            ['match' => 'admin.users.*', 'route' => 'admin.users.index', 'icon' => 'users', 'label' => 'Data Pengguna', 'short' => 'Pengguna'],
            ['match' => 'admin.extracurriculars.*', 'route' => 'admin.extracurriculars.index', 'icon' => 'trophy', 'label' => 'Data Ekskul', 'short' => 'Ekskul'],
            ['match' => 'admin.registrations.*', 'route' => 'admin.registrations.index', 'icon' => 'clipboard-list', 'label' => 'Pendaftaran', 'short' => 'Daftar'],
            ['match' => 'profile.edit', 'route' => 'profile.edit', 'icon' => 'user', 'label' => 'Profil Saya', 'short' => 'Profil'],
        ],
        'student' => [
            ['match' => 'student.dashboard', 'route' => 'student.dashboard', 'icon' => 'layout-dashboard', 'label' => 'Dashboard', 'short' => 'Beranda'],
            ['match' => 'student.extracurriculars.*', 'route' => 'student.extracurriculars.index', 'icon' => 'trophy', 'label' => 'Ekskul', 'short' => 'Ekskul'],
            ['match' => 'student.attendances.*', 'route' => 'student.attendances.index', 'icon' => 'clipboard-check', 'label' => 'Absensi', 'short' => 'Absensi'],
            ['match' => 'student.assessments.*', 'route' => 'student.assessments.index', 'icon' => 'star', 'label' => 'Penilaian', 'short' => 'Nilai'],
            ['match' => 'student.profile.*', 'route' => 'student.profile.index', 'icon' => 'circle-user-round', 'label' => 'Profil Lengkap', 'short' => 'Profil'],
        ],
        default => [
            ['match' => 'teacher.dashboard', 'route' => 'teacher.dashboard', 'icon' => 'layout-dashboard', 'label' => 'Dashboard', 'short' => 'Beranda'],
            ['match' => 'teacher.schedules.*', 'route' => 'teacher.schedules.index', 'icon' => 'calendar-check', 'label' => 'Jadwal Ekskul', 'short' => 'Jadwal'],
            ['match' => 'teacher.attendances.*', 'route' => 'teacher.attendances.index', 'icon' => 'clipboard-check', 'label' => 'Absensi', 'short' => 'Absensi'],
            ['match' => 'teacher.participants.*', 'route' => 'teacher.participants.index', 'icon' => 'users', 'label' => 'Data Siswa', 'short' => 'Siswa'],
            ['match' => 'teacher.registrations.*', 'route' => 'teacher.registrations.index', 'icon' => 'user-check', 'label' => 'Pendaftaran Siswa', 'short' => 'Daftar'],
            ['match' => 'teacher.announcements.*', 'route' => 'teacher.announcements.index', 'icon' => 'megaphone', 'label' => 'Pengumuman', 'short' => 'Info'],
            ['match' => 'teacher.grading.*', 'route' => 'teacher.grading.index', 'icon' => 'star', 'label' => 'Penilaian', 'short' => 'Nilai'],
            ['match' => 'teacher.reports.*', 'route' => 'teacher.reports.index', 'icon' => 'chart-column', 'label' => 'Hasil', 'short' => 'Hasil'],
            ['match' => 'profile.edit', 'route' => 'profile.edit', 'icon' => 'user', 'label' => 'Profil Saya', 'short' => 'Profil'],
        ],
    };

    $roleName = match ($role) {
        'admin' => 'Administrator',
        'student' => 'Siswa',
        default => 'Guru',
    };

    $roleTitle = match ($role) {
        'admin' => 'Dashboard Admin',
        'student' => 'Dashboard Siswa',
        default => 'Dashboard Guru',
    };
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-role="{{ $role }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Ekskul' }}</title>
    <link rel="icon" type="image/svg+xml" href="/favicon.svg">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-paper font-sans text-on-surface antialiased"
      x-data="{ sidebarOpen: window.innerWidth >= 768 }"
      @resize.window="sidebarOpen = window.innerWidth >= 768">
    @include('components.sidebar')

    <main class="min-h-screen md:pb-0 {{ $role === 'student' ? '' : 'pb-20' }}"
          :style="sidebarOpen && window.innerWidth >= 768 ? 'padding-left: 16rem;' : 'padding-left: 0;'">
        @include('components.topbar')
        @if ($role === 'student')
            @include('components.student-bottom-nav')
        @else
            @include('components.bottom-nav')
        @endif

        <div class="mx-auto max-w-container-max px-4 md:px-8 md:pb-12 md:pt-24 {{ $role === 'student' ? 'pb-32 pt-6' : 'pb-12 pt-24' }}">
            @if (session('success'))
                <div class="mb-6 rounded-lg border border-tertiary/20 bg-tertiary-container px-4 py-3 text-body-md text-on-tertiary-container">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="mb-6 rounded-lg border border-error/20 bg-error-container px-4 py-3 text-body-md text-on-error-container">
                    {{ session('error') }}
                </div>
            @endif

            {{ $slot }}
        </div>
    </main>

    @stack('scripts')
</body>
</html>
