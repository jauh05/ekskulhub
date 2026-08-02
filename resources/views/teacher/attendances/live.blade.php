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
                        Tanggal: <strong>{{ \Carbon\Carbon::parse($session->schedule->activity_date)->locale('id')->isoFormat('dddd, D MMMM YYYY') }}</strong> |
                        Jadwal: {{ \Carbon\Carbon::parse($session->schedule->attendance_start_at)->format('H:i') }} - {{ \Carbon\Carbon::parse($session->schedule->attendance_end_at)->format('H:i') }} | 
                        <span class="text-error font-bold"><span class="material-symbols-outlined text-[14px] align-middle">timer</span> Ditutup Otomatis Pada: {{ $session->opened_at->addHours(2)->format('H:i') }}</span>
                    </p>
                </div>
            </div>
            
            <div class="flex flex-col sm:flex-row gap-3">
                <a href="{{ route('teacher.attendances.index') }}" class="bg-surface-container-high text-on-surface px-6 py-3 rounded-lg font-label-lg font-bold hover:bg-surface-container-highest transition-all flex items-center gap-2 border border-outline-variant justify-center text-center">
                    <span class="material-symbols-outlined text-[20px]">arrow_back</span> Tutup Halaman (Biarkan Berjalan)
                </a>
                <form id="endSessionForm" action="{{ route('teacher.attendances.live.close', $session->id) }}" method="POST">
                    @csrf
                    <button type="button" @click="showEndSessionModal = true" class="w-full bg-error text-white px-6 py-3 rounded-lg font-label-lg font-bold hover:bg-error/90 transition-all flex items-center gap-2 shadow-lg shadow-error/20 justify-center text-center">
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
                                    <th class="p-4 font-label-md text-secondary">FOTO/BUKTI</th>
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
                                        <td class="p-4">
                                            <template x-if="attendance.selfie_path">
                                                <button type="button" @click="openPhoto('/storage/' + attendance.selfie_path)" class="text-primary hover:underline text-label-sm flex items-center gap-1 bg-primary/10 px-3 py-1 rounded-full font-bold transition-colors hover:bg-primary hover:text-white">
                                                    <span class="material-symbols-outlined text-[16px]">image</span> Selfie
                                                </button>
                                            </template>
                                            <template x-if="attendance.proof_file">
                                                <button type="button" @click="openPhoto('/storage/' + attendance.proof_file)" class="text-primary hover:underline text-label-sm flex items-center gap-1 bg-primary/10 px-3 py-1 rounded-full font-bold transition-colors hover:bg-primary hover:text-white mt-1">
                                                    <span class="material-symbols-outlined text-[16px]">description</span> Bukti
                                                </button>
                                            </template>
                                            <template x-if="!attendance.selfie_path && !attendance.proof_file">
                                                <span class="text-outline text-label-sm">-</span>
                                            </template>
                                        </td>
                                        <td class="p-4 text-right">
                                            <button type="button" @click="openDeleteModal(attendance.id)" class="px-3 py-1 rounded-full bg-error/10 text-error inline-flex items-center gap-1 hover:bg-error hover:text-white transition-colors text-label-sm font-bold" title="Batalkan/Hapus">
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

        <!-- Cancel Modal -->
        <div x-show="showDeleteModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:p-0">
                <div x-show="showDeleteModal" x-transition.opacity class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity" @click="showDeleteModal = false" aria-hidden="true"></div>
                <div x-show="showDeleteModal" x-transition.scale.origin.center class="inline-block align-middle bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:max-w-sm w-full relative z-10">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 text-center">
                        <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-error/10 mb-4">
                            <span class="material-symbols-outlined text-error text-[24px]">cancel</span>
                        </div>
                        <h3 class="text-title-md font-bold text-on-surface mb-2">Batalkan Data Presensi?</h3>
                        <p class="text-body-md text-secondary">Apakah Anda yakin ingin membatalkan dan menghapus data presensi ini? Tindakan ini tidak dapat dikembalikan.</p>
                    </div>
                    <div class="bg-surface-container-low px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-outline-variant">
                        <button type="button" @click="confirmDelete()" class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-error text-base font-medium text-white hover:bg-error/90 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">Batalkan Presensi</button>
                        <button type="button" @click="showDeleteModal = false" class="mt-3 w-full inline-flex justify-center rounded-lg border border-outline-variant shadow-sm px-4 py-2 bg-white text-base font-medium text-secondary hover:bg-surface-container-low focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Tutup</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Photo Modal -->
        <div x-show="showPhotoModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:p-0">
                <div x-show="showPhotoModal" x-transition.opacity class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity" @click="showPhotoModal = false" aria-hidden="true"></div>
                <div x-show="showPhotoModal" x-transition.scale.origin.center class="inline-block align-middle bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:max-w-2xl w-full relative z-10">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 border-b border-outline-variant flex justify-between items-center">
                        <h3 class="text-title-lg font-bold text-on-surface">Bukti FOTO/SELFIE/DOKUMEN</h3>
                        <button type="button" @click="showPhotoModal = false" class="text-on-surface-variant hover:text-error transition-colors"><span class="material-symbols-outlined">close</span></button>
                    </div>
                    <div class="p-4 bg-surface-container-lowest flex justify-center items-center overflow-hidden">
                        <img :src="selectedPhotoUrl" class="max-w-full max-h-[70vh] object-contain rounded-lg shadow-sm" alt="Bukti Kehadiran/Izin">
                    </div>
                    <div class="bg-surface-container-low px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-outline-variant">
                        <button type="button" @click="showPhotoModal = false" class="w-full inline-flex justify-center rounded-lg border border-outline-variant shadow-sm px-4 py-2 bg-white text-base font-medium text-secondary hover:bg-surface-container-low focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">Tutup</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- End Session Modal -->
        <div x-show="showEndSessionModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="showEndSessionModal" x-transition.opacity class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity" @click="showEndSessionModal = false" aria-hidden="true"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div x-show="showEndSessionModal" x-transition.scale.origin.bottom class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full relative z-10">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4 border-b border-outline-variant">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-error/10 sm:mx-0 sm:h-10 sm:w-10">
                                <span class="material-symbols-outlined text-error">stop_circle</span>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-title-lg font-bold text-on-surface" id="modal-title">Akhiri Sesi Presensi</h3>
                                <div class="mt-2">
                                    <p class="text-body-md text-secondary">
                                        Apakah Anda yakin ingin mengakhiri sesi presensi ini? <br><br>
                                        Siswa yang belum melakukan presensi akan otomatis ditandai sebagai <strong class="text-error">Alpa</strong>. Tindakan ini tidak dapat dibatalkan.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-surface-container-low px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-outline-variant">
                        <button type="button" onclick="document.getElementById('endSessionForm').submit()" class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-error text-base font-medium text-white hover:bg-error/90 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">Ya, Akhiri Sesi</button>
                        <button type="button" @click="showEndSessionModal = false" class="mt-3 w-full inline-flex justify-center rounded-lg border border-outline-variant shadow-sm px-4 py-2 bg-white text-base font-medium text-secondary hover:bg-surface-container-low focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Batal</button>
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
                showDeleteModal: false,
                selectedAttendanceId: null,
                showPhotoModal: false,
                selectedPhotoUrl: null,
                showEndSessionModal: false,
                qrExpiresAt: null,
                qrHash: '------',
                timerDisplay: '00:30',
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

                openPhoto(url) {
                    this.selectedPhotoUrl = url;
                    this.showPhotoModal = true;
                },

                openDeleteModal(id) {
                    this.selectedAttendanceId = id;
                    this.showDeleteModal = true;
                },

                confirmDelete() {
                    if (!this.selectedAttendanceId) return;
                    
                    $.ajax({
                        url: `/guru/attendances/${this.selectedAttendanceId}`,
                        method: 'POST',
                        data: {
                            _method: 'DELETE',
                            _token: '{{ csrf_token() }}'
                        },
                        success: (res) => {
                            this.showDeleteModal = false;
                            this.selectedAttendanceId = null;
                            this.fetchData();
                        },
                        error: (err) => {
                            this.showDeleteModal = false;
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
