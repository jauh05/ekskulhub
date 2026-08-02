<div x-data="activeSessionPoller()" x-init="initPoller()" class="w-full">
    <!-- Polling Indicator -->
    <div x-show="isPolling" x-transition.opacity.duration.300ms class="flex items-center gap-2 text-secondary text-xs mb-2">
        <span class="material-symbols-outlined text-[14px] animate-spin">sync</span>
        Memeriksa sesi presensi...
    </div>

    <template x-for="session in activeSessions" :key="session.id">
        <div x-show="!session.already_attended" x-transition.opacity.duration.500ms
             class="mb-6 relative overflow-hidden bg-primary p-6 rounded-2xl shadow-xl border border-primary-container animate-pulse">
            <!-- Decorative Background Pattern -->
            <div class="absolute top-0 right-0 opacity-10 pointer-events-none translate-x-1/4 -translate-y-1/4">
                <span class="material-symbols-outlined text-[120px]">podcasts</span>
            </div>
            
            <div class="relative z-10 flex flex-col sm:flex-row justify-between sm:items-center gap-4">
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <span class="material-symbols-outlined text-white text-[20px] animate-bounce">fiber_manual_record</span>
                        <span class="text-sm font-bold text-white tracking-widest uppercase">Sesi Live Presensi Berjalan</span>
                    </div>
                    <h4 class="text-2xl font-bold text-white mb-1" x-text="'Ekskul: ' + session.extracurricular_name"></h4>
                    <p class="text-white/90 text-sm">Guru Anda telah membuka sesi presensi. Segera presensi untuk mencatat kehadiran Anda!</p>
                </div>
                
                <a :href="'{{ url('siswa/absensi/scan') }}?schedule_id=' + session.schedule_id" 
                   class="bg-white text-primary px-6 py-3 rounded-full text-label-lg font-bold shadow-lg hover:scale-105 active:scale-95 transition-all text-center flex items-center justify-center gap-2 whitespace-nowrap group">
                    <span class="material-symbols-outlined group-hover:rotate-12 transition-transform">how_to_reg</span> Presensi Sekarang
                </a>
            </div>
        </div>
    </template>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('activeSessionPoller', () => ({
            activeSessions: [],
            intervalId: null,
            isPolling: false,
            
            initPoller() {
                this.fetchSessions();
                this.intervalId = setInterval(() => {
                    this.fetchSessions();
                }, 10000); // 10 seconds
            },
            
            async fetchSessions() {
                this.isPolling = true;
                try {
                    const response = await fetch('{{ route("student.api.active-sessions") }}', {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });
                    
                    if (response.ok) {
                        this.activeSessions = await response.json();
                    }
                } catch (error) {
                    console.error('Failed to fetch active sessions', error);
                } finally {
                    setTimeout(() => { this.isPolling = false; }, 1000);
                }
            }
        }));
    });
</script>
