<!-- Mobile overlay -->
<div x-show="sidebarOpen" class="fixed inset-0 bg-black/50 z-20 md:hidden" @click="sidebarOpen = false" x-transition.opacity style="display: none;"></div>

<aside class="h-screen w-64 fixed left-0 top-0 bg-surface-container-lowest border-r border-outline-variant flex flex-col py-6 z-30 transition-transform duration-300"
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
    <div class="px-6 mb-10 flex items-center gap-3">
        <div class="w-10 h-10 bg-primary-container rounded-lg flex items-center justify-center">
            <span class="material-symbols-outlined text-white" style="font-variation-settings: 'FILL' 1;">school</span>
        </div>
        <div>
            <h1 class="font-headline-md text-headline-md font-bold text-primary">Ekskul</h1>
            <p class="text-label-sm text-secondary -mt-1">Management</p>
        </div>
    </div>
    <nav class="flex-1 space-y-1">
        @if(Auth::user()->role === 'admin')
            <a class="flex items-center gap-3 px-4 py-3 mx-2 {{ request()->routeIs('admin.dashboard') ? 'bg-primary-container text-on-primary-container font-semibold' : 'text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface' }} rounded-lg transition-colors" href="{{ route('admin.dashboard') }}">
                <span class="material-symbols-outlined">dashboard</span>
                <span class="font-body-md">Dashboard</span>
            <!-- more links later -->
            <a class="flex items-center gap-3 px-4 py-3 mx-2 {{ request()->routeIs('admin.users.*') ? 'bg-primary-container text-on-primary-container font-semibold' : 'text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface' }} rounded-lg transition-colors" href="{{ route('admin.users.index') }}">
                <span class="material-symbols-outlined">group</span>
                <span class="font-body-md">Data Pengguna</span>
            </a>
            <a class="flex items-center gap-3 px-4 py-3 mx-2 {{ request()->routeIs('admin.extracurriculars.*') ? 'bg-primary-container text-on-primary-container font-semibold' : 'text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface' }} rounded-lg transition-colors" href="{{ route('admin.extracurriculars.index') }}">
                <span class="material-symbols-outlined">sports_basketball</span>
                <span class="font-body-md">Data Ekskul</span>
            </a>
            <a class="flex items-center gap-3 px-4 py-3 mx-2 {{ request()->routeIs('admin.registrations.*') ? 'bg-primary-container text-on-primary-container font-semibold' : 'text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface' }} rounded-lg transition-colors" href="{{ route('admin.registrations.index') }}">
                <span class="material-symbols-outlined">assignment</span>
                <span class="font-body-md">Pendaftaran</span>
            </a>
        @elseif(Auth::user()->role === 'teacher')
            <a class="flex items-center gap-3 px-4 py-3 mx-2 {{ request()->routeIs('teacher.dashboard') ? 'bg-primary-container text-on-primary-container font-semibold' : 'text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface' }} rounded-lg transition-colors" href="{{ route('teacher.dashboard') }}">
                <span class="material-symbols-outlined">dashboard</span>
                <span class="font-body-md">Dashboard</span>
            </a>
            <a class="flex items-center gap-3 px-4 py-3 mx-2 {{ request()->routeIs('teacher.schedules.*') ? 'bg-primary-container text-on-primary-container font-semibold' : 'text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface' }} rounded-lg transition-colors" href="{{ route('teacher.schedules.index') }}">
                <span class="material-symbols-outlined">event</span>
                <span class="font-body-md">Jadwal Ekskul</span>
            </a>
            <a class="flex items-center gap-3 px-4 py-3 mx-2 {{ request()->routeIs('teacher.attendances.*') ? 'bg-primary-container text-on-primary-container font-semibold' : 'text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface' }} rounded-lg transition-colors" href="{{ route('teacher.attendances.index') }}">
                <span class="material-symbols-outlined">fact_check</span>
                <span class="font-body-md">Absensi</span>
            </a>
            <a class="flex items-center gap-3 px-4 py-3 mx-2 {{ request()->routeIs('teacher.participants.*') ? 'bg-primary-container text-on-primary-container font-semibold' : 'text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface' }} rounded-lg transition-colors" href="{{ route('teacher.participants.index') }}">
                <span class="material-symbols-outlined">group</span>
                <span class="font-body-md">Data Siswa</span>
            </a>
            <a class="flex items-center gap-3 px-4 py-3 mx-2 {{ request()->routeIs('teacher.registrations.*') ? 'bg-primary-container text-on-primary-container font-semibold' : 'text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface' }} rounded-lg transition-colors" href="{{ route('teacher.registrations.index') }}">
                <span class="material-symbols-outlined">how_to_reg</span>
                <span class="font-body-md">Pendaftaran Siswa</span>
            </a>
            <a class="flex items-center gap-3 px-4 py-3 mx-2 {{ request()->routeIs('teacher.announcements.*') ? 'bg-primary-container text-on-primary-container font-semibold' : 'text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface' }} rounded-lg transition-colors" href="{{ route('teacher.announcements.index') }}">
                <span class="material-symbols-outlined">campaign</span>
                <span class="font-body-md">Pengumuman</span>
            </a>
            <a class="flex items-center gap-3 px-4 py-3 mx-2 {{ request()->routeIs('teacher.grading.*') ? 'bg-primary-container text-on-primary-container font-semibold' : 'text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface' }} rounded-lg transition-colors" href="{{ route('teacher.grading.index') }}">
                <span class="material-symbols-outlined">grade</span>
                <span class="font-body-md">Penilaian</span>
            </a>
            <a class="flex items-center gap-3 px-4 py-3 mx-2 {{ request()->routeIs('teacher.reports.*') ? 'bg-primary-container text-on-primary-container font-semibold' : 'text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface' }} rounded-lg transition-colors" href="{{ route('teacher.reports.index') }}">
                <span class="material-symbols-outlined">bar_chart</span>
                <span class="font-body-md">Hasil</span>
            </a>
@endif
    </nav>
    <div class="px-2 mt-auto pt-6 border-t border-outline-variant">
        <a class="flex items-center gap-3 px-4 py-3 text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface rounded-lg transition-colors" href="{{ route('profile.edit') }}">
            <span class="material-symbols-outlined">person</span>
            <span class="font-body-md">Profile</span>
        </a>
        <x-logout-button class="w-full flex items-center gap-3 px-4 py-3 text-error hover:bg-error-container/20 rounded-lg transition-colors" iconClass="" showText="true" />
    </div>
</aside>
