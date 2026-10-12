<div x-data="activeSessionPoller()" x-init="initPoller()" class="w-full">
    <!-- Indikator Polling -->
    <div x-show="isPolling" x-transition.opacity.duration.300ms class="mb-2 flex items-center gap-2 text-body-xs text-secondary">
        <x-icon name="refresh-cw" class="animate-spin size-[14px]" />
        Memeriksa sesi presensi...
    </div>

    <template x-for="session in activeSessions" :key="session.id">
        <div x-show="!session.already_attended" x-transition.opacity.duration.500ms
             class="mb-6 rounded-xl bg-primary p-5 text-on-primary shadow-md">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div class="min-w-0">
                    <div class="mb-1 flex items-center gap-2">
                        <x-icon name="radio" class="size-[16px]" />
                        <span class="text-label-xs font-bold uppercase tracking-widest">Sesi Live Presensi Berjalan</span>
                    </div>
                    <h4 class="text-headline-sm font-bold" x-text="'Ekskul: ' + session.extracurricular_name"></h4>
                    <p class="mt-1 text-body-sm opacity-80">Guru Anda telah membuka sesi presensi. Segera presensi untuk mencatat kehadiran Anda!</p>
                </div>

                <a :href="'{{ url('siswa/absensi/scan') }}?schedule_id=' + session.schedule_id"
                   class="inline-flex shrink-0 items-center justify-center gap-2 whitespace-nowrap rounded-lg bg-primary-container px-4 py-2.5 text-label-lg text-on-primary-container transition-opacity hover:opacity-90">
                    <x-icon name="user-check" class="size-[18px]" /> Presensi Sekarang
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
