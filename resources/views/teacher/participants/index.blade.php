<x-app-layout>
    <div class="flex flex-col md:flex-row justify-between md:items-end gap-4 mb-6">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 bg-primary/10 text-primary rounded-xl flex items-center justify-center">
                <span class="material-symbols-outlined text-[28px]">groups</span>
            </div>
            <div>
                <h3 class="font-headline-md text-headline-md text-on-surface font-bold">Data Peserta Ekskul</h3>
                <p class="text-body-md text-secondary">Ringkasan siswa yang terdaftar pada ekstrakurikuler Anda</p>
            </div>
        </div>
        
        <form action="{{ route('teacher.participants.index') }}" method="GET" class="flex flex-col sm:flex-row items-center gap-2 w-full md:w-auto">
            <div class="relative w-full sm:w-auto">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-secondary text-[20px]">search</span>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama siswa..." class="w-full sm:w-64 pl-10 pr-4 py-2 border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary/20 bg-white font-body-md text-on-surface">
            </div>
            <select name="ekskul_id" class="w-full sm:w-auto px-4 py-2 border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary/20 bg-white font-body-md min-w-[200px]">
                <option value="">Semua Ekstrakurikuler</option>
                @foreach($ekskuls as $ek)
                    <option value="{{ $ek->id }}" {{ request('ekskul_id') == $ek->id ? 'selected' : '' }}>
                        {{ $ek->name }}
                    </option>
                @endforeach
            </select>
            <button type="submit" class="w-full sm:w-auto bg-primary text-white px-6 py-2.5 rounded-lg font-label-md font-bold hover:bg-primary/90 transition-colors flex items-center justify-center gap-2">
                <span class="material-symbols-outlined text-[20px]">filter_list</span> Filter
            </button>
        </form>
    </div>

    <!-- Summary Cards (1 Baris Kanan Kiri) -->
    <div class="bg-white rounded-xl border border-outline-variant card-shadow mb-8 overflow-x-auto">
        <div class="flex flex-row items-center divide-x divide-outline-variant min-w-max w-full">
            
            <div class="flex-1 p-4 flex flex-row items-center justify-center gap-2">
                <span class="material-symbols-outlined text-primary text-[24px]">group</span>
                <span class="text-body-md text-secondary font-medium whitespace-nowrap">Total Siswa:</span>
                <span class="text-title-lg font-bold text-on-surface">{{ $totalSiswa }}</span>
            </div>
            
            <div class="flex-1 p-4 flex flex-row items-center justify-center gap-2">
                <span class="material-symbols-outlined text-[#10B981] text-[24px]">person_add</span>
                <span class="text-body-md text-secondary font-medium whitespace-nowrap">Baru Bulan Ini:</span>
                <span class="text-title-lg font-bold text-on-surface">{{ $siswaBaruBulanIni }}</span>
            </div>

            <div class="flex-1 p-4 flex flex-row items-center justify-center gap-2">
                <span class="material-symbols-outlined text-[#D97706] text-[24px]">category</span>
                <span class="text-body-md text-secondary font-medium whitespace-nowrap">Jumlah Ekskul:</span>
                <span class="text-title-lg font-bold text-on-surface">{{ $totalEkskul }}</span>
            </div>

        </div>
    </div>

    <div class="bg-white rounded-xl border border-outline-variant card-shadow overflow-hidden">
        <div class="p-4 bg-surface-container-low border-b border-outline-variant flex justify-between items-center">
            <h4 class="font-title-md font-bold text-on-surface flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">table_view</span> Data Siswa
            </h4>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-outline-variant bg-surface-container-lowest">
                        <th class="p-4 font-label-md text-secondary flex items-center gap-2"><span class="material-symbols-outlined text-[18px]">person</span> NAMA SISWA</th>
                        <th class="p-4 font-label-md text-secondary"><span class="material-symbols-outlined text-[18px]">school</span> KELAS</th>
                        <th class="p-4 font-label-md text-secondary"><span class="material-symbols-outlined text-[18px]">sports_soccer</span> EKSKUL</th>
                        <th class="p-4 font-label-md text-secondary"><span class="material-symbols-outlined text-[18px]">wc</span> L/P</th>
                        <th class="p-4 font-label-md text-secondary"><span class="material-symbols-outlined text-[18px]">fact_check</span> ABSEN</th>
                        <th class="p-4 font-label-md text-secondary"><span class="material-symbols-outlined text-[18px]">calendar_today</span> GABUNG</th>
                        <th class="p-4 font-label-md text-secondary"><span class="material-symbols-outlined text-[18px]">info</span> STATUS</th>
                        <th class="p-4 font-label-md text-secondary text-right"><span class="material-symbols-outlined text-[18px]">settings</span> AKSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant">
                    @forelse($participants as $part)
                    <tr class="hover:bg-surface-container-low transition-colors">
                        <td class="p-4 font-body-md text-on-surface font-semibold">{{ $part->student->name ?? '-' }}</td>
                        <td class="p-4 font-body-md text-on-surface-variant">{{ $part->student->studentProfile->class_name ?? '-' }}</td>
                        <td class="p-4 font-body-md text-on-surface-variant">{{ $part->extracurricular->name ?? '-' }}</td>
                        <td class="p-4 font-body-md text-on-surface-variant">
                            @php
                                $gender = $part->student->studentProfile->gender ?? '-';
                                $genderLabel = $gender === 'male' ? 'L' : ($gender === 'female' ? 'P' : '-');
                            @endphp
                            {{ $genderLabel }}
                        </td>
                        <td class="p-4 font-body-md text-on-surface-variant">
                            @php
                                $totalSchedules = $part->extracurricular->schedules->count();
                                $attended = $part->student->attendances->where('status', 'present')->count();
                            @endphp
                            <span class="font-bold text-primary">{{ $attended }}</span> / {{ $totalSchedules }}
                        </td>
                        <td class="p-4 font-body-md text-on-surface-variant">{{ $part->created_at->format('d M Y') }}</td>
                        <td class="p-4 font-body-md">
                            @if($part->status === 'approved')
                                <span class="bg-[#10B981]/10 text-[#10B981] px-3 py-1 rounded-full text-[12px] font-bold">Aktif</span>
                            @elseif($part->status === 'pending')
                                <span class="bg-[#F59E0B]/10 text-[#F59E0B] px-3 py-1 rounded-full text-[12px] font-bold">Pending</span>
                            @else
                                <span class="bg-[#EF4444]/10 text-[#EF4444] px-3 py-1 rounded-full text-[12px] font-bold">Ditolak</span>
                            @endif
                        </td>
                        <td class="p-4 flex gap-2 justify-end">
                            @if($part->status === 'pending')
                                <form action="{{ route('teacher.participants.update', $part->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="approved">
                                    <button type="submit" class="w-8 h-8 rounded-full bg-[#10B981]/10 text-[#10B981] hover:bg-[#10B981] hover:text-white flex items-center justify-center transition-colors shadow-sm" title="Terima Siswa">
                                        <span class="material-symbols-outlined text-[18px]">check</span>
                                    </button>
                                </form>
                                <form action="{{ route('teacher.participants.update', $part->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="rejected">
                                    <button type="submit" class="w-8 h-8 rounded-full bg-[#EF4444]/10 text-[#EF4444] hover:bg-[#EF4444] hover:text-white flex items-center justify-center transition-colors shadow-sm" title="Tolak Siswa">
                                        <span class="material-symbols-outlined text-[18px]">close</span>
                                    </button>
                                </form>
                            @endif
                            <button type="button" 
                                onclick="openDetailModal(
                                    '{{ $part->id }}', 
                                    '{{ $part->student_id }}',
                                    '{{ addslashes($part->student->name ?? '-') }}', 
                                    '{{ addslashes($part->student->studentProfile->class_name ?? '-') }}', 
                                    '{{ addslashes($part->student->email ?? '-') }}', 
                                    '{{ addslashes($part->student->phone ?? '-') }}', 
                                    '{{ $genderLabel }}', 
                                    '{{ addslashes($part->extracurricular->name ?? '-') }}', 
                                    '{{ $part->status }}',
                                    '{{ $attended }} / {{ $totalSchedules }}'
                                )" 
                                class="w-8 h-8 rounded-full bg-primary/10 text-primary hover:bg-primary hover:text-white flex items-center justify-center transition-colors shadow-sm" title="Detail Siswa">
                                <span class="material-symbols-outlined text-[18px]">edit_square</span>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="p-4 text-center text-on-surface-variant">Belum ada peserta</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($participants->hasPages())
            <div class="p-4 border-t border-outline-variant bg-surface-container-lowest">
                {{ $participants->links() }}
            </div>
        @endif
        </div>
    </div>

    <!-- Modal Detail Siswa -->
    <div id="detailModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 backdrop-blur-sm opacity-0 transition-opacity duration-300">
        <div class="bg-white rounded-xl w-[320px] overflow-hidden shadow-xl transform scale-95 transition-transform duration-300 relative border border-outline-variant/30">
            <!-- Close Button -->
            <button type="button" onclick="closeDetailModal()" class="absolute top-3 right-3 w-9 h-9 rounded-full bg-surface-container-low text-on-surface-variant hover:bg-error/10 hover:text-error flex items-center justify-center transition-colors z-10">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>

            <!-- Profile Header -->
            <div class="p-5 pb-3 flex flex-col items-center border-b border-outline-variant/50 bg-surface-container-lowest">
                <div class="w-14 h-14 rounded-full bg-primary/10 text-primary flex items-center justify-center text-xl font-bold mb-2 border-2 border-white shadow-sm" id="modalInitial">
                    A
                </div>
                <h4 class="font-title-md font-bold text-on-surface text-center leading-tight" id="modalName">-</h4>
                <p class="font-label-md text-on-surface-variant mt-0.5 text-center" id="modalClass">-</p>
                <div id="modalStatus" class="mt-2 px-3 py-1 rounded-full text-[10px] font-bold bg-[#10B981]/10 text-[#10B981]">AKTIF</div>
            </div>
            
            <!-- Details List -->
            <div class="p-4 space-y-3 bg-white">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-surface-container-low flex items-center justify-center text-on-surface-variant">
                        <span class="material-symbols-outlined text-[16px]">mail</span>
                    </div>
                    <div>
                        <p class="text-on-surface-variant font-label-sm uppercase tracking-wider text-[9px]">Email</p>
                        <p class="font-body-xs text-on-surface font-medium truncate max-w-[200px]" id="modalEmail">-</p>
                    </div>
                </div>
                
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-surface-container-low flex items-center justify-center text-on-surface-variant">
                        <span class="material-symbols-outlined text-[16px]">call</span>
                    </div>
                    <div>
                        <p class="text-on-surface-variant font-label-sm uppercase tracking-wider text-[9px]">WhatsApp</p>
                        <p class="font-body-xs text-on-surface font-medium" id="modalPhone">-</p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-surface-container-low flex items-center justify-center text-on-surface-variant">
                        <span class="material-symbols-outlined text-[16px]">wc</span>
                    </div>
                    <div>
                        <p class="text-on-surface-variant font-label-sm uppercase tracking-wider text-[9px]">Jenis Kelamin</p>
                        <p class="font-body-xs text-on-surface font-medium" id="modalGender">-</p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-surface-container-low flex items-center justify-center text-on-surface-variant">
                        <span class="material-symbols-outlined text-[16px]">sports_soccer</span>
                    </div>
                    <div class="flex-1 flex justify-between items-center">
                        <div>
                            <p class="text-on-surface-variant font-label-sm uppercase tracking-wider text-[9px]">Ekskul</p>
                            <p class="font-body-xs text-on-surface font-medium" id="modalEkskul">-</p>
                        </div>
                        <div class="text-right">
                            <p class="text-on-surface-variant font-label-sm uppercase tracking-wider text-[9px]">Absensi</p>
                            <p class="font-title-sm text-primary font-bold mt-0.5" id="modalAbsen">-</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer Action -->
            <div class="p-4 bg-surface-container-lowest border-t border-outline-variant/50 flex flex-col gap-2">
                <form id="resetPasswordForm" method="POST" class="w-full">
                    @csrf
                    <button type="submit" class="w-full py-2 rounded-lg font-label-md bg-secondary text-white hover:bg-secondary/90 transition-all shadow-sm flex items-center justify-center gap-2" onclick="return confirm('Apakah Anda yakin ingin mereset kata sandi siswa ini menjadi default: password ?')">
                        <span class="material-symbols-outlined text-[16px]">lock_reset</span> Reset Sandi (default: password)
                    </button>
                </form>
                <form id="kickForm" method="POST" class="w-full">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="status" value="rejected">
                    <button type="submit" class="w-full py-2 rounded-lg font-label-md bg-error text-white hover:bg-error/90 transition-all shadow-sm flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined text-[16px]">person_remove</span> Keluarkan Siswa
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openDetailModal(id, student_id, name, className, email, phone, gender, ekskul, status, absen) {
            document.getElementById('modalName').textContent = name;
            document.getElementById('modalInitial').textContent = name.charAt(0).toUpperCase();
            document.getElementById('modalClass').textContent = 'Kelas ' + className;
            document.getElementById('modalEmail').textContent = email;
            document.getElementById('modalPhone').textContent = phone;
            document.getElementById('modalGender').textContent = gender === 'L' ? 'Laki-Laki' : (gender === 'P' ? 'Perempuan' : '-');
            document.getElementById('modalEkskul').textContent = ekskul;
            document.getElementById('modalAbsen').textContent = absen;
            
            const statusEl = document.getElementById('modalStatus');
            if(status === 'approved') {
                statusEl.textContent = 'AKTIF';
                statusEl.className = 'mt-2 px-3 py-1 rounded-full text-[10px] font-bold bg-[#10B981]/10 text-[#10B981]';
            } else if(status === 'pending') {
                statusEl.textContent = 'PENDING';
                statusEl.className = 'mt-2 px-3 py-1 rounded-full text-[10px] font-bold bg-[#F59E0B]/10 text-[#F59E0B]';
            } else {
                statusEl.textContent = 'DITOLAK';
                statusEl.className = 'mt-2 px-3 py-1 rounded-full text-[10px] font-bold bg-[#EF4444]/10 text-[#EF4444]';
            }

            const kickForm = document.getElementById('kickForm');
            if(status === 'rejected') {
                kickForm.style.display = 'none';
            } else {
                kickForm.style.display = 'block';
                // Set the action URL correctly
                const baseUrl = "{{ route('teacher.participants.update', ':id') }}";
                kickForm.action = baseUrl.replace(':id', id);
            }

            const resetForm = document.getElementById('resetPasswordForm');
            if (resetForm) {
                const resetBaseUrl = "{{ route('teacher.participants.reset_password', ':user') }}";
                resetForm.action = resetBaseUrl.replace(':user', student_id);
            }

            const modal = document.getElementById('detailModal');
            const modalInner = modal.querySelector('div');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            // Trigger reflow
            void modal.offsetWidth;
            modal.classList.remove('opacity-0');
            modalInner.classList.remove('scale-95');
        }

        function closeDetailModal() {
            const modal = document.getElementById('detailModal');
            const modalInner = modal.querySelector('div');
            modal.classList.add('opacity-0');
            modalInner.classList.add('scale-95');
            setTimeout(() => {
                modal.classList.remove('flex');
                modal.classList.add('hidden');
            }, 300);
        }
    </script>
</x-app-layout>
