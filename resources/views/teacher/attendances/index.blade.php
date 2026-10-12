<x-app-layout>
    <x-slot name="title">Data Absensi</x-slot>
    <div x-data="{ showRunModal: false }">
    <div class="space-y-8">
        @if(isset($activeSession) && $activeSession)
        <div class="flex flex-col gap-4 rounded-xl border border-outline-variant bg-primary-fixed p-5 text-on-primary-fixed sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center gap-3">
                <span class="flex size-10 shrink-0 items-center justify-center rounded-lg bg-surface text-primary-container">
                    <x-icon name="radio" class="size-[20px]" />
                </span>
                <div>
                    <h4 class="text-title-md font-bold text-on-primary-fixed">Sesi Presensi Sedang Berjalan</h4>
                    <p class="text-body-sm text-on-surface-variant">Anda memiliki sesi live presensi yang belum ditutup.</p>
                </div>
            </div>
            <a href="{{ route('teacher.attendances.live', $activeSession->id) }}" class="inline-flex items-center justify-center gap-2 rounded-lg bg-primary-container px-4 py-2.5 text-label-lg text-on-primary-container transition-opacity hover:opacity-90">Buka Kembali Halaman Live</a>
        </div>
        @endif

        <!-- Header -->
        <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
            <div>
                <p class="text-body-md text-secondary">Pantau kehadiran siswa di setiap sesi ekstrakurikuler.</p>
                <h3 class="mt-1 text-headline-md font-bold text-on-surface md:text-headline-lg">Data Absensi</h3>
            </div>

            <form action="{{ route('teacher.attendances.index') }}" method="GET" class="flex flex-col gap-2 sm:flex-row sm:flex-wrap sm:items-center">
                <input type="date" name="date" value="{{ request('date') }}" class="w-full rounded-lg border border-outline-variant bg-surface px-3 py-2.5 text-body-md text-on-surface focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20 sm:w-auto">
                <select name="ekskul_id" class="w-full rounded-lg border border-outline-variant bg-surface px-3 py-2.5 text-body-md text-on-surface focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20 sm:w-auto sm:min-w-[180px]">
                    <option value="">Semua Ekskul</option>
                    @foreach($ekskuls as $ek)
                        <option value="{{ $ek->id }}" {{ request('ekskul_id') == $ek->id ? 'selected' : '' }}>
                            {{ $ek->name }}
                        </option>
                    @endforeach
                </select>
                <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-lg border border-outline-variant bg-surface px-4 py-2.5 text-label-lg text-on-surface transition-colors hover:bg-surface-container sm:w-auto">
                    <x-icon name="list-filter" class="size-[20px]" /> Terapkan Filter
                </button>
                <button type="button" @click="showRunModal = true" class="inline-flex w-full items-center justify-center gap-2 rounded-lg bg-primary-container px-4 py-2.5 text-label-lg text-on-primary-container transition-opacity hover:opacity-90 sm:w-auto">
                    <x-icon name="circle-play" class="size-[20px]" /> Jalankan Presensi
                </button>
            </form>
        </div>

        <!-- Statistik -->
        <div class="grid grid-cols-2 gap-4 lg:grid-cols-4">
            <div class="flex flex-col justify-between gap-8 rounded-xl bg-primary-container p-5 text-on-primary-container">
                <div class="flex items-center justify-between gap-3">
                    <span class="text-label-md opacity-90">% Hadir</span>
                    <span class="flex size-9 items-center justify-center rounded-lg bg-on-primary-container/15">
                        <x-icon name="clipboard-check" class="size-[18px]" />
                    </span>
                </div>
                <p class="text-headline-md font-bold">{{ $persentaseHadir }}%</p>
            </div>

            <div class="flex flex-col justify-between gap-8 rounded-xl border border-outline-variant bg-surface p-5">
                <div class="flex items-center justify-between gap-3">
                    <span class="text-label-md text-secondary">Hadir</span>
                    <span class="flex size-9 items-center justify-center rounded-lg bg-tertiary-container text-on-tertiary-container">
                        <x-icon name="user-check" class="size-[18px]" />
                    </span>
                </div>
                <p class="text-headline-md font-bold text-on-surface">{{ $totalHadir }}</p>
            </div>

            <div class="flex flex-col justify-between gap-8 rounded-xl border border-outline-variant bg-surface p-5">
                <div class="flex items-center justify-between gap-3">
                    <span class="text-label-md text-secondary">Sakit/Izin</span>
                    <span class="flex size-9 items-center justify-center rounded-lg bg-warning-container text-on-warning-container">
                        <x-icon name="thermometer" class="size-[18px]" />
                    </span>
                </div>
                <p class="text-headline-md font-bold text-on-surface">{{ $totalSakit + $totalIzin }}</p>
            </div>

            <div class="flex flex-col justify-between gap-8 rounded-xl border border-outline-variant bg-surface p-5">
                <div class="flex items-center justify-between gap-3">
                    <span class="text-label-md text-secondary">Alpa</span>
                    <span class="flex size-9 items-center justify-center rounded-lg bg-error-container text-on-error-container">
                        <x-icon name="user-x" class="size-[18px]" />
                    </span>
                </div>
                <p class="text-headline-md font-bold text-on-surface">{{ $totalAlpa }}</p>
            </div>
        </div>

        <!-- Riwayat Sesi Presensi -->
        <section x-data="{
            showEditSessionModal: false,
            showDeleteSessionModal: false,
            selectedSession: {},

            openEditSession(sess) {
                this.selectedSession = sess;
                this.showEditSessionModal = true;
            },
            openDeleteSession(sess) {
                this.selectedSession = sess;
                this.showDeleteSessionModal = true;
            }
        }" class="overflow-hidden rounded-xl border border-outline-variant bg-surface">
            <div class="flex items-center justify-between gap-3 border-b border-outline-variant px-5 py-4">
                <div class="flex items-center gap-3">
                    <span class="flex size-9 items-center justify-center rounded-lg bg-primary-fixed text-on-primary-fixed">
                        <x-icon name="list" class="size-[18px]" />
                    </span>
                    <h4 class="text-title-md font-bold text-on-surface">Riwayat Sesi Presensi</h4>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full border-collapse text-left">
                    <thead>
                        <tr class="bg-surface-container-low">
                            <th class="whitespace-nowrap px-5 py-3 text-label-sm uppercase tracking-wide text-secondary">TANGGAL & WAKTU</th>
                            <th class="whitespace-nowrap px-5 py-3 text-label-sm uppercase tracking-wide text-secondary">EKSKUL</th>
                            <th class="whitespace-nowrap px-5 py-3 text-label-sm uppercase tracking-wide text-secondary">STATUS</th>
                            <th class="whitespace-nowrap px-5 py-3 text-label-sm uppercase tracking-wide text-secondary">JUMLAH HADIR</th>
                            <th class="whitespace-nowrap px-5 py-3 text-label-sm uppercase tracking-wide text-secondary">AKSI</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant">
                        @forelse($attendanceSessions as $sess)
                        <tr class="transition-colors hover:bg-surface-container-low">
                            <td class="px-5 py-4 text-body-md text-on-surface-variant">
                                <span class="font-bold text-on-surface">{{ \Carbon\Carbon::parse($sess->schedule->activity_date)->format('d M Y') }}</span><br>
                                <span class="text-label-sm text-secondary">{{ \Carbon\Carbon::parse($sess->schedule->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($sess->schedule->end_time)->format('H:i') }}</span>
                            </td>
                            <td class="px-5 py-4 text-body-md font-semibold text-on-surface">{{ $sess->schedule->extracurricular->name ?? '-' }}</td>
                            <td class="px-5 py-4">
                                @if($sess->status == 'open')
                                    <span class="inline-flex w-max items-center gap-1.5 rounded-full bg-tertiary-container px-2.5 py-0.5 text-label-xs font-bold text-on-tertiary-container">
                                        <span class="h-1.5 w-1.5 rounded-full bg-tertiary"></span> Berjalan
                                    </span>
                                @else
                                    <span class="inline-block rounded-full bg-surface-container-high px-2.5 py-0.5 text-label-xs font-bold text-on-surface-variant">Selesai</span>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-body-md font-bold text-on-surface">
                                {{ $sess->attendances_count }} Siswa
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex flex-wrap items-center gap-2">
                                    <a href="{{ route('teacher.attendances.index', ['session_id' => $sess->id]) }}" class="inline-flex size-9 items-center justify-center rounded-lg bg-primary-fixed text-on-primary-fixed transition-opacity hover:opacity-90" title="Lihat Absensi" aria-label="Lihat Absensi">
                                        <x-icon name="eye" class="size-[18px]" />
                                    </a>
                                    @if($sess->status == 'closed')
                                        <form action="{{ route('teacher.attendances.session.update', $sess->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="activity_date" value="{{ \Carbon\Carbon::parse($sess->schedule->activity_date)->format('Y-m-d') }}">
                                            <input type="hidden" name="start_time" value="{{ \Carbon\Carbon::parse($sess->schedule->start_time)->format('H:i') }}">
                                            <input type="hidden" name="end_time" value="{{ now()->addHours(1)->format('H:i') }}">
                                            <input type="hidden" name="status" value="open">
                                            <button type="submit" class="inline-flex items-center gap-1.5 rounded-lg bg-tertiary-container px-3 py-2 text-label-sm font-bold text-on-tertiary-container transition-opacity hover:opacity-90" title="Buka Kembali" onclick="confirmAction(event, 'Membuka kembali sesi akan memperpanjang waktu selesai 1 jam dari sekarang. Lanjutkan?')">
                                                <x-icon name="lock-open" class="size-[16px]" /> Buka
                                            </button>
                                        </form>
                                    @else
                                        <a href="{{ route('teacher.attendances.live', $sess->id) }}" class="inline-flex items-center gap-1.5 rounded-lg bg-primary-container px-3 py-2 text-label-sm font-bold text-on-primary-container transition-opacity hover:opacity-90" title="Live QR">
                                            <x-icon name="scan-qr-code" class="size-[16px]" /> Live
                                        </a>
                                    @endif
                                    <button @click="openEditSession({{ json_encode($sess) }})" class="inline-flex size-9 items-center justify-center rounded-lg bg-warning-container text-on-warning-container transition-opacity hover:opacity-90" title="Edit Sesi" aria-label="Edit Sesi">
                                        <x-icon name="pencil" class="size-[16px]" />
                                    </button>
                                    <button @click="openDeleteSession({{ json_encode($sess) }})" class="inline-flex size-9 items-center justify-center rounded-lg bg-error-container text-on-error-container transition-opacity hover:opacity-90" title="Hapus Sesi" aria-label="Hapus Sesi">
                                        <x-icon name="trash-2" class="size-[16px]" />
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-5 py-8 text-center text-body-md text-on-surface-variant">Belum ada data sesi presensi</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="border-t border-outline-variant px-5 py-4">
                {{ $attendanceSessions->links() }}
            </div>

            <!-- Edit Session Modal -->
            <div x-show="showEditSessionModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                <div class="flex min-h-screen items-center justify-center px-4 pb-20 pt-4 text-center sm:p-0">
                    <div x-show="showEditSessionModal" x-transition.opacity class="fixed inset-0 bg-on-surface/40 transition-opacity" @click="showEditSessionModal = false" aria-hidden="true"></div>
                    <div x-show="showEditSessionModal" x-transition.scale.origin.center class="relative z-10 inline-block w-full transform overflow-hidden rounded-xl border border-outline-variant bg-surface text-left align-middle shadow-lg transition-all sm:my-8 sm:max-w-lg">
                        <div class="border-b border-outline-variant px-5 py-4 sm:px-6">
                            <h3 class="text-title-lg font-bold text-on-surface">Edit Sesi Presensi</h3>
                        </div>
                        <form :action="'{{ route('teacher.attendances.session.update', 'ID_PLACEHOLDER') }}'.replace('ID_PLACEHOLDER', selectedSession?.id)" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="space-y-4 px-5 py-5 sm:px-6">
                                <div>
                                    <label class="mb-1.5 block text-label-md text-on-surface-variant">Tanggal Kegiatan</label>
                                    <input type="date" name="activity_date" class="w-full rounded-lg border border-outline-variant bg-surface px-3 py-2.5 text-body-md text-on-surface focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20" :value="selectedSession?.schedule?.activity_date ? selectedSession.schedule.activity_date.split(' ')[0] : ''" required>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="mb-1.5 block text-label-md text-on-surface-variant">Waktu Mulai</label>
                                        <input type="time" name="start_time" class="w-full rounded-lg border border-outline-variant bg-surface px-3 py-2.5 text-body-md text-on-surface focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20" :value="selectedSession?.schedule?.start_time ? selectedSession.schedule.start_time.substring(0, 5) : ''" required>
                                    </div>
                                    <div>
                                        <label class="mb-1.5 block text-label-md text-on-surface-variant">Waktu Selesai</label>
                                        <input type="time" name="end_time" class="w-full rounded-lg border border-outline-variant bg-surface px-3 py-2.5 text-body-md text-on-surface focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20" :value="selectedSession?.schedule?.end_time ? selectedSession.schedule.end_time.substring(0, 5) : ''" required>
                                    </div>
                                </div>
                                <div>
                                    <label class="mb-1.5 block text-label-md text-on-surface-variant">Status Sesi</label>
                                    <select name="status" class="w-full rounded-lg border border-outline-variant bg-surface px-3 py-2.5 text-body-md text-on-surface focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20" x-model="selectedSession.status" required>
                                        <option value="open">Berjalan (Open)</option>
                                        <option value="closed">Selesai (Closed)</option>
                                    </select>
                                    <p class="mt-1 text-body-sm text-secondary">Jika Anda mengubah status menjadi "Berjalan", Anda bisa membuka kembali halaman QR Code di menu utama.</p>
                                </div>
                            </div>
                            <div class="flex flex-col gap-3 border-t border-outline-variant bg-surface-container-low px-5 py-4 sm:flex-row-reverse sm:px-6">
                                <button type="submit" class="inline-flex w-full justify-center rounded-lg bg-primary-container px-4 py-2.5 text-label-lg text-on-primary-container transition-opacity hover:opacity-90 focus:outline-none sm:w-auto">Simpan Perubahan</button>
                                <button type="button" @click="showEditSessionModal = false" class="inline-flex w-full justify-center rounded-lg border border-outline-variant bg-surface px-4 py-2.5 text-label-lg text-on-surface transition-colors hover:bg-surface-container focus:outline-none sm:w-auto">Batal</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Delete Session Modal -->
            <div x-show="showDeleteSessionModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                <div class="flex min-h-screen items-center justify-center px-4 pb-20 pt-4 text-center sm:p-0">
                    <div x-show="showDeleteSessionModal" x-transition.opacity class="fixed inset-0 bg-on-surface/40 transition-opacity" @click="showDeleteSessionModal = false" aria-hidden="true"></div>
                    <div x-show="showDeleteSessionModal" x-transition.scale.origin.center class="relative z-10 inline-block w-full transform overflow-hidden rounded-xl border border-outline-variant bg-surface text-left align-middle shadow-lg transition-all sm:my-8 sm:max-w-md">
                        <div class="px-5 py-6 text-center sm:px-6">
                            <div class="mx-auto mb-4 flex size-12 items-center justify-center rounded-full bg-error-container">
                                <x-icon name="triangle-alert" class="size-[24px] text-on-error-container" />
                            </div>
                            <h3 class="mb-2 text-title-md font-bold text-on-surface">Hapus Sesi Presensi?</h3>
                            <p class="text-body-md text-secondary">Apakah Anda yakin ingin menghapus sesi presensi ini? <br><br> <span class="font-bold text-error">Perhatian:</span> Menghapus sesi akan ikut <strong>MENGHAPUS SEMUA DATA KEHADIRAN SISWA</strong> yang terekam pada sesi tersebut secara permanen!</p>
                        </div>
                        <form :action="'{{ route('teacher.attendances.session.destroy', 'ID_PLACEHOLDER') }}'.replace('ID_PLACEHOLDER', selectedSession?.id)" method="POST">
                            @csrf
                            @method('DELETE')
                            <div class="flex flex-col gap-3 border-t border-outline-variant bg-surface-container-low px-5 py-4 sm:flex-row-reverse sm:px-6">
                                <button type="submit" class="inline-flex w-full justify-center rounded-lg bg-error px-4 py-2.5 text-label-lg text-on-error transition-opacity hover:opacity-90 focus:outline-none sm:w-auto">Ya, Hapus Semua</button>
                                <button type="button" @click="showDeleteSessionModal = false" class="inline-flex w-full justify-center rounded-lg border border-outline-variant bg-surface px-4 py-2.5 text-label-lg text-on-surface transition-colors hover:bg-surface-container focus:outline-none sm:w-auto">Batal</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>

        @if(request()->filled('session_id'))
        <div class="flex flex-wrap items-center justify-between gap-3 rounded-xl border border-outline-variant bg-primary-fixed px-5 py-3 text-on-primary-fixed">
            <div class="flex items-center gap-2 text-body-md font-semibold">
                <x-icon name="funnel" class="size-[18px]" /> Menampilkan daftar absen khusus untuk sesi yang dipilih.
            </div>
            <a href="{{ route('teacher.attendances.index') }}" class="text-label-md font-bold text-primary-container hover:underline">Reset Filter</a>
        </div>
        @endif

        <!-- Riwayat Presensi -->
        <section x-data="{
            showAddModal: false,
            showEditModal: false,
            showDeleteModal: false,
            showDetailModal: false,
            showPhotoModal: false,
            selectedAttendance: {},
            selectedPhotoUrl: null,

            openEdit(att) {
                this.selectedAttendance = att;
                this.showEditModal = true;
            },
            openDelete(att) {
                this.selectedAttendance = att;
                this.showDeleteModal = true;
            },
            openDetail(att) {
                this.selectedAttendance = att;
                this.showDetailModal = true;
            },
            openPhoto(url) {
                this.selectedPhotoUrl = url;
                this.showPhotoModal = true;
            }
        }" class="overflow-hidden rounded-xl border border-outline-variant bg-surface">
            <div class="flex flex-wrap items-center justify-between gap-3 border-b border-outline-variant px-5 py-4">
                <div class="flex items-center gap-3">
                    <span class="flex size-9 items-center justify-center rounded-lg bg-primary-fixed text-on-primary-fixed">
                        <x-icon name="table" class="size-[18px]" />
                    </span>
                    <h4 class="text-title-md font-bold text-on-surface">Riwayat Presensi</h4>
                </div>
                <button @click="showAddModal = true" class="inline-flex items-center gap-2 rounded-lg bg-primary-container px-4 py-2.5 text-label-lg text-on-primary-container transition-opacity hover:opacity-90">
                    <x-icon name="plus" class="size-[18px]" /> Tambah Presensi
                </button>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full border-collapse text-left">
                    <thead>
                        <tr class="bg-surface-container-low">
                            <th class="whitespace-nowrap px-5 py-3 text-label-sm uppercase tracking-wide text-secondary">TANGGAL & WAKTU PRESENSI</th>
                            <th class="whitespace-nowrap px-5 py-3 text-label-sm uppercase tracking-wide text-secondary">NAMA SISWA</th>
                            <th class="whitespace-nowrap px-5 py-3 text-label-sm uppercase tracking-wide text-secondary">EKSKUL</th>
                            <th class="whitespace-nowrap px-5 py-3 text-label-sm uppercase tracking-wide text-secondary">STATUS</th>
                            <th class="whitespace-nowrap px-5 py-3 text-label-sm uppercase tracking-wide text-secondary">FOTO/SELFIE/BUKTI IZIN</th>
                            <th class="whitespace-nowrap px-5 py-3 text-label-sm uppercase tracking-wide text-secondary">AKSI/VERIFIKASI</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant">
                        @forelse($attendances as $att)
                        <tr class="transition-colors hover:bg-surface-container-low">
                            <td class="px-5 py-4 text-body-md text-on-surface-variant">
                                <span class="font-bold text-on-surface">{{ \Carbon\Carbon::parse($att->attendanceSession->schedule->activity_date)->format('d M Y') }}</span>
                                @if($att->created_at)
                                    <br><span class="inline-flex items-center gap-1 text-label-sm font-medium text-secondary"><x-icon name="clock" class="align-middle size-[14px]" /> {{ \Carbon\Carbon::parse($att->created_at)->format('H:i') }}</span>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-body-md font-semibold text-on-surface">{{ $att->student->name ?? '-' }}</td>
                            <td class="px-5 py-4 text-body-md text-on-surface-variant">{{ $att->attendanceSession->schedule->extracurricular->name ?? '-' }}</td>
                            <td class="px-5 py-4">
                                @if($att->status == 'present')
                                    <span class="inline-block rounded-full bg-tertiary-container px-2.5 py-0.5 text-label-xs font-bold text-on-tertiary-container">Hadir</span>
                                @elseif($att->status == 'absent')
                                    <span class="inline-block rounded-full bg-error-container px-2.5 py-0.5 text-label-xs font-bold text-on-error-container">Alpa</span>
                                @elseif($att->status == 'sick')
                                    <span class="inline-block rounded-full bg-surface-container-high px-2.5 py-0.5 text-label-xs font-bold text-on-surface-variant">Sakit</span>
                                @else
                                    <span class="inline-block rounded-full bg-primary-container px-2.5 py-0.5 text-label-xs font-bold text-on-primary-container">Izin</span>
                                @endif
                                @if($att->is_verified_by_teacher)
                                    <x-icon name="badge-check" class="ml-1 align-middle text-tertiary tooltip size-[14px]" />
                                @endif
                            </td>
                            <td class="px-5 py-4">
                                @if($att->selfie_path)
                                    <button type="button" @click="openPhoto('{{ Storage::url($att->selfie_path) }}')" class="inline-flex items-center gap-1 rounded-full bg-primary-fixed px-3 py-1 text-label-sm font-bold text-on-primary-fixed transition-opacity hover:opacity-90">
                                        <x-icon name="image" class="size-[16px]" /> Selfie
                                    </button>
                                @elseif($att->proof_file)
                                    <button type="button" @click="openPhoto('{{ Storage::url($att->proof_file) }}')" class="inline-flex items-center gap-1 rounded-full bg-primary-fixed px-3 py-1 text-label-sm font-bold text-on-primary-fixed transition-opacity hover:opacity-90">
                                        <x-icon name="file-text" class="size-[16px]" /> Bukti Izin
                                    </button>
                                @else
                                    <span class="text-label-sm text-secondary">-</span>
                                @endif
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex flex-wrap items-center gap-2">
                                    <button @click="openDetail({{ json_encode($att) }})" class="inline-flex items-center gap-1.5 rounded-lg bg-primary-fixed px-3 py-2 text-label-sm font-bold text-on-primary-fixed transition-opacity hover:opacity-90" title="Detail">
                                        <x-icon name="eye" class="size-[16px]" /> Detail
                                    </button>
                                    <button @click="openEdit({{ json_encode($att) }})" class="inline-flex items-center gap-1.5 rounded-lg bg-warning-container px-3 py-2 text-label-sm font-bold text-on-warning-container transition-opacity hover:opacity-90" title="Edit">
                                        <x-icon name="pencil" class="size-[16px]" /> Edit
                                    </button>
                                    <button @click="openDelete({{ json_encode($att) }})" class="inline-flex items-center gap-1.5 rounded-lg bg-error-container px-3 py-2 text-label-sm font-bold text-on-error-container transition-opacity hover:opacity-90" title="Batalkan/Hapus">
                                        <x-icon name="circle-x" class="size-[16px]" /> Batalkan
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-5 py-8 text-center text-body-md text-on-surface-variant">Belum ada data kehadiran</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="border-t border-outline-variant px-5 py-4">
                {{ $attendances->links() }}
            </div>

            <!-- Add Modal -->
            <div x-show="showAddModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                <div class="flex min-h-screen items-center justify-center px-4 pb-20 pt-4 text-center sm:p-0">
                    <div x-show="showAddModal" x-transition.opacity class="fixed inset-0 bg-on-surface/40 transition-opacity" @click="showAddModal = false" aria-hidden="true"></div>
                    <div x-show="showAddModal" x-transition.scale.origin.center class="relative z-10 inline-block w-full transform overflow-hidden rounded-xl border border-outline-variant bg-surface text-left align-middle shadow-lg transition-all sm:my-8 sm:max-w-lg">
                        <div class="border-b border-outline-variant px-5 py-4 sm:px-6">
                            <h3 class="text-title-lg font-bold text-on-surface">Tambah Presensi Manual</h3>
                        </div>
                        <form action="{{ route('teacher.attendances.store') }}" method="POST">
                            @csrf
                            <div class="space-y-4 px-5 py-5 sm:px-6">
                                <div>
                                    <label class="mb-1.5 block text-label-md text-on-surface-variant">Jadwal</label>
                                    <select name="schedule_id" class="w-full rounded-lg border border-outline-variant bg-surface px-3 py-2.5 text-body-md text-on-surface focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20" required>
                                        <option value="">Pilih Jadwal...</option>
                                        @foreach($allSchedules as $sch)
                                            <option value="{{ $sch->id }}">{{ $sch->extracurricular->name }} - {{ \Carbon\Carbon::parse($sch->activity_date)->format('d M Y') }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div x-data="{
                                    open: false,
                                    search: '',
                                    selectedValue: '',
                                    selectedText: 'Pilih Siswa...',
                                    options: [
                                        @foreach($activeStudents as $reg)
                                            { id: '{{ $reg->student->id }}', text: '{{ addslashes($reg->student->name) }} ({{ addslashes($reg->extracurricular->name) }})' },
                                        @endforeach
                                    ],
                                    get filteredOptions() {
                                        if (this.search === '') {
                                            return this.options;
                                        }
                                        return this.options.filter(opt => opt.text.toLowerCase().includes(this.search.toLowerCase()));
                                    },
                                    selectOption(opt) {
                                        this.selectedValue = opt.id;
                                        this.selectedText = opt.text;
                                        this.open = false;
                                        this.search = '';
                                    }
                                }" class="relative" @click.away="open = false">
                                    <label class="mb-1.5 block text-label-md text-on-surface-variant">Siswa</label>
                                    <input type="hidden" name="student_id" :value="selectedValue" required>
                                    <button type="button" @click="open = !open" class="flex w-full items-center justify-between gap-2 rounded-lg border border-outline-variant bg-surface px-3 py-2.5 text-left text-body-md focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
                                        <span x-text="selectedText" :class="selectedValue === '' ? 'text-secondary' : 'text-on-surface'"></span>
                                        <x-icon name="chevron-down" class="size-[20px] text-outline" />
                                    </button>

                                    <div x-show="open" x-transition style="display: none;" class="absolute z-50 mt-1 flex max-h-60 w-full flex-col rounded-lg border border-outline-variant bg-surface shadow-md">
                                        <div class="sticky top-0 z-10 rounded-t-lg border-b border-outline-variant bg-surface p-2">
                                            <input type="text" x-model="search" placeholder="Cari nama siswa..." class="w-full rounded-lg border border-outline-variant bg-surface-container-low px-3 py-2 text-body-md text-on-surface placeholder:text-outline focus:border-primary focus:bg-surface focus:outline-none focus:ring-2 focus:ring-primary/20" @click.stop>
                                        </div>
                                        <ul class="flex-1 overflow-y-auto overflow-x-hidden p-1">
                                            <li x-show="filteredOptions.length === 0" class="px-3 py-2 text-center text-body-md text-secondary">Siswa tidak ditemukan</li>
                                            <template x-for="opt in filteredOptions" :key="opt.id">
                                                <li @click="selectOption(opt)" class="cursor-pointer rounded-md px-3 py-2 text-body-md text-on-surface hover:bg-primary/10" :class="{'bg-primary-fixed font-semibold text-on-primary-fixed': selectedValue === opt.id}" x-text="opt.text"></li>
                                            </template>
                                        </ul>
                                    </div>
                                </div>
                                <div>
                                    <label class="mb-1.5 block text-label-md text-on-surface-variant">Status</label>
                                    <select name="status" class="w-full rounded-lg border border-outline-variant bg-surface px-3 py-2.5 text-body-md text-on-surface focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20" required>
                                        <option value="present">Hadir</option>
                                        <option value="sick">Sakit</option>
                                        <option value="permission">Izin</option>
                                        <option value="absent">Alpa</option>
                                        <option value="late">Terlambat</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="mb-1.5 block text-label-md text-on-surface-variant">Catatan Tambahan (Opsional)</label>
                                    <textarea name="notes" class="w-full rounded-lg border border-outline-variant bg-surface px-3 py-2.5 text-body-md text-on-surface placeholder:text-outline focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20" rows="2" placeholder="Tulis catatan jika diperlukan..."></textarea>
                                </div>
                            </div>
                            <div class="flex flex-col gap-3 border-t border-outline-variant bg-surface-container-low px-5 py-4 sm:flex-row-reverse sm:px-6">
                                <button type="submit" class="inline-flex w-full justify-center rounded-lg bg-primary-container px-4 py-2.5 text-label-lg text-on-primary-container transition-opacity hover:opacity-90 focus:outline-none sm:w-auto">Simpan</button>
                                <button type="button" @click="showAddModal = false" class="inline-flex w-full justify-center rounded-lg border border-outline-variant bg-surface px-4 py-2.5 text-label-lg text-on-surface transition-colors hover:bg-surface-container focus:outline-none sm:w-auto">Batal</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Edit Modal -->
            <div x-show="showEditModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                <div class="flex min-h-screen items-center justify-center px-4 pb-20 pt-4 text-center sm:p-0">
                    <div x-show="showEditModal" x-transition.opacity class="fixed inset-0 bg-on-surface/40 transition-opacity" @click="showEditModal = false" aria-hidden="true"></div>
                    <div x-show="showEditModal" x-transition.scale.origin.center class="relative z-10 inline-block w-full transform overflow-hidden rounded-xl border border-outline-variant bg-surface text-left align-middle shadow-lg transition-all sm:my-8 sm:max-w-lg">
                        <div class="border-b border-outline-variant px-5 py-4 sm:px-6">
                            <h3 class="text-title-lg font-bold text-on-surface">Edit Presensi</h3>
                            <p class="mt-1 text-body-sm text-secondary" x-text="selectedAttendance?.student?.name"></p>
                        </div>
                        <form :action="'{{ route('teacher.attendances.update', 'ID_PLACEHOLDER') }}'.replace('ID_PLACEHOLDER', selectedAttendance?.id)" method="POST">
                            @csrf
                            @method('PATCH')
                            <div class="space-y-4 px-5 py-5 sm:px-6">
                                <div>
                                    <label class="mb-1.5 block text-label-md text-on-surface-variant">Status</label>
                                    <select name="status" class="w-full rounded-lg border border-outline-variant bg-surface px-3 py-2.5 text-body-md text-on-surface focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20" x-model="selectedAttendance.status" required>
                                        <option value="present">Hadir</option>
                                        <option value="sick">Sakit</option>
                                        <option value="permission">Izin</option>
                                        <option value="absent">Alpa</option>
                                        <option value="late">Terlambat</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="mb-1.5 block text-label-md text-on-surface-variant">Catatan Tambahan (Opsional)</label>
                                    <textarea name="notes" class="w-full rounded-lg border border-outline-variant bg-surface px-3 py-2.5 text-body-md text-on-surface placeholder:text-outline focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20" rows="2" x-model="selectedAttendance.notes" placeholder="Tulis catatan jika diperlukan..."></textarea>
                                </div>
                            </div>
                            <div class="flex flex-col gap-3 border-t border-outline-variant bg-surface-container-low px-5 py-4 sm:flex-row-reverse sm:px-6">
                                <button type="submit" class="inline-flex w-full justify-center rounded-lg bg-primary-container px-4 py-2.5 text-label-lg text-on-primary-container transition-opacity hover:opacity-90 focus:outline-none sm:w-auto">Simpan</button>
                                <button type="button" @click="showEditModal = false" class="inline-flex w-full justify-center rounded-lg border border-outline-variant bg-surface px-4 py-2.5 text-label-lg text-on-surface transition-colors hover:bg-surface-container focus:outline-none sm:w-auto">Batal</button>
                            </div>
                        </form>
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
                            <p class="text-body-md text-secondary">Apakah Anda yakin ingin membatalkan dan menghapus data presensi untuk <span class="font-bold text-on-surface" x-text="selectedAttendance?.student?.name"></span>? Tindakan ini tidak dapat dikembalikan.</p>
                        </div>
                        <form :action="'{{ route('teacher.attendances.destroy', 'ID_PLACEHOLDER') }}'.replace('ID_PLACEHOLDER', selectedAttendance?.id)" method="POST">
                            @csrf
                            @method('DELETE')
                            <div class="flex flex-col gap-3 border-t border-outline-variant bg-surface-container-low px-5 py-4 sm:flex-row-reverse sm:px-6">
                                <button type="submit" class="inline-flex w-full justify-center rounded-lg bg-error px-4 py-2.5 text-label-lg text-on-error transition-opacity hover:opacity-90 focus:outline-none sm:w-auto">Batalkan Presensi</button>
                                <button type="button" @click="showDeleteModal = false" class="inline-flex w-full justify-center rounded-lg border border-outline-variant bg-surface px-4 py-2.5 text-label-lg text-on-surface transition-colors hover:bg-surface-container focus:outline-none sm:w-auto">Tutup</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Detail Modal -->
            <div x-show="showDetailModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                <div class="flex min-h-screen items-center justify-center px-4 pb-20 pt-4 text-center sm:p-0">
                    <div x-show="showDetailModal" x-transition.opacity class="fixed inset-0 bg-on-surface/40 transition-opacity" @click="showDetailModal = false" aria-hidden="true"></div>
                    <div x-show="showDetailModal" x-transition.scale.origin.center class="relative z-10 inline-block w-full transform overflow-hidden rounded-xl border border-outline-variant bg-surface text-left align-middle shadow-lg transition-all sm:my-8 sm:max-w-md">
                        <div class="flex items-center justify-between border-b border-outline-variant px-5 py-4 sm:px-6">
                            <h3 class="text-title-lg font-bold text-on-surface">Detail Presensi</h3>
                            <button type="button" @click="showDetailModal = false" class="flex size-9 items-center justify-center rounded-full bg-surface-container-low text-on-surface-variant transition-colors hover:bg-error-container hover:text-on-error-container" aria-label="Tutup"><x-icon name="x" class="size-[18px]" /></button>
                        </div>
                        <div class="space-y-4 px-5 py-5 sm:px-6">
                            <div class="flex items-center justify-between border-b border-outline-variant pb-2">
                                <span class="text-body-md text-secondary">Nama Siswa</span>
                                <span class="font-bold text-on-surface" x-text="selectedAttendance?.student?.name"></span>
                            </div>
                            <div class="flex items-center justify-between border-b border-outline-variant pb-2">
                                <span class="text-body-md text-secondary">Status</span>
                                <span class="inline-block rounded-full px-2.5 py-0.5 text-label-xs font-bold"
                                      :class="{
                                          'bg-tertiary-container text-on-tertiary-container': selectedAttendance?.status === 'present',
                                          'bg-error-container text-on-error-container': selectedAttendance?.status === 'absent',
                                          'bg-surface-container-high text-on-surface-variant': selectedAttendance?.status === 'sick',
                                          'bg-primary-container text-on-primary-container': selectedAttendance?.status === 'permission',
                                          'bg-warning-container text-on-warning-container': selectedAttendance?.status === 'late'
                                      }"
                                      x-text="selectedAttendance?.status === 'present' ? 'Hadir' : (selectedAttendance?.status === 'absent' ? 'Alpa' : (selectedAttendance?.status === 'sick' ? 'Sakit' : (selectedAttendance?.status === 'permission' ? 'Izin' : 'Terlambat')))">
                                </span>
                            </div>
                            <div class="flex items-center justify-between border-b border-outline-variant pb-2">
                                <span class="text-body-md text-secondary">Metode</span>
                                <span class="font-medium uppercase text-on-surface" x-text="selectedAttendance?.method"></span>
                            </div>
                            <div class="flex items-center justify-between border-b border-outline-variant pb-2">
                                <span class="text-body-md text-secondary">Waktu Absen</span>
                                <span class="font-medium text-on-surface" x-text="selectedAttendance?.checked_at ? new Date(selectedAttendance.checked_at).toLocaleString('id-ID') : '-'"></span>
                            </div>
                            <div class="flex flex-col gap-1 border-b border-outline-variant pb-2">
                                <span class="text-body-md text-secondary">Catatan</span>
                                <span class="font-medium text-on-surface" x-text="selectedAttendance?.notes || '-'"></span>
                            </div>
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
        </section>
    </div>

    <!-- Modal Jalankan Presensi -->
    <div x-show="showRunModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex min-h-screen items-center justify-center px-4 pb-20 pt-4 text-center sm:p-0">
            <div x-show="showRunModal" x-transition.opacity class="fixed inset-0 bg-on-surface/40 transition-opacity" @click="showRunModal = false" aria-hidden="true"></div>
            <div x-show="showRunModal" x-transition.scale.origin.center class="relative z-10 inline-block w-full transform overflow-hidden rounded-xl border border-outline-variant bg-surface text-left align-middle shadow-lg transition-all sm:my-8 sm:max-w-lg">
                <div class="border-b border-outline-variant px-5 py-4 sm:px-6">
                    <div class="flex items-start gap-3">
                        <span class="flex size-10 shrink-0 items-center justify-center rounded-lg bg-primary-fixed text-on-primary-fixed">
                            <x-icon name="play" class="size-[20px]" />
                        </span>
                        <div>
                            <h3 class="text-title-lg font-bold leading-6 text-on-surface" id="modal-title">
                                Pilih Jadwal Presensi
                            </h3>
                            <p class="mt-2 text-body-md text-secondary">Silakan pilih jadwal ekstrakurikuler hari ini yang ingin dijalankan presensinya secara langsung (Live).</p>
                        </div>
                    </div>
                </div>
                @if(isset($totalActiveStudents) && $totalActiveStudents == 0)
                    <div class="px-5 py-6 text-center sm:px-6">
                        <div class="mx-auto mb-4 flex size-12 items-center justify-center rounded-full bg-error-container">
                            <x-icon name="triangle-alert" class="size-[24px] text-on-error-container" />
                        </div>
                        <h4 class="mb-2 text-title-md font-bold text-on-surface">Belum Memiliki Siswa Aktif</h4>
                        <p class="text-body-md text-secondary">Kamu belum mempunyai siswa aktif, sehingga belum bisa melakukan presensi. Silakan tunggu siswa mendaftar dan setujui pendaftarannya terlebih dahulu.</p>
                    </div>
                    <div class="flex flex-col gap-3 border-t border-outline-variant bg-surface-container-low px-5 py-4 sm:flex-row-reverse sm:px-6">
                        <button type="button" @click="showRunModal = false" class="inline-flex w-full justify-center rounded-lg border border-outline-variant bg-surface px-4 py-2.5 text-label-lg text-on-surface transition-colors hover:bg-surface-container focus:outline-none sm:w-auto">
                            Tutup
                        </button>
                    </div>
                @else
                    <form action="{{ route('teacher.attendances.start') }}" method="POST" x-data="{
                        selectedOption: '{{ (isset($todaySchedules) && $todaySchedules->count() > 0) ? $todaySchedules->first()->id : 'dadakan' }}',
                        currentTime: '',
                        endTime: '',
                        initTimes() {
                            const d = new Date();
                            this.currentTime = d.toTimeString().substring(0, 5);
                            d.setHours(d.getHours() + 2);
                            this.endTime = d.toTimeString().substring(0, 5);
                        }
                    }" x-init="initTimes(); $watch('showRunModal', val => { if(val) initTimes() });">
                        @csrf
                        <div class="max-h-[65vh] space-y-4 overflow-y-auto px-5 py-5 text-left sm:px-6">

                            <!-- Jadwal dadakan (kartu pilihan pertama) -->
                            <label class="block cursor-pointer rounded-xl border p-4 transition-colors"
                                   :class="selectedOption === 'dadakan' ? 'border-primary bg-primary-container text-on-primary-container ring-2 ring-primary/20' : 'border-outline-variant bg-surface text-on-surface hover:bg-surface-container-low'">
                                <div class="flex items-start">
                                    <div class="mt-1 shrink-0">
                                        <input type="radio" name="schedule_option" value="dadakan" x-model="selectedOption" class="h-4 w-4 border-outline text-primary-container focus:ring-primary">
                                    </div>
                                    <div class="ml-3 w-full">
                                        <span class="block text-label-lg font-bold">BUKA PRESENSI SEKARANG (DADAKAN)</span>
                                        <span class="mt-1 block text-body-sm opacity-80">
                                            Mulai presensi latihan baru di luar jadwal rutin secara instan hari ini.
                                        </span>
                                    </div>
                                </div>

                                <!-- Input dadakan (tampil saat opsi dadakan dipilih) -->
                                <div x-show="selectedOption === 'dadakan'" x-transition class="mt-4 space-y-4 border-t border-on-primary-container/20 pt-4" style="display: none;">
                                    <div class="space-y-1.5">
                                        <label class="block text-label-md">Ekstrakurikuler <span class="font-bold text-error-container">*</span></label>
                                        <select name="extracurricular_id" :required="selectedOption === 'dadakan'" class="w-full rounded-lg border border-outline-variant bg-surface px-3 py-2.5 text-body-md text-on-surface focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
                                            <option value="">-- Pilih Ekskul --</option>
                                            @foreach($ekskuls as $ek)
                                                <option value="{{ $ek->id }}">{{ $ek->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="space-y-1.5">
                                        <label class="block text-label-md">Topik / Agenda</label>
                                        <input type="text" name="topic" placeholder="Contoh: Sesi Latihan Tambahan" class="w-full rounded-lg border border-outline-variant bg-surface px-3 py-2.5 text-body-md text-on-surface placeholder:text-outline focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
                                    </div>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div class="space-y-1.5">
                                            <label class="block text-label-md">Waktu Mulai <span class="font-bold text-error-container">*</span></label>
                                            <input type="time" name="start_time" x-model="currentTime" :required="selectedOption === 'dadakan'" class="w-full rounded-lg border border-outline-variant bg-surface px-3 py-2.5 text-body-md text-on-surface focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
                                        </div>
                                        <div class="space-y-1.5">
                                            <label class="block text-label-md">Waktu Selesai <span class="font-bold text-error-container">*</span></label>
                                            <input type="time" name="end_time" x-model="endTime" :required="selectedOption === 'dadakan'" class="w-full rounded-lg border border-outline-variant bg-surface px-3 py-2.5 text-body-md text-on-surface focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
                                        </div>
                                    </div>
                                    <div class="space-y-1.5">
                                        <label class="block text-label-md">Lokasi <span class="font-bold text-error-container">*</span></label>
                                        <input type="text" name="location" placeholder="Contoh: Lapangan Utama" :required="selectedOption === 'dadakan'" class="w-full rounded-lg border border-outline-variant bg-surface px-3 py-2.5 text-body-md text-on-surface placeholder:text-outline focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
                                    </div>
                                </div>
                            </label>

                            @if(isset($todaySchedules) && $todaySchedules->count() > 0)
                                <div class="relative flex items-center py-2">
                                    <div class="grow border-t border-outline-variant"></div>
                                    <span class="mx-4 shrink text-label-xs font-bold uppercase tracking-wider text-secondary">Atau Pilih Jadwal Hari Ini</span>
                                    <div class="grow border-t border-outline-variant"></div>
                                </div>

                                <div class="space-y-3">
                                    @foreach($todaySchedules as $jadwal)
                                        @php
                                            $now = now()->format('H:i:s');
                                            $startTime = \Carbon\Carbon::parse($jadwal->attendance_start_at)->format('H:i:s');
                                            $endTime = \Carbon\Carbon::parse($jadwal->attendance_end_at)->format('H:i:s');
                                            $isWithinTime = ($now >= $startTime && $now <= $endTime);
                                        @endphp
                                        <label class="flex items-start rounded-xl border p-4 transition-all {{ $isWithinTime ? 'border-outline-variant bg-surface cursor-pointer hover:bg-surface-container-low' : 'border-outline-variant/30 bg-surface-container-lowest opacity-60 cursor-not-allowed' }}"
                                               :class="selectedOption === '{{ $jadwal->id }}' ? 'border-primary ring-2 ring-primary/20' : ''">
                                            <div class="mt-1 shrink-0">
                                                <input type="radio" name="schedule_option" value="{{ $jadwal->id }}" x-model="selectedOption" class="h-4 w-4 border-outline text-primary-container focus:ring-primary" {{ !$isWithinTime ? 'disabled' : '' }}>
                                            </div>
                                            <div class="ml-3 w-full">
                                                <span class="block text-label-lg font-bold {{ $isWithinTime ? 'text-on-surface' : 'text-secondary' }}">{{ $jadwal->extracurricular->name }}</span>
                                                <span class="mt-1 block text-body-sm text-secondary">
                                                    Waktu: {{ \Carbon\Carbon::parse($jadwal->attendance_start_at)->format('H:i') }} - {{ \Carbon\Carbon::parse($jadwal->attendance_end_at)->format('H:i') }}
                                                </span>
                                                @if(!$isWithinTime)
                                                    <span class="mt-1 block text-body-sm font-medium text-error"><x-icon name="triangle-alert" class="align-middle size-[14px]" /> Sesi presensi belum waktunya atau sudah lewat</span>
                                                @endif
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            @endif

                            <input type="hidden" name="schedule_id" :value="selectedOption !== 'dadakan' ? selectedOption : ''">
                        </div>
                        <div class="flex flex-col gap-3 border-t border-outline-variant bg-surface-container-low px-5 py-4 sm:flex-row-reverse sm:px-6">
                            <button type="submit" class="inline-flex w-full justify-center rounded-lg bg-primary-container px-4 py-2.5 text-label-lg text-on-primary-container transition-opacity hover:opacity-90 focus:outline-none sm:w-auto">
                                Mulai Presensi
                            </button>
                            <button type="button" @click="showRunModal = false" class="inline-flex w-full justify-center rounded-lg border border-outline-variant bg-surface px-4 py-2.5 text-label-lg text-on-surface transition-colors hover:bg-surface-container focus:outline-none sm:w-auto">
                                Batal
                            </button>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>
    </div>
</x-app-layout>
