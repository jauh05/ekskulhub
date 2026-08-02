<x-app-layout>
    <div class="pt-6 px-8 pb-12">
        <!-- Welcome Header -->
        <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 bg-primary rounded-full flex items-center justify-center shrink-0 shadow-sm">
                    <span class="material-symbols-outlined text-[32px] text-white">waving_hand</span>
                </div>
                <div>
                    <h3 class="font-headline-lg text-headline-lg font-bold text-on-surface">Selamat Datang, {{ explode(' ', auth()->user()->name ?? 'Guru')[0] }}!</h3>
                    <p class="text-body-lg text-secondary flex items-center gap-2 mt-1">
                        <span class="material-symbols-outlined text-[18px]">insights</span>
                        Pantau perkembangan siswa dan jadwal kegiatan hari ini.
                    </p>
                </div>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('teacher.schedules.create') }}" class="bg-primary text-on-primary px-5 py-2.5 rounded-lg font-label-md hover:bg-primary/90 transition-colors flex items-center gap-2 shadow-md">
                    <span class="material-symbols-outlined text-[20px]">add_task</span>
                    Buat Jadwal Baru
                </a>
            </div>
        </div>

        <!-- Ringkasan Statistik (1 Baris Kanan Kiri) -->
        <div class="bg-white rounded-xl border border-outline-variant card-shadow mb-8 overflow-x-auto">
            <div class="flex flex-row items-center divide-x divide-outline-variant min-w-max w-full">
                
                <div class="flex-1 p-4 flex flex-row items-center justify-center gap-3">
                    <div class="w-12 h-12 bg-primary/10 text-primary rounded-full flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-[24px]">group</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-label-md text-secondary font-medium">Total Peserta:</span>
                        <div class="flex items-center gap-2">
                            <span class="text-title-lg font-bold text-on-surface leading-none">{{ $totalParticipants ?? 0 }}</span>
                            <span class="text-label-sm text-tertiary flex items-center font-bold bg-tertiary/10 px-1.5 py-0.5 rounded text-[10px]">
                                <span class="material-symbols-outlined text-[12px] mr-0.5">trending_up</span> 12%
                            </span>
                        </div>
                    </div>
                </div>

                <div class="flex-1 p-4 flex flex-row items-center justify-center gap-3">
                    <div class="w-12 h-12 bg-secondary-container text-on-secondary-container rounded-full flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-[24px]">event_available</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-label-md text-secondary font-medium">Sesi Bulan Ini:</span>
                        <span class="text-title-lg font-bold text-on-surface leading-none">{{ $schedulesThisMonth ?? 0 }}</span>
                    </div>
                </div>

                <div class="flex-1 p-4 flex flex-row items-center justify-center gap-3">
                    <div class="w-12 h-12 bg-[#10B981]/10 text-[#10B981] rounded-full flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-[24px]">how_to_reg</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-label-md text-secondary font-medium">Hadir Hari Ini:</span>
                        <span class="text-title-lg font-bold text-on-surface leading-none">{{ $presentToday }}</span>
                    </div>
                </div>

                <div class="flex-1 p-4 flex flex-row items-center justify-center gap-3">
                    <div class="w-12 h-12 bg-error/10 text-error rounded-full flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-[24px]">warning</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-label-md text-error font-bold">Sesi Presensi Aktif:</span>
                        <span class="text-title-lg font-bold text-error leading-none">{{ $pendingSessions }}</span>
                    </div>
                </div>

            </div>
        </div>

        <!-- Layout Grid: Main Content -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column: Jadwal Terdekat & Peserta -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Jadwal Terdekat -->
                <section>
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="font-title-lg text-title-lg font-bold text-on-surface flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary">event_upcoming</span>
                            Jadwal Terdekat
                        </h4>
                        <a class="text-primary font-label-md font-bold hover:underline flex items-center gap-1" href="{{ route('teacher.schedules.index') }}">
                            Lihat Semua <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                        </a>
                    </div>
                    
                    @forelse($upcomingSchedules as $jadwal)
                    <div class="bg-white rounded-xl border border-outline-variant card-shadow overflow-hidden mb-6">
                        <div class="p-8">
                            <div class="flex flex-col md:flex-row justify-between gap-6">
                                <div class="space-y-4">
                                    <div>
                                        <span class="px-3 py-1 bg-primary-container text-on-primary-container rounded-full text-label-sm font-bold flex items-center gap-1 inline-flex">
                                            <span class="material-symbols-outlined text-[14px]">schedule</span> SESI MENDATANG
                                        </span>
                                        <h5 class="font-headline-md text-headline-md font-bold text-on-surface mt-3">{{ $jadwal->title }}</h5>
                                        <p class="text-body-lg text-secondary">{{ $jadwal->extracurricular->name }}</p>
                                    </div>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                                        <div class="flex items-center gap-3 text-on-surface-variant">
                                            <div class="w-10 h-10 bg-surface-container rounded-lg flex items-center justify-center">
                                                <span class="material-symbols-outlined text-primary">calendar_month</span>
                                            </div>
                                            <div>
                                                <p class="text-label-sm text-secondary">Tanggal</p>
                                                <p class="font-body-md font-semibold">{{ \Carbon\Carbon::parse($jadwal->activity_date)->translatedFormat('l, d M Y') }}</p>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-3 text-on-surface-variant">
                                            <div class="w-10 h-10 bg-surface-container rounded-lg flex items-center justify-center">
                                                <span class="material-symbols-outlined text-primary">schedule</span>
                                            </div>
                                            <div>
                                                <p class="text-label-sm text-secondary">Waktu</p>
                                                <p class="font-body-md font-semibold">{{ \Carbon\Carbon::parse($jadwal->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($jadwal->end_time)->format('H:i') }} WIB</p>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-3 text-on-surface-variant">
                                            <div class="w-10 h-10 bg-surface-container rounded-lg flex items-center justify-center">
                                                <span class="material-symbols-outlined text-primary">location_on</span>
                                            </div>
                                            <div>
                                                <p class="text-label-sm text-secondary">Lokasi</p>
                                                <p class="font-body-md font-semibold">{{ $jadwal->location ?? '-' }}</p>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-3 text-on-surface-variant">
                                            <div class="w-10 h-10 bg-surface-container rounded-lg flex items-center justify-center">
                                                <span class="material-symbols-outlined text-primary">menu_book</span>
                                            </div>
                                            <div>
                                                <p class="text-label-sm text-secondary">Materi</p>
                                                <p class="font-body-md font-semibold">{{ $jadwal->material ?? '-' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="w-full md:w-64 h-48 rounded-xl overflow-hidden border border-outline-variant shrink-0 bg-surface-container flex flex-col items-center justify-center text-primary/30">
                                    <span class="material-symbols-outlined text-6xl">event</span>
                                </div>
                            </div>
                            <div class="flex flex-wrap gap-4 mt-8 pt-6 border-t border-outline-variant">
                                <a href="{{ route('teacher.attendances.index') }}" class="bg-primary text-white px-8 py-3 rounded-lg font-body-md font-bold shadow-lg shadow-primary/20 hover:bg-primary/90 transition-all active:scale-95 flex items-center gap-2">
                                    <span class="material-symbols-outlined text-[20px]">assignment_turned_in</span> Buka Absensi
                                </a>
                                <a href="{{ route('teacher.schedules.edit', $jadwal->id) }}" class="bg-surface-container-low text-primary px-8 py-3 rounded-lg font-body-md font-bold hover:bg-surface-container-high transition-all active:scale-95 flex items-center gap-2 border border-primary/10">
                                    <span class="material-symbols-outlined text-[20px]">edit</span> Edit Jadwal
                                </a>
                                <form action="{{ route('teacher.schedules.destroy', $jadwal->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus jadwal ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-error/10 text-error px-4 py-3 rounded-lg font-body-md font-bold hover:bg-error/20 transition-all active:scale-95 flex items-center gap-2">
                                        <span class="material-symbols-outlined text-[20px]">delete</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="bg-white rounded-xl border border-outline-variant card-shadow p-8 text-center flex flex-col items-center justify-center">
                        <div class="w-16 h-16 bg-surface-container rounded-full flex items-center justify-center text-outline mb-4">
                            <span class="material-symbols-outlined text-3xl">event_busy</span>
                        </div>
                        <h5 class="text-title-md font-bold text-on-surface mb-2">Belum ada jadwal terdekat</h5>
                        <p class="text-body-md text-secondary max-w-md">Anda belum membuat jadwal latihan untuk waktu dekat. Silakan buat jadwal baru terlebih dahulu.</p>
                        <a href="{{ route('teacher.schedules.create') }}" class="mt-6 bg-primary text-on-primary px-6 py-2.5 rounded-lg font-label-md hover:bg-primary/90 transition-colors flex items-center gap-2">
                            <span class="material-symbols-outlined text-[20px]">add</span> Buat Jadwal
                        </a>
                    </div>
                    @endforelse
                </section>

                <!-- Peserta Terbaru -->
                <section>
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="font-title-lg text-title-lg font-bold text-on-surface flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary">recent_actors</span>
                            Peserta Terdaftar Terbaru
                        </h4>
                        <button class="text-on-surface-variant hover:text-primary p-1 rounded-full transition-colors">
                            <span class="material-symbols-outlined">more_horiz</span>
                        </button>
                    </div>
                    <div class="bg-white rounded-xl border border-outline-variant card-shadow overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse min-w-max">
                                <thead>
                                    <tr class="bg-surface-container-low border-b border-outline-variant">
                                        <th class="px-6 py-4 text-label-sm font-bold text-secondary uppercase tracking-wider">Nama Siswa</th>
                                        <th class="px-6 py-4 text-label-sm font-bold text-secondary uppercase tracking-wider">Ekskul</th>
                                        <th class="px-6 py-4 text-label-sm font-bold text-secondary uppercase tracking-wider">Bergabung</th>
                                        <th class="px-6 py-4 text-label-sm font-bold text-secondary uppercase tracking-wider text-right">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-outline-variant">
                                    @forelse($recentParticipants as $participant)
                                    <tr class="hover:bg-surface-container-low transition-colors group">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 rounded-full bg-primary-fixed flex items-center justify-center font-bold text-on-primary-fixed">
                                                    {{ strtoupper(substr($participant->student->name ?? 'A', 0, 1)) }}
                                                </div>
                                                <div>
                                                    <p class="font-body-md font-bold text-on-surface">{{ $participant->student->name ?? '-' }}</p>
                                                    <p class="text-label-sm text-secondary">ID: {{ $participant->student->id ?? '-' }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 font-body-md text-on-surface-variant">{{ $participant->extracurricular->name ?? '-' }}</td>
                                        <td class="px-6 py-4">
                                            <div class="flex flex-col gap-1">
                                                <div class="flex items-center gap-2">
                                                    @if($participant->status === 'approved')
                                                        <span class="px-2 py-1 bg-[#10B981]/10 text-[#10B981] text-[10px] font-bold rounded-full uppercase">Aktif</span>
                                                    @elseif($participant->status === 'pending')
                                                        <span class="px-2 py-1 bg-surface-variant text-on-surface-variant text-[10px] font-bold rounded-full uppercase">Pending</span>
                                                    @else
                                                        <span class="px-2 py-1 bg-error/10 text-error text-[10px] font-bold rounded-full uppercase">Ditolak</span>
                                                    @endif
                                                    <span class="text-label-sm text-secondary">{{ $participant->created_at->diffForHumans() }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            @if($participant->status === 'pending')
                                                <div class="flex items-center justify-end gap-2">
                                                    <form action="{{ route('teacher.participants.update', $participant->id) }}" method="POST">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="hidden" name="status" value="approved">
                                                        <button type="submit" class="bg-[#10B981]/10 text-[#10B981] p-2 hover:bg-[#10B981]/20 rounded-full transition-colors flex items-center justify-center" title="Terima Siswa">
                                                            <span class="material-symbols-outlined text-[18px]">check</span>
                                                        </button>
                                                    </form>
                                                    <form action="{{ route('teacher.participants.update', $participant->id) }}" method="POST">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="hidden" name="status" value="rejected">
                                                        <button type="submit" class="bg-error/10 text-error p-2 hover:bg-error/20 rounded-full transition-colors flex items-center justify-center" title="Tolak Siswa">
                                                            <span class="material-symbols-outlined text-[18px]">close</span>
                                                        </button>
                                                    </form>
                                                </div>
                                            @else
                                                <a href="{{ route('teacher.participants.index') }}" class="text-primary p-2 hover:bg-primary-container/10 rounded-full transition-colors inline-block">
                                                    <span class="material-symbols-outlined">chevron_right</span>
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-8 text-center text-secondary font-body-md">Belum ada siswa yang mendaftar ke ekstrakurikuler Anda.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="p-4 text-center border-t border-outline-variant">
                            <a href="{{ route('teacher.participants.index') }}" class="text-primary font-label-md font-bold hover:underline flex items-center justify-center gap-1 mx-auto">
                                <span class="material-symbols-outlined text-[18px]">group_add</span> Lihat Semua Peserta
                            </a>
                        </div>
                    </div>
                </section>
            </div>

            <!-- Right Column: Quick Actions & Updates -->
            <div class="space-y-8">
                <!-- Manajemen Kode Kelas -->
                <section class="mb-8">
                    <h4 class="font-title-lg text-title-lg font-bold text-on-surface mb-4 flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">pin</span> Manajemen Kode Kelas
                    </h4>
                    @foreach($extracurriculars as $ekskul)
                    <div class="bg-white rounded-xl border border-outline-variant card-shadow p-6 mb-4">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h5 class="font-headline-sm font-bold text-on-surface flex items-center gap-2">
                                    <span class="material-symbols-outlined text-secondary text-[20px]">sports_soccer</span> {{ $ekskul->name }}
                                </h5>
                                <p class="text-label-sm text-secondary">Bagikan kode ini ke siswa</p>
                            </div>
                            <div class="bg-primary-container text-on-primary-container px-3 py-1 rounded-md font-mono font-bold tracking-wider">
                                {{ $ekskul->class_code ?? 'BELUM DIATUR' }}
                            </div>
                        </div>
                        
                        <form action="{{ route('teacher.class_code.update', $ekskul->id) }}" method="POST" class="flex gap-2">
                            @csrf
                            <input type="text" name="class_code" value="{{ $ekskul->class_code }}" placeholder="Kode Baru" class="flex-grow h-11 px-4 bg-surface-container-low rounded-lg outline-none focus:ring-2 focus:ring-primary/20 border border-outline-variant transition-all font-mono uppercase" required>
                            <button type="submit" class="bg-primary text-on-primary px-6 rounded-lg font-label-md hover:bg-primary/90 transition-colors flex items-center gap-2">
                                <span class="material-symbols-outlined text-[18px]">save</span> Simpan
                            </button>
                        </form>
                        @error('class_code')
                            <p class="text-error text-[12px] mt-1 flex items-center gap-1"><span class="material-symbols-outlined text-[14px]">error</span> {{ $message }}</p>
                        @enderror
                    </div>
                    @endforeach
                </section>

                <!-- Aksi Cepat -->
                <section>
                    <h4 class="font-title-lg text-title-lg font-bold text-on-surface mb-4 flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">bolt</span> Aksi Cepat
                    </h4>
                    <div class="grid grid-cols-2 gap-4">
                        <a href="{{ route('teacher.schedules.create') }}" class="bg-white p-4 rounded-xl border border-outline-variant card-shadow flex flex-col items-center justify-center gap-3 text-center group hover:border-primary transition-all active:scale-95">
                            <div class="w-12 h-12 bg-primary-container text-on-primary-container rounded-full flex items-center justify-center group-hover:scale-110 transition-transform">
                                <span class="material-symbols-outlined">add_circle</span>
                            </div>
                            <span class="text-label-md font-bold text-on-surface">Buat Jadwal</span>
                        </a>
                        <a href="{{ route('teacher.attendances.index') }}" class="bg-white p-4 rounded-xl border border-outline-variant card-shadow flex flex-col items-center justify-center gap-3 text-center group hover:border-primary transition-all active:scale-95">
                            <div class="w-12 h-12 bg-tertiary-container text-on-tertiary-container rounded-full flex items-center justify-center group-hover:scale-110 transition-transform">
                                <span class="material-symbols-outlined">checklist</span>
                            </div>
                            <span class="text-label-md font-bold text-on-surface">Buka Absensi</span>
                        </a>
                        <a href="{{ route('teacher.grading.index') }}" class="bg-white p-4 rounded-xl border border-outline-variant card-shadow flex flex-col items-center justify-center gap-3 text-center group hover:border-primary transition-all active:scale-95">
                            <div class="w-12 h-12 bg-surface-container-highest text-primary rounded-full flex items-center justify-center group-hover:scale-110 transition-transform">
                                <span class="material-symbols-outlined">edit_note</span>
                            </div>
                            <span class="text-label-md font-bold text-on-surface">Input Nilai</span>
                        </a>
                        <a href="#" class="bg-white p-4 rounded-xl border border-outline-variant card-shadow flex flex-col items-center justify-center gap-3 text-center group hover:border-primary transition-all active:scale-95">
                            <div class="w-12 h-12 bg-error-container text-on-error-container rounded-full flex items-center justify-center group-hover:scale-110 transition-transform">
                                <span class="material-symbols-outlined">notification_add</span>
                            </div>
                            <span class="text-label-md font-bold text-on-surface">Pengumuman</span>
                        </a>
                    </div>
                </section>

                <!-- Update Terbaru -->
                <section>
                    <h4 class="font-title-lg text-title-lg font-bold text-on-surface mb-4 flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">notifications_active</span> Update Terbaru
                    </h4>
                    <div class="bg-white rounded-xl border border-outline-variant card-shadow p-6 space-y-6">
                        <div class="space-y-4">
                            @forelse($announcements as $announcement)
                            <div class="flex gap-4">
                                <div class="w-10 h-10 rounded-lg bg-surface-container flex-shrink-0 flex items-center justify-center">
                                    <span class="material-symbols-outlined text-primary">campaign</span>
                                </div>
                                <div>
                                    <p class="text-body-md font-bold text-on-surface">{{ $announcement->title }}</p>
                                    <p class="text-label-sm text-secondary line-clamp-2">{{ $announcement->content }}</p>
                                    <p class="text-[10px] text-outline mt-1 font-bold">{{ strtoupper($announcement->created_at->diffForHumans()) }}</p>
                                </div>
                            </div>
                            @empty
                            <div class="flex items-center gap-3 text-secondary p-4 bg-surface-container-lowest rounded-lg border border-outline-variant">
                                <span class="material-symbols-outlined text-[20px]">notifications_paused</span>
                                <p class="text-label-sm">Belum ada pengumuman terbaru.</p>
                            </div>
                            @endforelse
                        </div>
                        <button class="w-full py-2.5 rounded-lg border border-outline-variant text-label-md font-bold text-secondary hover:bg-surface-container-low transition-colors flex justify-center items-center gap-2">
                            <span class="material-symbols-outlined text-[18px]">history</span> Lihat Semua
                        </button>
                    </div>
                </section>
                
                <!-- Profile Quick View -->
                <section>
                    <div class="bg-primary rounded-xl p-6 text-white relative overflow-hidden shadow-xl shadow-primary/30">
                        <div class="relative z-10">
                            <p class="text-label-sm font-bold opacity-80 uppercase flex items-center gap-1"><span class="material-symbols-outlined text-[14px]">stars</span> Status Akun</p>
                            <h5 class="text-title-lg font-bold mt-1">Pembina Senior</h5>
                            <div class="flex items-center gap-2 mt-4">
                                <span class="material-symbols-outlined text-[18px]">verified</span>
                                <p class="text-label-md font-semibold">Tervalidasi Kemdikbud</p>
                            </div>
                        </div>
                        <!-- Atmospheric Background Shape -->
                        <div class="absolute -bottom-6 -right-6 w-32 h-32 bg-white opacity-10 rounded-full"></div>
                        <div class="absolute -top-6 -right-2 w-20 h-20 bg-white opacity-5 rounded-full"></div>
                    </div>
                </section>
            </div>
        </div>
    </div>
</x-app-layout>
