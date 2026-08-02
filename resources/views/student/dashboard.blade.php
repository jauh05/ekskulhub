<x-student-layout>
    <!-- Greeting Section -->
    <section class="animate-in fade-in slide-in-from-bottom-4 duration-700">
        <h2 class="font-headline-lg-mobile text-headline-lg-mobile text-on-surface">Halo, {{ Auth::user()->name }}!</h2>
        <p class="font-body-md text-on-secondary-container mt-1">Cek kegiatan ekskulmu hari ini.</p>
    </section>

    <!-- Stats Grid (2x2) -->
    <section class="grid grid-cols-2 gap-4">
        <div class="bg-surface p-4 rounded-xl border border-outline-variant soft-shadow flex flex-col gap-2">
            <span class="material-symbols-outlined text-primary" style="font-variation-settings: 'FILL' 1;">groups</span>
            <div>
                <p class="font-label-sm text-on-secondary-container">Ekskul Diikuti</p>
                <p class="font-title-lg text-title-lg text-on-surface">{{ $totalEkskul }}</p>
            </div>
        </div>
        <div class="bg-surface p-4 rounded-xl border border-outline-variant soft-shadow flex flex-col gap-2">
            <span class="material-symbols-outlined text-tertiary" style="font-variation-settings: 'FILL' 1;">event_available</span>
            <div>
                <p class="font-label-sm text-on-secondary-container">Persentase Hadir</p>
                <p class="font-title-lg text-title-lg text-on-surface">{{ $attendancePercentage }}%</p>
            </div>
        </div>
    </section>

    <!-- Jadwal Hari Ini Section -->
    <section class="space-y-4">
        <div class="flex justify-between items-end">
            <h3 class="font-title-lg text-title-lg text-on-surface">Jadwal Hari Ini</h3>
        </div>
        
        @forelse($jadwalHariIni as $jadwal)
        <div class="bg-primary-container p-5 rounded-xl text-on-primary-container soft-shadow relative overflow-hidden group mb-4">
            <!-- Abstract decorative element -->
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-white/10 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700"></div>
            <div class="flex justify-between items-start mb-4">
                <span class="px-2.5 py-0.5 rounded-full bg-white/20 text-white font-label-sm">Hari Ini</span>
                <span class="material-symbols-outlined text-white/80">sports_basketball</span>
            </div>
            <h4 class="font-headline-md text-headline-md text-white mb-1">{{ $jadwal->extracurricular->name }}</h4>
            <div class="flex flex-col gap-2 mt-4 text-white/90">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">schedule</span>
                    <span class="font-body-md">{{ \Carbon\Carbon::parse($jadwal->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($jadwal->end_time)->format('H:i') }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">location_on</span>
                    <span class="font-body-md">{{ $jadwal->location }}</span>
                </div>
            </div>
            <a href="{{ route('student.attendances.create', ['schedule_id' => $jadwal->id]) }}" class="block text-center mt-6 w-full bg-white text-primary font-title-md py-3 rounded-lg active:scale-95 transition-transform">
                Presensi Sekarang
            </a>
        </div>
        @empty
        <div class="p-6 bg-surface-container-low rounded-xl text-center border border-outline-variant">
            <span class="material-symbols-outlined text-[32px] text-on-surface-variant mb-2">event_busy</span>
            <p class="font-body-md text-on-surface-variant">Tidak ada jadwal ekskul hari ini.</p>
        </div>
        @endforelse
    </section>

    <!-- Ekskul Saya Section -->
    <section class="space-y-4">
        <h3 class="font-title-lg text-title-lg text-on-surface">Ekskul Saya</h3>
        <div class="flex gap-4 overflow-x-auto hide-scrollbar pb-2 px-1 -mx-1">
            @forelse($ekskulTerdaftar as $reg)
            <div class="min-w-[160px] max-w-[200px] bg-surface rounded-xl border {{ $reg->status == 'approved' ? 'border-outline-variant hover:border-primary' : 'border-error/30 opacity-75' }} soft-shadow overflow-hidden group cursor-pointer transition-colors flex-shrink-0 relative">
                <div class="h-24 {{ $reg->status == 'approved' ? 'bg-primary-container text-on-primary-container' : 'bg-surface-variant text-on-surface-variant' }} relative flex items-center justify-center overflow-hidden">
                    <span class="material-symbols-outlined text-[48px] opacity-20">sports_volleyball</span>
                </div>
                <div class="p-3">
                    <p class="font-title-md text-on-surface line-clamp-1">{{ $reg->extracurricular->name }}</p>
                    <p class="font-label-sm text-on-secondary-container line-clamp-1">{{ $reg->extracurricular->schedule }}</p>
                </div>
                @if($reg->status !== 'approved')
                <div class="absolute inset-0 bg-surface/60 backdrop-blur-[1px] flex flex-col items-center justify-center pointer-events-none">
                    <span class="bg-error/10 text-error border border-error/20 px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider shadow-sm">
                        {{ $reg->status == 'pending' ? 'Menunggu ACC' : 'Nonaktif' }}
                    </span>
                </div>
                @endif
            </div>
            @empty
            <div class="min-w-[160px] p-4 text-center text-on-surface-variant text-label-md border-2 border-dashed border-outline-variant rounded-xl flex items-center justify-center">
                Belum mengikuti ekskul
            </div>
            @endforelse

            <a href="{{ route('student.extracurriculars.index') }}" class="min-w-[120px] border-2 border-dashed border-outline-variant rounded-xl flex flex-col items-center justify-center gap-2 text-on-secondary-container hover:bg-surface-container-low transition-colors cursor-pointer active:scale-95 flex-shrink-0">
                <span class="material-symbols-outlined text-[32px]">add_circle</span>
                <span class="font-label-md">Cari Ekskul</span>
            </a>
        </div>
    </section>
</x-student-layout>
