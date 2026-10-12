<x-app-layout>
    <x-slot name="title">Live Presensi</x-slot>
    <div x-data="liveAttendance('{{ $session->id }}')">
        <div class="space-y-8">
            <!-- Header -->
            <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
                <div>
                    <div class="flex flex-wrap items-center gap-3">
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-error-container px-2.5 py-0.5 text-label-xs font-bold text-on-error-container">
                            <span class="size-1.5 rounded-full bg-error"></span> Live
                        </span>
                        <h3 class="text-headline-md font-bold text-on-surface md:text-headline-lg">Live Presensi</h3>
                        <span class="rounded-lg border border-outline-variant bg-surface-container-high px-3 py-1 font-mono text-title-md text-on-surface" x-data="{ time: new Date().toLocaleTimeString('id-ID') }" x-init="setInterval(() => time = new Date().toLocaleTimeString('id-ID'), 1000)" x-text="time"></span>
                    </div>
                    <p class="mt-2 text-body-md text-secondary">
                        Ekskul: <strong class="text-on-surface">{{ $session->schedule->extracurricular->name }}</strong>
                        <span class="mx-1">&middot;</span>
                        Tanggal: <strong class="text-on-surface">{{ \Carbon\Carbon::parse($session->schedule->activity_date)->locale('id')->isoFormat('dddd, D MMMM YYYY') }}</strong>
                        <span class="mx-1">&middot;</span>
                        Jadwal: {{ \Carbon\Carbon::parse($session->schedule->attendance_start_at)->format('H:i') }} - {{ \Carbon\Carbon::parse($session->schedule->attendance_end_at)->format('H:i') }}
                    </p>
                    <p class="mt-1 inline-flex items-center gap-1.5 text-body-sm font-bold text-error">
                        <x-icon name="timer" class="size-[14px]" /> Ditutup otomatis pada: {{ $session->opened_at->addHours(2)->format('H:i') }}
                    </p>
                </div>

                <div class="flex flex-col gap-3 sm:flex-row">
                    <a href="{{ route('teacher.attendances.index') }}" class="inline-flex w-full items-center justify-center gap-2 rounded-lg border border-outline-variant bg-surface px-4 py-2.5 text-label-lg text-on-surface transition-colors hover:bg-surface-container sm:w-auto" title="Kembali ke Halaman Sebelumnya">
                        <x-icon name="arrow-left" class="size-[20px]" /> Kembali
                    </a>
                    <form action="{{ route('teacher.attendances.live.extend', $session->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-lg bg-primary-container px-4 py-2.5 text-label-lg text-on-primary-container transition-opacity hover:opacity-90 sm:w-auto" onclick="confirmAction(event, 'Perpanjang batas waktu absensi 15 menit?')">
                            <x-icon name="timer" class="size-[20px]" /> Perpanjang 15 Menit
                        </button>
                    </form>
                    <form id="endSessionForm" action="{{ route('teacher.attendances.live.close', $session->id) }}" method="POST">
                        @csrf
                        <button type="button" @click="showEndSessionModal = true" class="inline-flex w-full items-center justify-center gap-2 rounded-lg bg-error px-4 py-2.5 text-label-lg text-on-error transition-opacity hover:opacity-90 sm:w-auto">
                            <x-icon name="circle-stop" class="size-[20px]" /> Akhiri Sesi
                        </button>
                    </form>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
                <!-- Kiri: kartu QR -->
                <div class="lg:col-span-1">
                    <section class="overflow-hidden rounded-xl border border-outline-variant bg-surface">
                        <div class="flex items-center justify-between gap-3 bg-primary-fixed px-5 py-4 text-on-primary-fixed">
                            <div class="flex min-w-0 items-center gap-3">
                                <x-icon name="qr-code" class="size-[20px] shrink-0" />
                                <h4 class="truncate text-title-sm font-bold">Scan QR Code</h4>
                            </div>
                            <span class="inline-flex shrink-0 items-center gap-1.5 rounded-full bg-surface px-3 py-1 font-mono text-label-md font-bold text-on-surface">
                                <x-icon name="timer" class="size-[14px]" />
                                <span x-text="timerDisplay">--:--</span>
                            </span>
                        </div>

                        <div class="flex flex-col items-center gap-5 p-5 text-center sm:p-6">
                            <p class="text-body-sm text-secondary">Minta siswa memindai QR code ini melalui aplikasi mereka. QR berganti setiap 10 detik.</p>

                            <div class="rounded-xl border border-outline-variant bg-surface p-3">
                                <div id="qr-container" class="mx-auto flex h-[250px] w-[250px] items-center justify-center text-outline">
                                    <!-- QR Code will be rendered here by qrcode.js -->
                                </div>
                            </div>

                            <div class="w-full rounded-xl bg-primary-container p-4 text-on-primary-container">
                                <p class="text-label-xs font-bold uppercase tracking-wider opacity-80">Atau gunakan Kode PIN Manual</p>
                                <p class="mt-1 font-mono text-headline-md font-bold tracking-[0.2em]" x-text="sessionCode">{{ $session->session_code }}</p>
                            </div>

                            <button type="button" @click="fetchQr()" class="inline-flex items-center gap-2 rounded-lg border border-outline-variant bg-surface px-4 py-2.5 text-label-lg text-on-surface transition-colors hover:bg-surface-container">
                                <x-icon name="refresh-cw" class="size-[18px]" /> Refresh Manual
                            </button>
                        </div>
                    </section>
                </div>

                <!-- Kanan: tile hitungan dan data realtime -->
                <div class="space-y-6 lg:col-span-2">
                    <div class="grid grid-cols-2 gap-4 xl:grid-cols-4">
                        <div class="flex flex-col justify-between gap-8 rounded-xl bg-primary-container p-5 text-on-primary-container">
                            <div class="flex items-center justify-between gap-3">
                                <span class="text-label-md opacity-90">Total masuk</span>
                                <span class="flex size-9 items-center justify-center rounded-lg bg-on-primary-container/15">
                                    <x-icon name="users" class="size-[18px]" />
                                </span>
                            </div>
                            <p class="text-headline-md font-bold" x-text="attendances.length">0</p>
                        </div>

                        <div class="flex flex-col justify-between gap-8 rounded-xl border border-outline-variant bg-surface p-5">
                            <div class="flex items-center justify-between gap-3">
                                <span class="text-label-md text-secondary">Hadir</span>
                                <span class="flex size-9 items-center justify-center rounded-lg bg-tertiary-container text-on-tertiary-container">
                                    <x-icon name="user-check" class="size-[18px]" />
                                </span>
                            </div>
                            <p class="text-headline-md font-bold text-on-surface" x-text="attendances.filter(a => a.status === 'present').length">0</p>
                        </div>

                        <div class="flex flex-col justify-between gap-8 rounded-xl border border-outline-variant bg-surface p-5">
                            <div class="flex items-center justify-between gap-3">
                                <span class="text-label-md text-secondary">Izin/Sakit</span>
                                <span class="flex size-9 items-center justify-center rounded-lg bg-primary-fixed text-on-primary-fixed">
                                    <x-icon name="thermometer" class="size-[18px]" />
                                </span>
                            </div>
                            <p class="text-headline-md font-bold text-on-surface" x-text="attendances.filter(a => a.status === 'permission' || a.status === 'sick').length">0</p>
                        </div>

                        <div class="flex flex-col justify-between gap-8 rounded-xl border border-outline-variant bg-surface p-5">
                            <div class="flex items-center justify-between gap-3">
                                <span class="text-label-md text-secondary">Terlambat</span>
                                <span class="flex size-9 items-center justify-center rounded-lg bg-warning-container text-on-warning-container">
                                    <x-icon name="clock" class="size-[18px]" />
                                </span>
                            </div>
                            <p class="text-headline-md font-bold text-on-surface" x-text="attendances.filter(a => a.status === 'late').length">0</p>
                        </div>
                    </div>

                    <section class="flex min-h-[420px] flex-col overflow-hidden rounded-xl border border-outline-variant bg-surface">
                        <div class="flex items-center justify-between gap-3 border-b border-outline-variant px-5 py-4">
                            <div class="flex items-center gap-3">
                                <span class="flex size-9 items-center justify-center rounded-lg bg-primary-fixed text-on-primary-fixed">
                                    <x-icon name="history" class="size-[18px]" />
                                </span>
                                <h4 class="text-title-md font-bold text-on-surface">
                                    Riwayat Masuk (<span x-text="attendances.length">0</span> Siswa)
                                </h4>
                            </div>
                            <span class="inline-flex items-center gap-2 text-label-sm text-secondary">
                                <span class="size-2 rounded-full bg-tertiary"></span> Live Update
                            </span>
                        </div>

                        <div class="flex-1 overflow-auto">
                            <table class="w-full border-collapse text-left">
                                <thead class="sticky top-0 z-10 bg-surface-container-low">
                                    <tr class="border-b border-outline-variant">
                                        <th class="whitespace-nowrap px-5 py-3 text-label-sm uppercase tracking-wide text-secondary">NAMA SISWA</th>
                                        <th class="whitespace-nowrap px-5 py-3 text-label-sm uppercase tracking-wide text-secondary">WAKTU SCAN</th>
                                        <th class="whitespace-nowrap px-5 py-3 text-label-sm uppercase tracking-wide text-secondary">METODE</th>
                                        <th class="whitespace-nowrap px-5 py-3 text-label-sm uppercase tracking-wide text-secondary">STATUS</th>
                                        <th class="whitespace-nowrap px-5 py-3 text-label-sm uppercase tracking-wide text-secondary">FOTO/BUKTI</th>
                                        <th class="whitespace-nowrap px-5 py-3 text-right text-label-sm uppercase tracking-wide text-secondary">AKSI</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-outline-variant">
                                    <template x-for="attendance in attendances" :key="attendance.id">
                                        <tr class="transition-colors hover:bg-surface-container-low">
                                            <td class="px-5 py-4 text-body-md font-semibold text-on-surface">
                                                <div class="flex flex-col">
                                                    <span x-text="attendance.student?.name || 'Siswa tidak ditemukan'"></span>
                                                    <span class="text-label-sm text-secondary" x-text="'ID: ' + attendance.student_id"></span>
                                                </div>
                                            </td>
                                            <td class="px-5 py-4 text-body-md text-on-surface-variant" x-text="formatTime(attendance.created_at)"></td>
                                            <td class="px-5 py-4 text-body-md text-on-surface-variant">
                                                <template x-if="attendance.method === 'selfie'">
                                                    <div class="flex items-center gap-2">
                                                        <x-icon name="camera" class="size-[16px] text-warning" />
                                                        <span>Selfie</span>
                                                    </div>
                                                </template>
                                                <template x-if="attendance.method === 'qr'">
                                                    <div class="flex items-center gap-2">
                                                        <x-icon name="qr-code" class="size-[16px] text-primary-container" />
                                                        <span x-text="'Kode: ' + (attendance.notes || 'Scan QR')"></span>
                                                    </div>
                                                </template>
                                                <template x-if="attendance.method === 'manual'">
                                                    <div class="flex items-center gap-2">
                                                        <x-icon name="file-pen" class="size-[16px] text-secondary" />
                                                        <span x-text="attendance.notes || 'Input Manual'"></span>
                                                    </div>
                                                </template>
                                            </td>
                                            <td class="px-5 py-4">
                                                <span class="inline-block rounded-full px-2.5 py-0.5 text-label-xs font-bold uppercase"
                                                      :class="{
                                                        'bg-tertiary-container text-on-tertiary-container': attendance.status === 'present',
                                                        'bg-primary-container text-on-primary-container': attendance.status === 'permission',
                                                        'bg-surface-container-high text-on-surface-variant': attendance.status === 'sick',
                                                        'bg-error-container text-on-error-container': attendance.status === 'absent',
                                                        'bg-warning-container text-on-warning-container': attendance.status === 'late'
                                                      }"
                                                      x-text="formatStatus(attendance.status)">
                                                </span>
                                            </td>
                                            <td class="px-5 py-4">
                                                <template x-if="attendance.selfie_path">
                                                    <button type="button" @click="openPhoto('/storage/' + attendance.selfie_path)" class="inline-flex items-center gap-1 rounded-full bg-primary-fixed px-3 py-1 text-label-sm font-bold text-on-primary-fixed transition-opacity hover:opacity-90">
                                                        <x-icon name="image" class="size-[16px]" /> Selfie
                                                    </button>
                                                </template>
                                                <template x-if="attendance.proof_file">
                                                    <button type="button" @click="openPhoto('/storage/' + attendance.proof_file)" class="mt-1 inline-flex items-center gap-1 rounded-full bg-primary-fixed px-3 py-1 text-label-sm font-bold text-on-primary-fixed transition-opacity hover:opacity-90">
                                                        <x-icon name="file-text" class="size-[16px]" /> Bukti
                                                    </button>
                                                </template>
                                                <template x-if="!attendance.selfie_path && !attendance.proof_file">
                                                    <span class="text-label-sm text-secondary">-</span>
                                                </template>
                                            </td>
                                            <td class="px-5 py-4 text-right">
                                                <button type="button" @click="openDeleteModal(attendance.id)" class="inline-flex items-center gap-1.5 rounded-lg bg-error-container px-3 py-2 text-label-sm font-bold text-on-error-container transition-opacity hover:opacity-90" title="Batalkan/Hapus">
                                                    <x-icon name="circle-x" class="size-[16px]" /> Batalkan
                                                </button>
                                            </td>
                                        </tr>
                                    </template>
                                    <tr x-show="attendances.length === 0">
                                        <td colspan="6" class="px-5 py-8 text-center text-body-md text-secondary">Belum ada siswa yang melakukan presensi.</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </section>
                </div>
            </div>
        </div>

        <!-- Cancel Modal -->
        <div x-show="showDeleteModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex min-h-screen items-center justify-center px-4 pb-20 pt-4 text-center sm:p-0">
                <div x-show="showDeleteModal" x-transition.opacity class="fixed inset-0 bg-on-surface/40 transition-opacity" @click="showDeleteModal = false" aria-hidden="true"></div>
                <div x-show="showDeleteModal" x-transition.scale.origin.center class="relative z-10 inline-block w-full transform overflow-hidden rounded-xl border border-outline-variant bg-surface text-left align-middle shadow-lg transition-all sm:my-8 sm:max-w-sm">
                    <div class="px-5 py-6 text-center sm:px-6">
                        <div class="mx-auto mb-4 flex size-12 items-center justify-center rounded-full bg-error-container">
                            <x-icon name="circle-x" class="size-[24px] text-on-error-container" />
                        </div>
                        <h3 class="mb-2 text-title-md font-bold text-on-surface">Batalkan Data Presensi?</h3>
                        <p class="text-body-md text-secondary">Apakah Anda yakin ingin membatalkan dan menghapus data presensi ini? Tindakan ini tidak dapat dikembalikan.</p>
                    </div>
                    <div class="flex flex-col gap-3 border-t border-outline-variant bg-surface-container-low px-5 py-4 sm:flex-row-reverse sm:px-6">
                        <button type="button" @click="confirmDelete()" class="inline-flex w-full justify-center rounded-lg bg-error px-4 py-2.5 text-label-lg text-on-error transition-opacity hover:opacity-90 focus:outline-none sm:w-auto">Batalkan Presensi</button>
                        <button type="button" @click="showDeleteModal = false" class="inline-flex w-full justify-center rounded-lg border border-outline-variant bg-surface px-4 py-2.5 text-label-lg text-on-surface transition-colors hover:bg-surface-container focus:outline-none sm:w-auto">Tutup</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Photo Modal -->
        <div x-show="showPhotoModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex min-h-screen items-center justify-center px-4 pb-20 pt-4 text-center sm:p-0">
                <div x-show="showPhotoModal" x-transition.opacity class="fixed inset-0 bg-on-surface/40 transition-opacity" @click="showPhotoModal = false" aria-hidden="true"></div>
                <div x-show="showPhotoModal" x-transition.scale.origin.center class="relative z-10 inline-block w-full transform overflow-hidden rounded-xl border border-outline-variant bg-surface text-left align-middle shadow-lg transition-all sm:my-8 sm:max-w-2xl">
                    <div class="flex items-center justify-between border-b border-outline-variant px-5 py-4 sm:px-6">
                        <h3 class="text-title-lg font-bold text-on-surface">Bukti FOTO/SELFIE/DOKUMEN</h3>
                        <button type="button" @click="showPhotoModal = false" class="flex size-9 items-center justify-center rounded-full bg-surface-container-low text-on-surface-variant transition-colors hover:bg-error-container hover:text-on-error-container" aria-label="Tutup"><x-icon name="x" class="size-[18px]" /></button>
                    </div>
                    <div class="flex items-center justify-center overflow-hidden bg-surface-container-lowest p-4">
                        <img :src="selectedPhotoUrl" class="max-h-[70vh] max-w-full rounded-lg object-contain" alt="Bukti Kehadiran/Izin">
                    </div>
                    <div class="flex flex-col gap-3 border-t border-outline-variant bg-surface-container-low px-5 py-4 sm:flex-row-reverse sm:px-6">
                        <button type="button" @click="showPhotoModal = false" class="inline-flex w-full justify-center rounded-lg border border-outline-variant bg-surface px-4 py-2.5 text-label-lg text-on-surface transition-colors hover:bg-surface-container focus:outline-none sm:w-auto">Tutup</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- End Session Modal -->
        <div x-show="showEndSessionModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex min-h-screen items-center justify-center px-4 pb-20 pt-4 text-center sm:p-0">
                <div x-show="showEndSessionModal" x-transition.opacity class="fixed inset-0 bg-on-surface/40 transition-opacity" @click="showEndSessionModal = false" aria-hidden="true"></div>
                <div x-show="showEndSessionModal" x-transition.scale.origin.center class="relative z-10 inline-block w-full transform overflow-hidden rounded-xl border border-outline-variant bg-surface text-left align-middle shadow-lg transition-all sm:my-8 sm:max-w-lg">
                    <div class="flex items-start gap-4 px-5 py-6 sm:px-6">
                        <div class="flex size-12 shrink-0 items-center justify-center rounded-full bg-error-container">
                            <x-icon name="circle-stop" class="size-[24px] text-on-error-container" />
                        </div>
                        <div>
                            <h3 class="text-title-lg font-bold text-on-surface" id="modal-title">Akhiri Sesi Presensi</h3>
                            <p class="mt-2 text-body-md text-secondary">
                                Apakah Anda yakin ingin mengakhiri sesi presensi ini? <br><br>
                                Siswa yang belum melakukan presensi akan otomatis ditandai sebagai <strong class="text-error">Alpa</strong>. Tindakan ini tidak dapat dibatalkan.
                            </p>
                        </div>
                    </div>
                    <div class="flex flex-col gap-3 border-t border-outline-variant bg-surface-container-low px-5 py-4 sm:flex-row-reverse sm:px-6">
                        <button type="button" onclick="document.getElementById('endSessionForm').submit()" class="inline-flex w-full justify-center rounded-lg bg-error px-4 py-2.5 text-label-lg text-on-error transition-opacity hover:opacity-90 focus:outline-none sm:w-auto">Ya, Akhiri Sesi</button>
                        <button type="button" @click="showEndSessionModal = false" class="inline-flex w-full justify-center rounded-lg border border-outline-variant bg-surface px-4 py-2.5 text-label-lg text-on-surface transition-colors hover:bg-surface-container focus:outline-none sm:w-auto">Batal</button>
                    </div>
                </div>
            </div>
        </div>

    </div>

    @push('scripts')
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

                init() {
                    this.$nextTick(() => {
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

                async confirmDelete() {
                    if (!this.selectedAttendanceId) return;

                    const body = new URLSearchParams({
                        _method: 'DELETE',
                        _token: '{{ csrf_token() }}'
                    });

                    try {
                        const response = await fetch(`/guru/attendances/${this.selectedAttendanceId}`, {
                            method: 'POST',
                            headers: { 'Accept': 'application/json' },
                            body
                        });
                        if (!response.ok) throw new Error('Delete failed');
                        this.selectedAttendanceId = null;
                        await this.fetchData();
                    } catch (error) {
                        Swal.fire('Gagal', 'Gagal membatalkan presensi.', 'error');
                    } finally {
                        this.showDeleteModal = false;
                    }
                },

                async fetchQr() {
                    const response = await fetch(`/guru/attendances/session/${this.sessionId}/api/qr`, {
                        headers: { 'Accept': 'application/json' }
                    });
                    if (!response.ok) return;

                    const res = await response.json();
                    const container = document.getElementById('qr-container');
                    container.replaceChildren();
                    const canvas = document.createElement('canvas');
                    container.appendChild(canvas);
                    await QRCodeGenerator.toCanvas(canvas, res.qr_payload, {
                        width: 250,
                        margin: 1,
                        color: { dark: '#000000', light: '#ffffff' },
                        errorCorrectionLevel: 'H'
                    });
                    this.qrHash = res.hash;
                    if (res.session_code) this.sessionCode = res.session_code;
                    this.qrExpiresAt = new Date(res.expires_at).getTime();
                    this.updateTimer();
                },

                async fetchData() {
                    const response = await fetch(`/guru/attendances/session/${this.sessionId}/api/data`, {
                        headers: { 'Accept': 'application/json' }
                    });
                    if (!response.ok) return;
                    const res = await response.json();
                    this.attendances = res.attendances;
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
    @endpush
</x-app-layout>
