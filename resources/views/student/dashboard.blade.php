<x-student-layout>
<div class="flex flex-col w-full gap-6 pb-4">
    <!-- Welcome Greeting -->
    <section class="mt-2">
        <div class="flex flex-col">
            <span class="text-sm font-medium text-secondary">Semangat pagi!</span>
            <h2 class="text-2xl font-bold text-on-surface">Halo, {{ explode(' ', Auth::user()->name)[0] }}! 👋</h2>
        </div>
    </section>

    <!-- Quick Stats Row -->
    <section class="grid grid-cols-2 gap-4">
        <div class="bg-surface-container-low p-4 rounded-xl flex flex-col gap-1 shadow-sm">
            <div class="w-8 h-8 rounded-lg bg-primary/10 flex items-center justify-center mb-1">
                <span class="material-symbols-outlined text-primary text-[20px]">exercise</span>
            </div>
            <span class="text-xs font-medium text-secondary uppercase tracking-wider">Ekskul Aktif</span>
            <span class="text-xl font-bold text-on-surface">{{ $totalEkskul }}</span>
        </div>
        <div class="bg-surface-container-low p-4 rounded-xl flex flex-col gap-1 shadow-sm">
            <div class="w-8 h-8 rounded-lg bg-tertiary/10 flex items-center justify-center mb-1">
                <span class="material-symbols-outlined text-tertiary text-[20px]">verified</span>
            </div>
            <span class="text-xs font-medium text-secondary uppercase tracking-wider">Kehadiran</span>
            <span class="text-xl font-bold text-on-surface">{{ $attendancePercentage }}%</span>
        </div>
    </section>

    <!-- Jadwal Hari Ini Card -->
    <section class="flex flex-col gap-2">
        <div class="flex items-center justify-between mb-1">
            <h3 class="text-lg font-semibold text-on-surface">Jadwal Hari Ini</h3>
            <a href="{{ route('student.attendances.index') }}" class="text-sm font-medium text-primary">Lihat Kalender</a>
        </div>
        
        @forelse($jadwalHariIni as $jadwal)
        <div class="relative overflow-hidden bg-primary p-6 rounded-2xl shadow-xl mb-4">
            <!-- Decorative Background Pattern -->
            <div class="absolute top-0 right-0 opacity-10 pointer-events-none translate-x-1/4 -translate-y-1/4">
                <span class="material-symbols-outlined text-[120px]">sports_basketball</span>
            </div>
            <div class="relative z-10 flex flex-col gap-4">
                <div class="flex justify-between items-start">
                    <div class="bg-white/20 backdrop-blur-md px-2 py-1 rounded-full">
                        <span class="text-xs font-medium text-white">Hari Ini</span>
                    </div>
                    <span class="material-symbols-outlined text-white">more_vert</span>
                </div>
                <div>
                    <h4 class="text-2xl font-bold text-white">{{ $jadwal->extracurricular->name }}</h4>
                    <div class="flex items-center gap-1 text-white/80 mt-1">
                        <span class="material-symbols-outlined text-[18px]">location_on</span>
                        <span class="text-sm font-medium">{{ $jadwal->location }}</span>
                    </div>
                </div>
                <div class="flex items-center justify-between pt-2">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-white">schedule</span>
                        <span class="text-lg font-semibold text-white">{{ \Carbon\Carbon::parse($jadwal->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($jadwal->end_time)->format('H:i') }}</span>
                    </div>
                    <a href="{{ route('student.attendances.create', ['schedule_id' => $jadwal->id]) }}" class="bg-white text-primary px-6 py-2 rounded-full text-sm font-bold shadow-lg active:scale-95 transition-transform block">
                        Presensi
                    </a>
                </div>
            </div>
        </div>
        @empty
        <div class="p-8 bg-surface-container-low rounded-2xl text-center border border-dashed border-outline-variant/50 flex flex-col items-center justify-center shadow-sm">
            <span class="material-symbols-outlined text-[32px] text-on-surface-variant mb-2">event_busy</span>
            <p class="text-lg font-semibold text-on-surface mb-1">Tidak ada jadwal</p>
        </div>
        @endforelse
    </section>

    <!-- Pengumuman Terbaru List -->
    @if(isset($pengumuman) && $pengumuman->count() > 0)
    <section class="flex flex-col gap-2 mt-4">
        <h3 class="text-lg font-semibold text-on-surface mb-1 flex items-center gap-1">
            <span class="material-symbols-outlined text-primary text-[20px]">campaign</span> Pengumuman Terbaru
        </h3>
        <div class="flex flex-col gap-3">
            @foreach($pengumuman as $p)
            <div class="bg-surface-container-lowest p-4 rounded-xl shadow-sm border border-outline-variant/30 relative overflow-hidden group">
                <div class="absolute left-0 top-0 bottom-0 w-1 bg-primary group-hover:w-1.5 transition-all"></div>
                <div class="pl-2">
                    <div class="flex items-center justify-between mb-1">
                        <span class="text-[10px] font-bold text-primary uppercase tracking-wider bg-primary/10 px-2 py-0.5 rounded-full">{{ $p->extracurricular->name }}</span>
                        <span class="text-xs text-secondary">{{ \Carbon\Carbon::parse($p->published_at)->diffForHumans() }}</span>
                    </div>
                    <h5 class="text-base font-bold text-on-surface mb-1">{{ $p->title }}</h5>
                    <p class="text-sm text-on-surface-variant line-clamp-2 leading-relaxed">{{ $p->content }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </section>
    @endif

    <!-- Aktivitas Terbaru List -->
    <section class="flex flex-col gap-2 mt-4">
        <h3 class="text-lg font-semibold text-on-surface mb-1">Status Registrasi</h3>
        <div class="flex flex-col gap-2">
            @forelse($ekskulTerdaftar as $reg)
            <div class="bg-surface-container-lowest p-4 rounded-xl flex items-center gap-4 shadow-sm border border-outline-variant/30">
                <div class="w-12 h-12 rounded-full shrink-0 {{ $reg->status == 'approved' ? 'bg-secondary-container text-on-secondary-container' : 'bg-surface-variant text-on-surface-variant' }} flex items-center justify-center">
                    <span class="material-symbols-outlined">{{ $reg->status == 'approved' ? 'music_note' : 'edit_note' }}</span>
                </div>
                <div class="flex-1 min-w-0">
                    <h5 class="text-sm font-bold text-on-surface truncate">{{ $reg->extracurricular->name }}</h5>
                    <p class="text-xs font-medium text-secondary">{{ $reg->status == 'approved' ? 'Pendaftaran Berhasil' : ($reg->status == 'pending' ? 'Menunggu Verifikasi' : 'Ditolak') }}</p>
                </div>
                @if($reg->status == 'approved')
                    <div class="bg-green-100 text-green-700 px-2 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider border border-green-200 shrink-0">Disetujui</div>
                @elseif($reg->status == 'pending')
                    <div class="bg-surface-container text-on-surface-variant px-2 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider border border-surface-variant shrink-0">Pending</div>
                @else
                    <div class="bg-error-container text-on-error-container px-2 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider border border-error/20 shrink-0">Ditolak</div>
                @endif
            </div>
            @empty
            <div class="p-4 bg-surface-container-low rounded-xl text-center text-on-surface-variant text-sm font-medium">
                Belum ada pendaftaran ekskul.
            </div>
            @endforelse
        </div>
    </section>

    <!-- Rekomendasi Ekskul Horizontal Scroll -->
    <section class="flex flex-col gap-2 mt-4">
        <div class="flex items-center justify-between mb-1">
            <h3 class="text-lg font-semibold text-on-surface">Ekskul Yang Diikuti</h3>
            <a href="{{ route('student.extracurriculars.index') }}" class="text-sm font-medium text-primary">Lihat Semua</a>
        </div>
        <div class="flex overflow-x-auto gap-4 pb-4 -mx-4 px-4 md:mx-0 md:px-0 hide-scrollbar">
            @forelse($ekskulTerdaftar->where('status', 'approved') as $reg)
            <div class="w-[200px] bg-surface-container-low rounded-2xl overflow-hidden shadow-sm flex-shrink-0 group cursor-pointer border border-outline-variant/30 flex flex-col">
                <div class="h-24 w-full relative overflow-hidden bg-primary-container text-on-primary-container flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-[48px] opacity-50 group-hover:scale-110 transition-transform duration-500">sports_volleyball</span>
                    <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent"></div>
                </div>
                <div class="p-4 flex flex-col gap-1 flex-1">
                    <div class="flex items-center gap-1">
                        <span class="material-symbols-outlined text-primary text-[18px]">sports_esports</span>
                        <h5 class="text-sm font-bold text-on-surface line-clamp-1">{{ $reg->extracurricular->name }}</h5>
                    </div>
                    <p class="text-xs text-secondary line-clamp-1">{{ $reg->extracurricular->regular_day ?? 'Jadwal Rutin' }}</p>
                </div>
            </div>
            @empty
            <div class="w-[200px] bg-surface-container-low rounded-2xl overflow-hidden shadow-sm flex-shrink-0 flex items-center justify-center h-32 p-4 text-center border-2 border-dashed border-outline-variant">
                <span class="text-sm text-on-surface-variant font-medium">Belum mengikuti ekskul aktif.</span>
            </div>
            @endforelse
        </div>
    </section>

    <!-- Interactive Script for Micro-interactions -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const cards = document.querySelectorAll('.bg-surface-container-low, .bg-surface-container-lowest, .bg-primary');
            
            cards.forEach(card => {
                card.addEventListener('touchstart', () => {
                    card.style.transform = 'scale(0.98)';
                    card.style.transition = 'transform 0.2s cubic-bezier(0.34, 1.56, 0.64, 1)';
                }, {passive: true});
                
                card.addEventListener('touchend', () => {
                    card.style.transform = 'scale(1)';
                }, {passive: true});
            });
            
            // Horizontal scroll bounce effect hint
            const scrollContainer = document.querySelector('.overflow-x-auto');
            if(scrollContainer) {
                setTimeout(() => {
                    scrollContainer.scrollBy({ left: 40, behavior: 'smooth' });
                    setTimeout(() => {
                        scrollContainer.scrollBy({ left: -40, behavior: 'smooth' });
                    }, 600);
                }, 1000);
            }
        });
    </script>
</div>
</x-student-layout>
