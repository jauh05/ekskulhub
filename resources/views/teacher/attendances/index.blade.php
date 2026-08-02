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
            <button type="button" @click="showRunModal = true" class="bg-[#10B981] text-white px-6 py-2.5 rounded-lg font-label-md font-bold hover:bg-[#059669] transition-all flex items-center gap-2 ml-2 shadow-md">
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

    <div class="bg-white rounded-xl border border-outline-variant card-shadow overflow-hidden">
        <div class="p-4 bg-surface-container-low border-b border-outline-variant flex justify-between items-center">
            <h4 class="font-title-md font-bold text-on-surface flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">table_view</span> Detail Kehadiran Siswa
            </h4>
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
                        <td class="p-4">
                            <form action="{{ route('teacher.attendances.update', $att->id) }}" method="POST" class="flex gap-1">
                                @csrf @method('PATCH')
                                <select name="status" class="py-1 px-2 border border-outline-variant rounded text-label-sm" onchange="this.form.submit()">
                                    <option value="present" {{ $att->status == 'present' ? 'selected' : '' }}>Hadir</option>
                                    <option value="sick" {{ $att->status == 'sick' ? 'selected' : '' }}>Sakit</option>
                                    <option value="permitted" {{ $att->status == 'permitted' ? 'selected' : '' }}>Izin</option>
                                    <option value="absent" {{ $att->status == 'absent' ? 'selected' : '' }}>Alpa</option>
                                </select>
                            </form>
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
    </div>

    <!-- Modal Jalankan Presensi -->
    <div x-show="showRunModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="showRunModal" x-transition.opacity class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="showRunModal = false" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div x-show="showRunModal" x-transition.scale.origin.bottom class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
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
                            <div class="p-4 bg-error-container text-on-error-container rounded-lg flex items-start gap-2">
                                <span class="material-symbols-outlined mt-0.5">error</span>
                                <div>
                                    <p class="font-bold">Tidak ada jadwal hari ini.</p>
                                    <p class="text-sm mt-1">Anda belum membuat jadwal untuk hari ini, atau Anda tidak mengampu ekskul apapun di hari ini.</p>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="bg-surface-container-low px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-outline-variant">
                        @if(isset($todaySchedules) && $todaySchedules->count() > 0)
                            <button type="submit" class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-primary text-base font-medium text-white hover:bg-primary/90 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                                Mulai Presensi
                            </button>
                        @endif
                        <button type="button" @click="showRunModal = false" class="mt-3 w-full inline-flex justify-center rounded-lg border border-outline-variant shadow-sm px-4 py-2 bg-white text-base font-medium text-secondary hover:bg-surface-container-low focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    </div>
</x-app-layout>
