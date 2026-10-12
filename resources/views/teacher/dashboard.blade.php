<x-app-layout>
    <x-slot name="title">Dashboard Guru</x-slot>
    @php
        $statistikGrafik = [
            ['label' => 'Peserta', 'value' => (int) ($totalParticipants ?? 0)],
            ['label' => 'Sesi bulan ini', 'value' => (int) ($schedulesThisMonth ?? 0)],
            ['label' => 'Hadir hari ini', 'value' => (int) ($presentToday ?? 0)],
            ['label' => 'Sesi aktif', 'value' => (int) ($pendingSessions ?? 0)],
        ];
        $nilaiMaksGrafik = max(1, max(array_column($statistikGrafik, 'value')));
    @endphp

    <div class="space-y-8">
        <!-- Header -->
        <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
            <div>
                <p class="text-body-md text-secondary">Pantau perkembangan siswa dan jadwal kegiatan hari ini.</p>
                <h3 class="mt-1 text-headline-md font-bold text-on-surface md:text-headline-lg">Selamat datang, {{ explode(' ', auth()->user()->name ?? 'Guru')[0] }}</h3>
            </div>
            <a href="{{ route('teacher.schedules.create') }}" class="inline-flex w-full items-center justify-center gap-2 rounded-lg bg-primary-container px-4 py-2.5 text-label-lg text-on-primary-container transition-opacity hover:opacity-90 md:w-auto">
                <x-icon name="list-plus" class="size-[20px]" />
                Buat Jadwal Baru
            </a>
        </div>

        <!-- Statistik -->
        <div class="grid grid-cols-2 gap-4 lg:grid-cols-4">
            <div class="flex flex-col justify-between gap-8 rounded-xl bg-primary-container p-5 text-on-primary-container">
                <div class="flex items-center justify-between gap-3">
                    <span class="text-label-md opacity-90">Total peserta</span>
                    <span class="flex size-9 items-center justify-center rounded-lg bg-on-primary-container/15">
                        <x-icon name="users" class="size-[18px]" />
                    </span>
                </div>
                <p class="text-headline-md font-bold">{{ $totalParticipants ?? 0 }}</p>
            </div>

            <div class="flex flex-col justify-between gap-8 rounded-xl border border-outline-variant bg-surface p-5">
                <div class="flex items-center justify-between gap-3">
                    <span class="text-label-md text-secondary">Sesi bulan ini</span>
                    <span class="flex size-9 items-center justify-center rounded-lg bg-secondary-container text-on-secondary-container">
                        <x-icon name="calendar-check" class="size-[18px]" />
                    </span>
                </div>
                <p class="text-headline-md font-bold text-on-surface">{{ $schedulesThisMonth ?? 0 }}</p>
            </div>

            <div class="flex flex-col justify-between gap-8 rounded-xl border border-outline-variant bg-surface p-5">
                <div class="flex items-center justify-between gap-3">
                    <span class="text-label-md text-secondary">Hadir hari ini</span>
                    <span class="flex size-9 items-center justify-center rounded-lg bg-tertiary-container text-on-tertiary-container">
                        <x-icon name="user-check" class="size-[18px]" />
                    </span>
                </div>
                <p class="text-headline-md font-bold text-on-surface">{{ $presentToday ?? 0 }}</p>
            </div>

            <div class="flex flex-col justify-between gap-8 rounded-xl border border-outline-variant bg-surface p-5">
                <div class="flex items-center justify-between gap-3">
                    <span class="text-label-md text-secondary">Sesi presensi aktif</span>
                    <span class="flex size-9 items-center justify-center rounded-lg bg-primary-fixed text-on-primary-fixed">
                        <x-icon name="radio" class="size-[18px]" />
                    </span>
                </div>
                <p class="text-headline-md font-bold text-on-surface">{{ $pendingSessions ?? 0 }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-8 xl:grid-cols-3">
            <!-- Kolom utama -->
            <div class="space-y-8 xl:col-span-2">
                <!-- Ringkasan -->
                <section class="rounded-xl border border-outline-variant bg-surface p-5">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <h4 class="text-title-md font-bold text-on-surface">Ringkasan presensi</h4>
                            <p class="text-label-sm text-secondary">Perbandingan angka utama presensi Anda</p>
                        </div>
                    </div>
                    <div class="mt-6 grid grid-cols-4 gap-3 sm:gap-6">
                        @foreach($statistikGrafik as $item)
                        <div class="flex flex-col items-center gap-2">
                            <span class="text-title-sm font-bold text-on-surface">{{ $item['value'] }}</span>
                            <div class="flex h-36 w-full max-w-16 items-end rounded-md bg-surface-container-low">
                                <div class="w-full rounded-md bg-primary" style="height: {{ max(4, round($item['value'] / $nilaiMaksGrafik * 100)) }}%"></div>
                            </div>
                            <span class="text-center text-label-xs leading-tight text-secondary">{{ $item['label'] }}</span>
                        </div>
                        @endforeach
                    </div>
                </section>

                <!-- Jadwal terdekat -->
                <section>
                    <div class="mb-3 flex items-center justify-between">
                        <h4 class="text-title-md font-bold text-on-surface">Jadwal terdekat</h4>
                        <a href="{{ route('teacher.schedules.index') }}" class="inline-flex items-center gap-1 text-label-md font-bold text-primary-container hover:underline">
                            Lihat semua <x-icon name="arrow-right" class="size-[16px]" />
                        </a>
                    </div>

                    @forelse($upcomingSchedules as $jadwal)
                    @php $tanggalJadwal = \Carbon\Carbon::parse($jadwal->activity_date); @endphp
                    <article class="flex flex-col gap-5 rounded-xl border border-outline-variant bg-surface p-5 sm:flex-row">
                        <div class="flex w-full shrink-0 flex-row items-center justify-center gap-3 rounded-xl bg-primary-container px-4 py-3 text-on-primary-container sm:w-20 sm:flex-col sm:gap-0 sm:py-4">
                            <span class="text-label-xs font-bold uppercase tracking-wider">{{ $tanggalJadwal->translatedFormat('M') }}</span>
                            <span class="text-headline-md font-bold">{{ $tanggalJadwal->format('d') }}</span>
                        </div>
                        <div class="min-w-0 flex-1">
                            <span class="inline-flex rounded-full bg-primary-fixed px-2.5 py-0.5 text-label-xs font-bold text-on-primary-fixed">Sesi mendatang</span>
                            <h5 class="mt-2 text-title-lg font-bold text-on-surface">{{ $jadwal->title }}</h5>
                            <p class="text-body-sm text-secondary">{{ $jadwal->extracurricular->name }}</p>
                            <div class="mt-3 flex flex-wrap gap-x-5 gap-y-2 text-body-sm text-on-surface-variant">
                                <span class="inline-flex items-center gap-1.5"><x-icon name="clock" class="size-[16px] text-secondary" />{{ \Carbon\Carbon::parse($jadwal->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($jadwal->end_time)->format('H:i') }} WIB</span>
                                <span class="inline-flex items-center gap-1.5"><x-icon name="map-pin" class="size-[16px] text-secondary" />{{ $jadwal->location ?? '-' }}</span>
                                @if($jadwal->material)
                                <span class="inline-flex items-center gap-1.5"><x-icon name="book-open" class="size-[16px] text-secondary" />{{ $jadwal->material }}</span>
                                @endif
                            </div>
                            <div class="mt-5 flex flex-wrap items-center gap-2">
                                <a href="{{ route('teacher.attendances.index') }}" class="inline-flex items-center gap-2 rounded-lg bg-primary-container px-4 py-2 text-label-lg text-on-primary-container transition-opacity hover:opacity-90">
                                    <x-icon name="clipboard-check" class="size-[18px]" /> Buka absensi
                                </a>
                                <a href="{{ route('teacher.schedules.edit', $jadwal->id) }}" class="inline-flex items-center gap-2 rounded-lg border border-outline-variant bg-surface px-4 py-2 text-label-lg text-on-surface transition-colors hover:bg-surface-container">
                                    <x-icon name="pencil" class="size-[18px]" /> Edit
                                </a>
                                <form action="{{ route('teacher.schedules.destroy', $jadwal->id) }}" method="POST" onsubmit="confirmAction(event, 'Hapus jadwal ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" aria-label="Hapus jadwal" class="inline-flex size-9 items-center justify-center rounded-lg text-error transition-colors hover:bg-error-container/50">
                                        <x-icon name="trash-2" class="size-[18px]" />
                                    </button>
                                </form>
                            </div>
                        </div>
                    </article>
                    @empty
                    <div class="flex flex-col items-start gap-2 rounded-xl border border-dashed border-outline-variant bg-surface p-6">
                        <p class="text-title-sm font-bold text-on-surface">Belum ada jadwal terdekat</p>
                        <p class="text-body-sm text-secondary">Buat jadwal latihan untuk membuka sesi absensi.</p>
                        <a href="{{ route('teacher.schedules.create') }}" class="mt-2 inline-flex items-center gap-2 text-label-lg text-primary-container hover:underline">
                            <x-icon name="plus" class="size-[18px]" /> Buat jadwal
                        </a>
                    </div>
                    @endforelse
                </section>

                <!-- Peserta terbaru -->
                <section>
                    <div class="mb-3 flex items-center justify-between">
                        <h4 class="text-title-md font-bold text-on-surface">Peserta terbaru</h4>
                        <a href="{{ route('teacher.participants.index') }}" class="inline-flex items-center gap-1 text-label-md font-bold text-primary-container hover:underline">
                            Lihat semua <x-icon name="arrow-right" class="size-[16px]" />
                        </a>
                    </div>
                    <div class="overflow-hidden rounded-xl border border-outline-variant bg-surface">
                        <ul class="divide-y divide-outline-variant">
                            @forelse($recentParticipants as $participant)
                            <li class="flex items-center gap-4 px-5 py-4">
                                <div class="flex size-10 shrink-0 items-center justify-center rounded-full bg-primary-fixed text-label-md font-bold text-on-primary-fixed">
                                    {{ strtoupper(substr($participant->student->name ?? 'A', 0, 1)) }}
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="truncate text-body-md font-semibold text-on-surface">{{ $participant->student->name ?? '-' }}</p>
                                    <p class="truncate text-body-xs text-secondary">{{ $participant->extracurricular->name ?? '-' }} &middot; {{ $participant->created_at->diffForHumans() }}</p>
                                </div>
                                @if($participant->status === 'approved')
                                    <span class="shrink-0 rounded-full bg-tertiary-container px-2.5 py-0.5 text-label-xs font-bold text-on-tertiary-container">Aktif</span>
                                @elseif($participant->status === 'pending')
                                    <div class="flex shrink-0 items-center gap-2">
                                        <form action="{{ route('teacher.participants.update', $participant->id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="approved">
                                            <button type="submit" title="Terima siswa" aria-label="Terima siswa" class="flex size-9 items-center justify-center rounded-full bg-tertiary-container text-on-tertiary-container transition-opacity hover:opacity-90">
                                                <x-icon name="check" class="size-[18px]" />
                                            </button>
                                        </form>
                                        <form action="{{ route('teacher.participants.update', $participant->id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="rejected">
                                            <button type="submit" title="Tolak siswa" aria-label="Tolak siswa" class="flex size-9 items-center justify-center rounded-full bg-error-container text-on-error-container transition-opacity hover:opacity-90">
                                                <x-icon name="x" class="size-[18px]" />
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    <span class="shrink-0 rounded-full bg-error-container px-2.5 py-0.5 text-label-xs font-bold text-on-error-container">Ditolak</span>
                                @endif
                            </li>
                            @empty
                            <li class="px-5 py-8 text-center text-body-md text-secondary">Belum ada siswa yang mendaftar ke ekstrakurikuler Anda.</li>
                            @endforelse
                        </ul>
                    </div>
                </section>
            </div>

            <!-- Kolom samping -->
            <div class="space-y-8">
                <!-- Kode kelas -->
                <section class="space-y-4">
                    <h4 class="flex items-center gap-2 text-title-md font-bold text-on-surface">
                        <x-icon name="pin" class="size-[18px] text-primary-container" /> Kode kelas
                    </h4>
                    @foreach($extracurriculars as $ekskul)
                    <div class="overflow-hidden rounded-xl border border-outline-variant bg-surface">
                        <div class="flex items-center gap-3 bg-primary-fixed px-5 py-4 text-on-primary-fixed">
                            <x-icon name="trophy" class="size-[20px] shrink-0" />
                            <div class="min-w-0 flex-1">
                                <p class="truncate text-title-sm font-bold">{{ $ekskul->name }}</p>
                                <p class="text-label-xs opacity-80">Bagikan kode ini ke siswa</p>
                            </div>
                            <span class="shrink-0 rounded-md bg-surface px-2.5 py-1 font-mono text-label-md font-bold tracking-wider text-on-surface">{{ $ekskul->class_code ?? 'BELUM DIATUR' }}</span>
                        </div>
                        <form action="{{ route('teacher.class_code.update', $ekskul->id) }}" method="POST" class="flex flex-col gap-2 p-4 sm:flex-row">
                            @csrf
                            <input type="text" name="class_code" value="{{ $ekskul->class_code }}" placeholder="Kode baru" required class="min-w-0 flex-1 rounded-lg border border-outline-variant bg-surface px-3 py-2.5 font-mono text-body-md uppercase text-on-surface placeholder:text-outline focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
                            <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-lg bg-primary-container px-4 py-2.5 text-label-lg text-on-primary-container transition-opacity hover:opacity-90">
                                <x-icon name="save" class="size-[18px]" /> Simpan
                            </button>
                        </form>
                        @error('class_code')
                            <p class="flex items-center gap-1 px-4 pb-4 text-body-xs text-error"><x-icon name="circle-alert" class="size-[14px]" /> {{ $message }}</p>
                        @enderror
                    </div>
                    @endforeach
                </section>

                <!-- Aksi cepat -->
                <section>
                    <h4 class="mb-3 flex items-center gap-2 text-title-md font-bold text-on-surface">
                        <x-icon name="zap" class="size-[18px] text-primary-container" /> Aksi cepat
                    </h4>
                    <div class="grid grid-cols-2 gap-3">
                        <a href="{{ route('teacher.schedules.create') }}" class="flex flex-col gap-3 rounded-xl border border-outline-variant bg-surface p-4 transition-colors hover:bg-surface-container-low">
                            <span class="flex size-9 items-center justify-center rounded-lg bg-primary-fixed text-on-primary-fixed"><x-icon name="circle-plus" class="size-[18px]" /></span>
                            <span class="text-label-md font-semibold text-on-surface">Buat jadwal</span>
                        </a>
                        <a href="{{ route('teacher.attendances.index') }}" class="flex flex-col gap-3 rounded-xl border border-outline-variant bg-surface p-4 transition-colors hover:bg-surface-container-low">
                            <span class="flex size-9 items-center justify-center rounded-lg bg-tertiary-container text-on-tertiary-container"><x-icon name="list-checks" class="size-[18px]" /></span>
                            <span class="text-label-md font-semibold text-on-surface">Buka absensi</span>
                        </a>
                        <a href="{{ route('teacher.grading.index') }}" class="flex flex-col gap-3 rounded-xl border border-outline-variant bg-surface p-4 transition-colors hover:bg-surface-container-low">
                            <span class="flex size-9 items-center justify-center rounded-lg bg-secondary-container text-on-secondary-container"><x-icon name="notebook-pen" class="size-[18px]" /></span>
                            <span class="text-label-md font-semibold text-on-surface">Input nilai</span>
                        </a>
                        <a href="{{ route('teacher.announcements.create') }}" class="flex flex-col gap-3 rounded-xl border border-outline-variant bg-surface p-4 transition-colors hover:bg-surface-container-low">
                            <span class="flex size-9 items-center justify-center rounded-lg bg-warning-container text-on-warning-container"><x-icon name="bell-plus" class="size-[18px]" /></span>
                            <span class="text-label-md font-semibold text-on-surface">Pengumuman</span>
                        </a>
                    </div>
                </section>

                <!-- Pengumuman terbaru -->
                <section>
                    <div class="mb-3 flex items-center justify-between">
                        <h4 class="text-title-md font-bold text-on-surface">Pengumuman terbaru</h4>
                        <a href="{{ route('teacher.announcements.index') }}" class="text-label-md font-bold text-primary-container hover:underline">Semua</a>
                    </div>
                    <div class="overflow-hidden rounded-xl border border-outline-variant bg-surface">
                        <ul class="divide-y divide-outline-variant">
                            @forelse($announcements as $announcement)
                            <li class="flex gap-3 px-4 py-4">
                                <span class="flex size-9 shrink-0 items-center justify-center rounded-full bg-primary-fixed text-on-primary-fixed"><x-icon name="megaphone" class="size-[16px]" /></span>
                                <div class="min-w-0">
                                    <p class="truncate text-label-md font-semibold text-on-surface">{{ $announcement->title }}</p>
                                    <p class="line-clamp-2 text-body-xs text-secondary">{{ $announcement->content }}</p>
                                    <p class="mt-1 text-label-xs text-outline">{{ $announcement->created_at->diffForHumans() }}</p>
                                </div>
                            </li>
                            @empty
                            <li class="flex items-center gap-3 px-4 py-6 text-body-sm text-secondary">
                                <x-icon name="bell-off" class="size-[18px]" /> Belum ada pengumuman.
                            </li>
                            @endforelse
                        </ul>
                    </div>
                </section>

                <!-- Akun -->
                <section class="rounded-xl bg-primary-container p-5 text-on-primary-container">
                    <p class="text-label-xs font-bold uppercase tracking-wider opacity-80">Akun guru</p>
                    <p class="mt-1 text-title-lg font-bold">{{ auth()->user()->name }}</p>
                    <p class="mt-3 text-body-sm opacity-90">NIP: {{ auth()->user()->teacherProfile?->nip ?? '-' }}</p>
                </section>
            </div>
        </div>
    </div>
</x-app-layout>
