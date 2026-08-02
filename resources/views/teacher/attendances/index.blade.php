<x-app-layout>
    <div x-data="{ showRunModal: false }">
    <div class="flex flex-col md:flex-row justify-between md:items-end gap-4 mb-6">
        <div class="mb-8 flex items-center gap-3">
            <div class="w-12 h-12 bg-primary/10 text-primary rounded-xl flex items-center justify-center">
                <span class="material-symbols-outlined text-[28px]">fact_check</span>
            </div>
            <div>
                <h3 class="font-headline-md text-headline-md text-on-surface font-bold">Data Absensi</h3>
                <p class="text-body-md text-secondary mt-1">Pantau kehadiran siswa di setiap sesi ekstrakurikuler.</p>
            </div>
        </div>

        <form action="{{ route('teacher.attendances.index') }}" method="GET" class="flex flex-wrap items-center gap-2">
            <input type="date" name="date" value="{{ request('date') }}" class="px-4 py-2 border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary/20 bg-white font-body-md">
            <select name="ekskul_id" class="px-4 py-2 border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary/20 bg-white font-body-md min-w-[150px]">
                <option value="">Semua Ekskul</option>
                @foreach($ekskuls as $ek)
                    <option value="{{ $ek->id }}" {{ request('ekskul_id') == $ek->id ? 'selected' : '' }}>
                        {{ $ek->name }}
                    </option>
                @endforeach
            </select>
            <button type="submit" class="bg-primary text-white px-6 py-2.5 rounded-lg font-label-md font-bold hover:bg-primary/90 transition-colors flex items-center gap-2">
                <span class="material-symbols-outlined text-[20px]">filter_list</span> Terapkan Filter
            </button>
            <button type="button" @click="showRunModal = true" class="bg-primary text-white px-6 py-2.5 rounded-lg font-label-md font-bold hover:bg-primary/90 transition-all flex items-center gap-2 ml-2 shadow-md">
                <span class="material-symbols-outlined text-[20px]">play_circle</span> Jalankan Presensi
            </button>
        </form>
    </div>

    <!-- Summary Cards (1 Baris Kanan Kiri) -->
    <div class="bg-white rounded-xl border border-outline-variant card-shadow mb-8 overflow-x-auto">
        <div class="flex flex-row items-center divide-x divide-outline-variant min-w-max w-full">
            
            <div class="flex-1 p-4 flex flex-row items-center justify-center gap-2">
                <span class="material-symbols-outlined text-primary text-[24px]">fact_check</span>
                <span class="text-body-md text-secondary font-medium whitespace-nowrap">% Hadir:</span>
                <span class="text-title-lg font-bold text-on-surface">{{ $persentaseHadir }}%</span>
            </div>

            <div class="flex-1 p-4 flex flex-row items-center justify-center gap-2">
                <span class="material-symbols-outlined text-tertiary text-[24px]">how_to_reg</span>
                <span class="text-body-md text-secondary font-medium whitespace-nowrap">Hadir:</span>
                <span class="text-title-lg font-bold text-on-surface">{{ $totalHadir }}</span>
            </div>

            <div class="flex-1 p-4 flex flex-row items-center justify-center gap-2">
                <span class="material-symbols-outlined text-[#D97706] text-[24px]">sick</span>
                <span class="text-body-md text-secondary font-medium whitespace-nowrap">Sakit/Izin:</span>
                <span class="text-title-lg font-bold text-on-surface">{{ $totalSakit + $totalIzin }}</span>
            </div>

            <div class="flex-1 p-4 flex flex-row items-center justify-center gap-2">
                <span class="material-symbols-outlined text-error text-[24px]">person_off</span>
                <span class="text-body-md text-secondary font-medium whitespace-nowrap">Alpa:</span>
                <span class="text-title-lg font-bold text-error">{{ $totalAlpa }}</span>
            </div>

        </div>
    </div>

    <div x-data="{ 
        showAddModal: false, 
        showEditModal: false, 
        showDeleteModal: false, 
        showDetailModal: false,
        selectedAttendance: {},
        
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
        }
    }" class="bg-white rounded-xl border border-outline-variant card-shadow overflow-hidden">
        <div class="p-4 bg-surface-container-low border-b border-outline-variant flex justify-between items-center">
            <h4 class="font-title-md font-bold text-on-surface flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">table_view</span> Riwayat Presensi
            </h4>
            <button @click="showAddModal = true" class="flex items-center gap-2 px-4 py-2 bg-primary text-white rounded-lg text-label-md font-bold hover:bg-primary/90 transition-colors shadow-sm">
                <span class="material-symbols-outlined text-[18px]">add</span> Tambah Presensi
            </button>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-outline-variant bg-surface-container-lowest">
                        <th class="p-4 font-label-md text-secondary"><span class="material-symbols-outlined text-[18px]">calendar_today</span> TANGGAL</th>
                        <th class="p-4 font-label-md text-secondary flex items-center gap-2"><span class="material-symbols-outlined text-[18px]">person</span> NAMA SISWA</th>
                        <th class="p-4 font-label-md text-secondary"><span class="material-symbols-outlined text-[18px]">sports_martial_arts</span> EKSKUL</th>
                        <th class="p-4 font-label-md text-secondary"><span class="material-symbols-outlined text-[18px]">rule</span> STATUS</th>
                        <th class="p-4 font-label-md text-secondary"><span class="material-symbols-outlined text-[18px]">photo_camera</span> FOTO/SELFIE</th>
                        <th class="p-4 font-label-md text-secondary">AKSI/VERIFIKASI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant">
                    @forelse($attendances as $att)
                    <tr class="hover:bg-surface-container-low transition-colors">
                        <td class="p-4 font-body-md text-on-surface-variant">{{ \Carbon\Carbon::parse($att->attendanceSession->schedule->activity_date)->format('d M Y') }}</td>
                        <td class="p-4 font-body-md text-on-surface font-semibold">{{ $att->student->name ?? '-' }}</td>
                        <td class="p-4 font-body-md text-on-surface-variant">{{ $att->attendanceSession->schedule->extracurricular->name ?? '-' }}</td>
                        <td class="p-4">
                            @if($att->status == 'present')
                                <span class="px-3 py-1 bg-tertiary-container text-on-tertiary-container text-label-sm font-bold rounded-full">Hadir</span>
                            @elseif($att->status == 'absent')
                                <span class="px-3 py-1 bg-error-container text-on-error-container text-label-sm font-bold rounded-full">Alpa</span>
                            @elseif($att->status == 'sick')
                                <span class="px-3 py-1 bg-surface-variant text-on-surface-variant text-label-sm font-bold rounded-full">Sakit</span>
                            @else
                                <span class="px-3 py-1 bg-primary-container text-on-primary-container text-label-sm font-bold rounded-full">Izin</span>
                            @endif
                            @if($att->is_verified_by_teacher)
                                <span class="material-symbols-outlined text-[14px] text-tertiary align-middle tooltip" title="Terverifikasi">verified</span>
                            @endif
                        </td>
                        <td class="p-4">
                            @if($att->photo_path)
                                <a href="{{ Storage::url($att->photo_path) }}" target="_blank" class="text-primary hover:underline text-label-sm flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[16px]">photo_camera</span> Lihat
                                </a>
                            @else
                                <span class="text-outline text-label-sm">-</span>
                            @endif
                        </td>
                        <td class="p-4 flex gap-2">
                            <button @click="openDetail({{ json_encode($att) }})" class="w-8 h-8 rounded-full bg-primary/10 text-primary flex items-center justify-center hover:bg-primary hover:text-white transition-colors" title="Detail">
                                <span class="material-symbols-outlined text-[16px]">visibility</span>
                            </button>
                            <button @click="openEdit({{ json_encode($att) }})" class="w-8 h-8 rounded-full bg-[#F59E0B]/10 text-[#F59E0B] flex items-center justify-center hover:bg-[#F59E0B] hover:text-white transition-colors" title="Edit">
                                <span class="material-symbols-outlined text-[16px]">edit</span>
                            </button>
                            <button @click="openDelete({{ json_encode($att) }})" class="w-8 h-8 rounded-full bg-error/10 text-error flex items-center justify-center hover:bg-error hover:text-white transition-colors" title="Hapus">
                                <span class="material-symbols-outlined text-[16px]">delete</span>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="p-4 text-center text-on-surface-variant">Belum ada data kehadiran</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-outline-variant">
            {{ $attendances->links() }}
        </div>

        <!-- Add Modal -->
        <div x-show="showAddModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:p-0">
                <div x-show="showAddModal" x-transition.opacity class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity" @click="showAddModal = false" aria-hidden="true"></div>
                <div x-show="showAddModal" x-transition.scale.origin.center class="inline-block align-middle bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:max-w-lg w-full relative z-10">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 border-b border-outline-variant">
                        <h3 class="text-title-lg font-bold text-on-surface">Tambah Presensi Manual</h3>
                    </div>
                    <form action="{{ route('teacher.attendances.store') }}" method="POST">
                        @csrf
                        <div class="px-4 py-5 sm:p-6 space-y-4">
                            <div>
                                <label class="block text-label-md font-bold text-on-surface mb-1">Jadwal</label>
                                <select name="schedule_id" class="w-full px-3 py-2 border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary/20 bg-white" required>
                                    <option value="">Pilih Jadwal...</option>
                                    @foreach($allSchedules as $sch)
                                        <option value="{{ $sch->id }}">{{ $sch->extracurricular->name }} - {{ \Carbon\Carbon::parse($sch->activity_date)->format('d M Y') }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-label-md font-bold text-on-surface mb-1">Siswa</label>
                                <select name="student_id" class="w-full px-3 py-2 border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary/20 bg-white" required>
                                    <option value="">Pilih Siswa...</option>
                                    @foreach($activeStudents as $reg)
                                        <option value="{{ $reg->student->id }}">{{ $reg->student->name }} ({{ $reg->extracurricular->name }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-label-md font-bold text-on-surface mb-1">Status</label>
                                <select name="status" class="w-full px-3 py-2 border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary/20 bg-white" required>
                                    <option value="present">Hadir</option>
                                    <option value="sick">Sakit</option>
                                    <option value="permitted">Izin</option>
                                    <option value="absent">Alpa</option>
                                    <option value="late">Terlambat</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-label-md font-bold text-on-surface mb-1">Catatan Tambahan (Opsional)</label>
                                <textarea name="notes" class="w-full px-3 py-2 border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary/20 bg-white" rows="2" placeholder="Tulis catatan jika diperlukan..."></textarea>
                            </div>
                        </div>
                        <div class="bg-surface-container-low px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-outline-variant">
                            <button type="submit" class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-primary text-base font-medium text-white hover:bg-primary/90 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">Simpan</button>
                            <button type="button" @click="showAddModal = false" class="mt-3 w-full inline-flex justify-center rounded-lg border border-outline-variant shadow-sm px-4 py-2 bg-white text-base font-medium text-secondary hover:bg-surface-container-low focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Batal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Edit Modal -->
        <div x-show="showEditModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:p-0">
                <div x-show="showEditModal" x-transition.opacity class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity" @click="showEditModal = false" aria-hidden="true"></div>
                <div x-show="showEditModal" x-transition.scale.origin.center class="inline-block align-middle bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:max-w-lg w-full relative z-10">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 border-b border-outline-variant">
                        <h3 class="text-title-lg font-bold text-on-surface">Edit Presensi</h3>
                        <p class="text-body-sm text-secondary mt-1" x-text="selectedAttendance?.student?.name"></p>
                    </div>
                    <form :action="'{{ route('teacher.attendances.update', 'ID_PLACEHOLDER') }}'.replace('ID_PLACEHOLDER', selectedAttendance?.id)" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="px-4 py-5 sm:p-6 space-y-4">
                            <div>
                                <label class="block text-label-md font-bold text-on-surface mb-1">Status</label>
                                <select name="status" class="w-full px-3 py-2 border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary/20 bg-white" x-model="selectedAttendance.status" required>
                                    <option value="present">Hadir</option>
                                    <option value="sick">Sakit</option>
                                    <option value="permitted">Izin</option>
                                    <option value="absent">Alpa</option>
                                    <option value="late">Terlambat</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-label-md font-bold text-on-surface mb-1">Catatan Tambahan (Opsional)</label>
                                <textarea name="notes" class="w-full px-3 py-2 border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary/20 bg-white" rows="2" x-model="selectedAttendance.notes" placeholder="Tulis catatan jika diperlukan..."></textarea>
                            </div>
                        </div>
                        <div class="bg-surface-container-low px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-outline-variant">
                            <button type="submit" class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-primary text-base font-medium text-white hover:bg-primary/90 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">Simpan</button>
                            <button type="button" @click="showEditModal = false" class="mt-3 w-full inline-flex justify-center rounded-lg border border-outline-variant shadow-sm px-4 py-2 bg-white text-base font-medium text-secondary hover:bg-surface-container-low focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Batal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Delete Modal -->
        <div x-show="showDeleteModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:p-0">
                <div x-show="showDeleteModal" x-transition.opacity class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity" @click="showDeleteModal = false" aria-hidden="true"></div>
                <div x-show="showDeleteModal" x-transition.scale.origin.center class="inline-block align-middle bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:max-w-sm w-full relative z-10">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 text-center">
                        <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-error/10 mb-4">
                            <span class="material-symbols-outlined text-error text-[24px]">delete</span>
                        </div>
                        <h3 class="text-title-md font-bold text-on-surface mb-2">Hapus Data Presensi?</h3>
                        <p class="text-body-md text-secondary">Apakah Anda yakin ingin menghapus data presensi untuk <span class="font-bold text-on-surface" x-text="selectedAttendance?.student?.name"></span>? Tindakan ini tidak dapat dibatalkan.</p>
                    </div>
                    <form :action="'{{ route('teacher.attendances.destroy', 'ID_PLACEHOLDER') }}'.replace('ID_PLACEHOLDER', selectedAttendance?.id)" method="POST">
                        @csrf
                        @method('DELETE')
                        <div class="bg-surface-container-low px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-outline-variant">
                            <button type="submit" class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-error text-base font-medium text-white hover:bg-error/90 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">Hapus</button>
                            <button type="button" @click="showDeleteModal = false" class="mt-3 w-full inline-flex justify-center rounded-lg border border-outline-variant shadow-sm px-4 py-2 bg-white text-base font-medium text-secondary hover:bg-surface-container-low focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Batal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Detail Modal -->
        <div x-show="showDetailModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:p-0">
                <div x-show="showDetailModal" x-transition.opacity class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity" @click="showDetailModal = false" aria-hidden="true"></div>
                <div x-show="showDetailModal" x-transition.scale.origin.center class="inline-block align-middle bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:max-w-md w-full relative z-10">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 border-b border-outline-variant flex justify-between items-center">
                        <h3 class="text-title-lg font-bold text-on-surface">Detail Presensi</h3>
                        <button type="button" @click="showDetailModal = false" class="text-on-surface-variant hover:text-error transition-colors"><span class="material-symbols-outlined">close</span></button>
                    </div>
                    <div class="px-4 py-5 sm:p-6 space-y-4">
                        <div class="flex justify-between items-center border-b border-outline-variant pb-2">
                            <span class="text-body-md text-secondary">Nama Siswa</span>
                            <span class="font-bold text-on-surface" x-text="selectedAttendance?.student?.name"></span>
                        </div>
                        <div class="flex justify-between items-center border-b border-outline-variant pb-2">
                            <span class="text-body-md text-secondary">Status</span>
                            <span class="font-bold px-2 py-1 rounded text-label-sm"
                                  :class="{
                                      'bg-tertiary-container text-on-tertiary-container': selectedAttendance?.status === 'present',
                                      'bg-error-container text-on-error-container': selectedAttendance?.status === 'absent',
                                      'bg-surface-variant text-on-surface-variant': selectedAttendance?.status === 'sick',
                                      'bg-primary-container text-on-primary-container': selectedAttendance?.status === 'permitted',
                                      'bg-[#F59E0B]/10 text-[#F59E0B]': selectedAttendance?.status === 'late'
                                  }"
                                  x-text="selectedAttendance?.status === 'present' ? 'Hadir' : (selectedAttendance?.status === 'absent' ? 'Alpa' : (selectedAttendance?.status === 'sick' ? 'Sakit' : (selectedAttendance?.status === 'permitted' ? 'Izin' : 'Terlambat')))">
                            </span>
                        </div>
                        <div class="flex justify-between items-center border-b border-outline-variant pb-2">
                            <span class="text-body-md text-secondary">Metode</span>
                            <span class="font-medium text-on-surface uppercase" x-text="selectedAttendance?.method"></span>
                        </div>
                        <div class="flex justify-between items-center border-b border-outline-variant pb-2">
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

    </div>

    <!-- Modal Jalankan Presensi -->
    <div x-show="showRunModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:p-0">
            <div x-show="showRunModal" x-transition.opacity class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity" @click="showRunModal = false" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div x-show="showRunModal" x-transition.scale.origin.center class="inline-block align-middle bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:max-w-lg w-full relative z-10">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4 border-b border-outline-variant">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-primary/10 sm:mx-0 sm:h-10 sm:w-10">
                            <span class="material-symbols-outlined text-primary">play_arrow</span>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-title-lg leading-6 font-bold text-on-surface" id="modal-title">
                                Pilih Jadwal Presensi
                            </h3>
                            <div class="mt-2">
                                <p class="text-body-md text-secondary">Silakan pilih jadwal ekstrakurikuler hari ini yang ingin dijalankan presensinya secara langsung (Live).</p>
                            </div>
                        </div>
                    </div>
                </div>
                @if(isset($totalActiveStudents) && $totalActiveStudents == 0)
                    <div class="px-4 py-5 sm:p-6 text-center">
                        <div class="bg-error/10 text-error p-4 rounded-xl inline-block mb-4">
                            <span class="material-symbols-outlined text-[32px]">warning</span>
                        </div>
                        <h4 class="text-title-md font-bold text-on-surface mb-2">Belum Memiliki Siswa Aktif</h4>
                        <p class="text-body-md text-secondary">Kamu belum mempunyai siswa aktif, sehingga belum bisa melakukan presensi. Silakan tunggu siswa mendaftar dan setujui pendaftarannya terlebih dahulu.</p>
                    </div>
                    <div class="bg-surface-container-low px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-outline-variant">
                        <button type="button" @click="showRunModal = false" class="mt-3 w-full inline-flex justify-center rounded-lg border border-outline-variant shadow-sm px-4 py-2 bg-white text-base font-medium text-secondary hover:bg-surface-container-low focus:outline-none sm:mt-0 sm:w-auto sm:text-sm transition-colors">
                            Tutup
                        </button>
                    </div>
                @else
                    <form action="{{ route('teacher.attendances.start') }}" method="POST">
                        @csrf
                        <div class="px-4 py-5 sm:p-6">
                            @if(isset($todaySchedules) && $todaySchedules->count() > 0)
                                <div class="space-y-4">
                                    @foreach($todaySchedules as $jadwal)
                                        <label class="flex items-start p-4 border border-outline-variant rounded-lg cursor-pointer hover:bg-surface-container-low transition-colors">
                                            <div class="flex-shrink-0 mt-1">
                                                <input type="radio" name="schedule_id" value="{{ $jadwal->id }}" class="h-4 w-4 text-primary focus:ring-primary border-outline" required>
                                            </div>
                                            <div class="ml-3">
                                                <span class="block text-label-lg font-bold text-on-surface">{{ $jadwal->extracurricular->name }}</span>
                                                <span class="block text-body-sm text-secondary mt-1">
                                                    Waktu: {{ \Carbon\Carbon::parse($jadwal->attendance_start_at)->format('H:i') }} - {{ \Carbon\Carbon::parse($jadwal->attendance_end_at)->format('H:i') }}
                                                </span>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            @else
                                <div class="space-y-4 text-left">
                                    <div class="p-3 bg-primary/10 text-primary rounded-lg text-body-sm flex gap-2 border border-primary/20">
                                        <span class="material-symbols-outlined text-[20px]">info</span>
                                        <span>Tidak ada jadwal hari ini. Sistem akan otomatis membuat jadwal sesi latihan baru untuk hari ini saat Anda klik Mulai.</span>
                                    </div>
                                    <div>
                                        <label class="block text-label-md font-bold text-on-surface mb-2">Pilih Ekstrakurikuler:</label>
                                        <select name="extracurricular_id" class="w-full px-4 py-3 border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary/20 bg-white font-body-md" required>
                                            <option value="">Pilih Ekskul...</option>
                                            @foreach($ekskuls as $ek)
                                                <option value="{{ $ek->id }}">{{ $ek->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="bg-surface-container-low px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-outline-variant">
                            <button type="submit" class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-primary text-base font-medium text-white hover:bg-primary/90 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                                Mulai Presensi
                            </button>
                            <button type="button" @click="showRunModal = false" class="mt-3 w-full inline-flex justify-center rounded-lg border border-outline-variant shadow-sm px-4 py-2 bg-white text-base font-medium text-secondary hover:bg-surface-container-low focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
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
