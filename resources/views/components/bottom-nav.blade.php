<!-- Mobile BottomNavBar (Shared for Admin & Teacher) -->
<nav class="md:hidden fixed bottom-0 left-0 w-full flex overflow-x-auto hide-scrollbar items-center py-2 bg-surface border-t border-outline-variant pb-safe z-50 soft-shadow scroll-smooth snap-x">
    @if(Auth::user()->role === 'admin')
        <a class="snap-start flex flex-col items-center justify-center {{ request()->routeIs('admin.dashboard') ? 'text-primary font-bold' : 'text-on-secondary-container' }} active:scale-90 duration-100 px-4 py-1 flex-shrink-0 min-w-[72px]" href="{{ route('admin.dashboard') }}">
            <span class="material-symbols-outlined" {!! request()->routeIs('admin.dashboard') ? 'style="font-variation-settings: \'FILL\' 1;"' : '' !!}>dashboard</span>
            <span class="font-label-sm mt-1 text-[10px]">Dashboard</span>
        </a>
        <a class="snap-start flex flex-col items-center justify-center {{ request()->routeIs('admin.users.*') ? 'text-primary font-bold' : 'text-on-secondary-container' }} active:scale-90 duration-100 px-4 py-1 flex-shrink-0 min-w-[72px]" href="{{ route('admin.users.index') }}">
            <span class="material-symbols-outlined" {!! request()->routeIs('admin.users.*') ? 'style="font-variation-settings: \'FILL\' 1;"' : '' !!}>group</span>
            <span class="font-label-sm mt-1 text-[10px]">Pengguna</span>
        </a>
        <a class="snap-start flex flex-col items-center justify-center {{ request()->routeIs('admin.extracurriculars.*') ? 'text-primary font-bold' : 'text-on-secondary-container' }} active:scale-90 duration-100 px-4 py-1 flex-shrink-0 min-w-[72px]" href="{{ route('admin.extracurriculars.index') }}">
            <span class="material-symbols-outlined" {!! request()->routeIs('admin.extracurriculars.*') ? 'style="font-variation-settings: \'FILL\' 1;"' : '' !!}>sports_basketball</span>
            <span class="font-label-sm mt-1 text-[10px]">Ekskul</span>
        </a>
        <a class="snap-start flex flex-col items-center justify-center {{ request()->routeIs('admin.registrations.*') ? 'text-primary font-bold' : 'text-on-secondary-container' }} active:scale-90 duration-100 px-4 py-1 flex-shrink-0 min-w-[72px]" href="{{ route('admin.registrations.index') }}">
            <span class="material-symbols-outlined" {!! request()->routeIs('admin.registrations.*') ? 'style="font-variation-settings: \'FILL\' 1;"' : '' !!}>assignment</span>
            <span class="font-label-sm mt-1 text-[10px]">Daftar</span>
        </a>
    @elseif(Auth::user()->role === 'teacher')
        <a class="snap-start flex flex-col items-center justify-center {{ request()->routeIs('teacher.dashboard') ? 'text-primary font-bold' : 'text-on-secondary-container' }} active:scale-90 duration-100 px-4 py-1 flex-shrink-0 min-w-[72px]" href="{{ route('teacher.dashboard') }}">
            <span class="material-symbols-outlined" {!! request()->routeIs('teacher.dashboard') ? 'style="font-variation-settings: \'FILL\' 1;"' : '' !!}>dashboard</span>
            <span class="font-label-sm mt-1 text-[10px]">Beranda</span>
        </a>
        <a class="snap-start flex flex-col items-center justify-center {{ request()->routeIs('teacher.schedules.*') ? 'text-primary font-bold' : 'text-on-secondary-container' }} active:scale-90 duration-100 px-4 py-1 flex-shrink-0 min-w-[72px]" href="{{ route('teacher.schedules.index') }}">
            <span class="material-symbols-outlined" {!! request()->routeIs('teacher.schedules.*') ? 'style="font-variation-settings: \'FILL\' 1;"' : '' !!}>event</span>
            <span class="font-label-sm mt-1 text-[10px]">Jadwal</span>
        </a>
        <a class="snap-start flex flex-col items-center justify-center {{ request()->routeIs('teacher.attendances.*') ? 'text-primary font-bold' : 'text-on-secondary-container' }} active:scale-90 duration-100 px-4 py-1 flex-shrink-0 min-w-[72px]" href="{{ route('teacher.attendances.index') }}">
            <span class="material-symbols-outlined" {!! request()->routeIs('teacher.attendances.*') ? 'style="font-variation-settings: \'FILL\' 1;"' : '' !!}>fact_check</span>
            <span class="font-label-sm mt-1 text-[10px]">Absensi</span>
        </a>
        <a class="snap-start flex flex-col items-center justify-center {{ request()->routeIs('teacher.participants.*') ? 'text-primary font-bold' : 'text-on-secondary-container' }} active:scale-90 duration-100 px-4 py-1 flex-shrink-0 min-w-[72px]" href="{{ route('teacher.participants.index') }}">
            <span class="material-symbols-outlined" {!! request()->routeIs('teacher.participants.*') ? 'style="font-variation-settings: \'FILL\' 1;"' : '' !!}>group</span>
            <span class="font-label-sm mt-1 text-[10px]">Siswa</span>
        </a>
        <a class="snap-start flex flex-col items-center justify-center {{ request()->routeIs('teacher.registrations.*') ? 'text-primary font-bold' : 'text-on-secondary-container' }} active:scale-90 duration-100 px-4 py-1 flex-shrink-0 min-w-[72px]" href="{{ route('teacher.registrations.index') }}">
            <span class="material-symbols-outlined" {!! request()->routeIs('teacher.registrations.*') ? 'style="font-variation-settings: \'FILL\' 1;"' : '' !!}>how_to_reg</span>
            <span class="font-label-sm mt-1 text-[10px]">Daftar</span>
        </a>
        <a class="snap-start flex flex-col items-center justify-center {{ request()->routeIs('teacher.announcements.*') ? 'text-primary font-bold' : 'text-on-secondary-container' }} active:scale-90 duration-100 px-4 py-1 flex-shrink-0 min-w-[72px]" href="{{ route('teacher.announcements.index') }}">
            <span class="material-symbols-outlined" {!! request()->routeIs('teacher.announcements.*') ? 'style="font-variation-settings: \'FILL\' 1;"' : '' !!}>campaign</span>
            <span class="font-label-sm mt-1 text-[10px]">Info</span>
        </a>
        <a class="snap-start flex flex-col items-center justify-center {{ request()->routeIs('teacher.grading.*') ? 'text-primary font-bold' : 'text-on-secondary-container' }} active:scale-90 duration-100 px-4 py-1 flex-shrink-0 min-w-[72px]" href="{{ route('teacher.grading.index') }}">
            <span class="material-symbols-outlined" {!! request()->routeIs('teacher.grading.*') ? 'style="font-variation-settings: \'FILL\' 1;"' : '' !!}>grade</span>
            <span class="font-label-sm mt-1 text-[10px]">Nilai</span>
        </a>
        <a class="snap-start flex flex-col items-center justify-center {{ request()->routeIs('teacher.reports.*') ? 'text-primary font-bold' : 'text-on-secondary-container' }} active:scale-90 duration-100 px-4 py-1 flex-shrink-0 min-w-[72px]" href="{{ route('teacher.reports.index') }}">
            <span class="material-symbols-outlined" {!! request()->routeIs('teacher.reports.*') ? 'style="font-variation-settings: \'FILL\' 1;"' : '' !!}>bar_chart</span>
            <span class="font-label-sm mt-1 text-[10px]">Hasil</span>
        </a>
    @endif
    <a class="snap-start flex flex-col items-center justify-center {{ request()->routeIs('profile.edit') ? 'text-primary font-bold' : 'text-on-secondary-container' }} active:scale-90 duration-100 px-4 py-1 flex-shrink-0 min-w-[72px]" href="{{ route('profile.edit') }}">
        <span class="material-symbols-outlined" {!! request()->routeIs('profile.edit') ? 'style="font-variation-settings: \'FILL\' 1;"' : '' !!}>person</span>
        <span class="font-label-sm mt-1 text-[10px]">Profil</span>
    </a>
    <form method="POST" action="{{ route('logout') }}" class="snap-start flex flex-col items-center justify-center text-error active:scale-90 duration-100 px-4 py-1 flex-shrink-0 min-w-[72px]">
        @csrf
        <button type="submit" onclick="confirmAction(event, 'Apakah Anda yakin ingin keluar?', this.closest('form'))" class="flex flex-col items-center justify-center w-full focus:outline-none">
            <span class="material-symbols-outlined">logout</span>
            <span class="font-label-sm mt-1 text-[10px]">Keluar</span>
        </button>
    </form>
</nav>
