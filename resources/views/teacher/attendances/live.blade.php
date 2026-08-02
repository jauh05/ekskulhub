<x-app-layout>
    <div class="pt-24 px-8 pb-12" x-data="liveAttendance('{{ $session->id }}')">
        
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row justify-between md:items-end gap-4 mb-8">
            <div class="flex items-center gap-3">
                <div class="w-14 h-14 bg-error/10 text-error rounded-xl flex items-center justify-center animate-pulse">
                    <span class="material-symbols-outlined text-[32px]">podcasts</span>
                </div>
                <div>
                    <h3 class="font-headline-lg text-headline-lg text-on-surface font-bold flex items-center gap-4">
                        Live Presensi
                        <span class="bg-surface-container-high text-on-surface px-3 py-1 rounded-lg text-title-md font-mono border border-outline-variant shadow-sm" x-data="{ time: new Date().toLocaleTimeString('id-ID') }" x-init="setInterval(() => time = new Date().toLocaleTimeString('id-ID'), 1000)" x-text="time"></span>
                    </h3>
                    <p class="text-body-md text-secondary mt-1">
                        Ekskul: <strong class="text-on-surface">{{ $session->schedule->extracurricular->name }}</strong> | 
                        Jadwal: {{ \Carbon\Carbon::parse($session->schedule->attendance_start_at)->format('H:i') }} - {{ \Carbon\Carbon::parse($session->schedule->attendance_end_at)->format('H:i') }}
                    </p>
                </div>
            </div>
            
            <div class="flex flex-col sm:flex-row gap-3">
                <a href="{{ route('teacher.attendances.index') }}" class="bg-surface-container-high text-on-surface px-6 py-3 rounded-lg font-label-lg font-bold hover:bg-surface-container-highest transition-all flex items-center gap-2 border border-outline-variant justify-center text-center">
                    <span class="material-symbols-outlined text-[20px]">arrow_back</span> Tutup Halaman (Biarkan Berjalan)
                </a>
                <form action="{{ route('teacher.attendances.live.close', $session->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full bg-error text-white px-6 py-3 rounded-lg font-label-lg font-bold hover:bg-error/90 transition-all flex items-center gap-2 shadow-lg shadow-error/20 justify-center text-center" onclick="return confirm('Apakah Anda yakin ingin mengakhiri sesi presensi ini? Siswa tidak akan bisa scan QR lagi.')">
                        <span class="material-symbols-outlined text-[20px]">stop_circle</span> Akhiri Sesi
                    </button>
                </form>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left: QR Code Section -->
            <div class="lg:col-span-1 space-y-6">
                <div class="bg-white rounded-xl border border-outline-variant card-shadow p-8 flex flex-col items-center justify-center text-center">
                    <h4 class="font-title-lg font-bold text-on-surface mb-2">Scan QR Code</h4>
                    <p class="text-body-sm text-secondary mb-6">Minta siswa memindai QR code ini melalui aplikasi mereka. QR berganti setiap 10 detik.</p>
                    
                    <div class="p-4 bg-white border-2 border-dashed border-outline-variant rounded-xl mb-4 relative">
                        <div id="qr-container" class="w-[250px] h-[250px] flex items-center justify-center text-outline mx-auto">
                            <!-- QR Code will be rendered here by qrcode.js -->
                        </div>
                    </div>
                    
                    <div class="mb-6">
                        <p class="text-label-sm text-secondary">Atau gunakan Kode PIN Manual:</p>
                        <p class="text-display-md font-mono font-bold text-primary tracking-[0.2em] bg-primary/10 px-4 py-2 rounded-xl mt-2 inline-block border border-primary/20" x-text="sessionCode">{{ $session->session_code }}</p>
                    </div>
                    
                    <div class="flex items-center gap-2 bg-surface-container-low px-4 py-2 rounded-full">
                        <span class="material-symbols-outlined text-primary text-[18px]">timer</span>
                        <span class="font-mono font-bold text-primary text-title-md" x-text="timerDisplay">05:00</span>
                    </div>
                    <button type="button" @click="fetchQr()" class="mt-4 text-primary text-label-sm font-bold hover:underline flex items-center gap-1">
                        <span class="material-symbols-outlined text-[16px]">sync</span> Refresh Manual
                    </button>
                </div>
            </div>

            <!-- Right: Realtime Data Table -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl border border-outline-variant card-shadow overflow-hidden flex flex-col h-full min-h-[500px]">
                    <div class="p-4 bg-surface-container-low border-b border-outline-variant flex justify-between items-center">
                        <h4 class="font-title-md font-bold text-on-surface flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary">history_toggle_off</span> 
                            Riwayat Masuk (<span x-text="attendances.length">0</span> Siswa)
                        </h4>
                        <div class="flex items-center gap-2 text-label-sm text-secondary">
                            <span class="w-2 h-2 rounded-full bg-[#10B981] animate-pulse"></span> Live Update
                        </div>
                    </div>
                    
                    <div class="flex-1 overflow-y-auto bg-surface-container-lowest p-0">
                        <table class="w-full text-left border-collapse">
                            <thead class="sticky top-0 bg-surface-container-lowest border-b border-outline-variant z-10">
                                <tr>
                                    <th class="p-4 font-label-md text-secondary">NAMA SISWA</th>
                                    <th class="p-4 font-label-md text-secondary">WAKTU SCAN</th>
                                    <th class="p-4 font-label-md text-secondary">METODE</th>
                                    <th class="p-4 font-label-md text-secondary">STATUS</th>
                                    <th class="p-4 font-label-md text-secondary text-right">AKSI</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-outline-variant">
                                <template x-for="attendance in attendances" :key="attendance.id">
                                    <tr class="hover:bg-surface-container-low transition-colors">
                                        <td class="p-4 font-body-md text-on-surface font-semibold">
                                            <div class="flex flex-col">
                                                <span x-text="attendance.student?.name || 'Siswa tidak ditemukan'"></span>
                                                <span class="text-label-sm text-secondary" x-text="'ID: ' + attendance.student_id"></span>
                                            </div>
                                        </td>
                                        <td class="p-4 font-body-md text-on-surface-variant" x-text="formatTime(attendance.created_at)"></td>
                                        <td class="p-4 font-body-md text-on-surface-variant">
                                            <template x-if="attendance.method === 'selfie'">
                                                <div class="flex items-center gap-2">
                                                    <span class="material-symbols-outlined text-[16px] text-[#F59E0B]">add_a_photo</span>
                                                    <span>Selfie</span>
                                                </div>
                                            </template>
                                            <template x-if="attendance.method === 'qr'">
                                                <div class="flex items-center gap-2">
                                                    <span class="material-symbols-outlined text-[16px] text-primary">qr_code</span>
                                                    <span x-text="'Kode: ' + (attendance.notes || 'Scan QR')"></span>
                                                </div>
                                            </template>
                                            <template x-if="attendance.method === 'manual'">
                                                <div class="flex items-center gap-2">
                                                    <span class="material-symbols-outlined text-[16px] text-secondary">edit_document</span>
                                                    <span x-text="attendance.notes || 'Input Manual'"></span>
                                                </div>
                                            </template>
                                        </td>
                                        <td class="p-4">
                                            <span class="px-2 py-1 rounded-full text-[12px] font-bold uppercase"
                                                  :class="{
                                                    'bg-[#10B981]/10 text-[#10B981]': attendance.status === 'present',
                                                    'bg-error/10 text-error': attendance.status === 'absent' || attendance.status === 'late',
                                                    'bg-tertiary/10 text-tertiary': attendance.status === 'permission' || attendance.status === 'sick'
                                                  }"
                                                  x-text="formatStatus(attendance.status)">
                                            </span>
                                        </td>
                                        <td class="p-4 text-right">
                                            <button type="button" @click="deleteAttendance(attendance.id)" class="px-3 py-1 rounded-full bg-error/10 text-error inline-flex items-center gap-1 hover:bg-error hover:text-white transition-colors text-label-sm font-bold" title="Batalkan/Hapus">
                                                <span class="material-symbols-outlined text-[16px]">cancel</span> Batalkan
                                            </button>
                                        </td>
                                    </tr>
                                </template>
                                <tr x-show="attendances.length === 0">
                                    <td colspan="5" class="p-8 text-center text-secondary">Belum ada siswa yang melakukan presensi.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('liveAttendance', (sessionId) => ({
                sessionId: sessionId,
                sessionCode: '{{ $session->session_code }}',
                attendances: [],
                qrExpiresAt: null,
                qrHash: '------',
                timerDisplay: '00:10',
                timerInterval: null,
                pollingInterval: null,
                qrCodeInstance: null,

                init() {
                    this.$nextTick(() => {
                        this.qrCodeInstance = new QRCode(document.getElementById("qr-container"), {
                            text: "Loading...",
                            width: 250,
                            height: 250,
                            colorDark : "#000000",
                            colorLight : "#ffffff",
                            correctLevel : QRCode.CorrectLevel.H
                        });

                        this.fetchQr();
                        this.fetchData();

                        // Poll data every 5 seconds
                        this.pollingInterval = setInterval(() => {
                            this.fetchData();
                        }, 5000);

                        // Timer countdown every second
                        this.timerInterval = setInterval(() => {
                            this.updateTimer();
                        }, 1000);
                    });
                },

                deleteAttendance(id) {
                    if(!confirm('Apakah Anda yakin ingin membatalkan/menghapus presensi ini?')) return;
                    $.ajax({
                        url: `/guru/attendances/${id}`,
                        method: 'POST',
                        data: {
                            _method: 'DELETE',
                            _token: '{{ csrf_token() }}'
                        },
                        success: (res) => {
                            this.fetchData();
                        },
                        error: (err) => {
                            alert('Gagal membatalkan presensi.');
                        }
                    });
                },

                fetchQr() {
                    $.ajax({
                        url: `/guru/attendances/session/${this.sessionId}/api/qr`,
                        method: 'GET',
                        success: (res) => {
                            this.qrCodeInstance.clear();
                            this.qrCodeInstance.makeCode(res.qr_payload);
                            this.qrHash = res.hash;
                            if (res.session_code) this.sessionCode = res.session_code;
                            this.qrExpiresAt = new Date(res.expires_at).getTime();
                            this.updateTimer();
                        }
                    });
                },

                fetchData() {
                    $.ajax({
                        url: `/guru/attendances/session/${this.sessionId}/api/data`,
                        method: 'GET',
                        success: (res) => {
                            // Check if new data length is different to play sound (optional)
                            this.attendances = res.attendances;
                        }
                    });
                },

                updateTimer() {
                    if (!this.qrExpiresAt) return;
                    
                    const now = new Date().getTime();
                    const distance = this.qrExpiresAt - now;

                    if (distance <= 0) {
                        this.timerDisplay = '00:00';
                        this.fetchQr(); // auto refresh QR when expired
                        return;
                    }

                    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                    this.timerDisplay = String(minutes).padStart(2, '0') + ':' + String(seconds).padStart(2, '0');
                },

                formatTime(dateString) {
                    const date = new Date(dateString);
                    return date.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
                },

                formatStatus(status) {
                    const map = {
                        'present': 'Hadir',
                        'permission': 'Izin',
                        'sick': 'Sakit',
                        'absent': 'Alpa',
                        'late': 'Terlambat'
                    };
                    return map[status] || status.toUpperCase();
                }
            }))
        })
    </script>
    <style>
        .animate-fade-in {
            animation: fadeIn 0.5s ease-in-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; background-color: #E6F4EA; }
            to { opacity: 1; background-color: transparent; }
        }
    </style>
    @endpush
</x-app-layout>
