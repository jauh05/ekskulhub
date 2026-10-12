<x-app-layout>
    <x-slot name="title">Dashboard Admin</x-slot>

    <div class="space-y-8">
        <!-- Header -->
        <div>
            <p class="text-body-md text-secondary">Ringkasan pengguna, ekskul, dan pendaftaran.</p>
            <h3 class="mt-1 text-headline-md font-bold text-on-surface md:text-headline-lg">Selamat datang, {{ explode(' ', auth()->user()->name ?? 'Admin')[0] }}</h3>
        </div>

        <!-- Statistik -->
        <div class="grid grid-cols-2 gap-4 lg:grid-cols-4">
            <div class="flex flex-col justify-between gap-8 rounded-xl bg-primary-container p-5 text-on-primary-container">
                <div class="flex items-center justify-between gap-3">
                    <span class="text-label-md opacity-90">Siswa aktif</span>
                    <span class="flex size-9 items-center justify-center rounded-lg bg-on-primary-container/15">
                        <x-icon name="users" class="size-[18px]" />
                    </span>
                </div>
                <p class="text-headline-md font-bold">{{ $totalStudents ?? 0 }}</p>
            </div>

            <div class="flex flex-col justify-between gap-8 rounded-xl border border-outline-variant bg-surface p-5">
                <div class="flex items-center justify-between gap-3">
                    <span class="text-label-md text-secondary">Guru aktif</span>
                    <span class="flex size-9 items-center justify-center rounded-lg bg-secondary-container text-on-secondary-container">
                        <x-icon name="user-round" class="size-[18px]" />
                    </span>
                </div>
                <p class="text-headline-md font-bold text-on-surface">{{ $totalTeachers ?? 0 }}</p>
            </div>

            <div class="flex flex-col justify-between gap-8 rounded-xl border border-outline-variant bg-surface p-5">
                <div class="flex items-center justify-between gap-3">
                    <span class="text-label-md text-secondary">Ekskul aktif</span>
                    <span class="flex size-9 items-center justify-center rounded-lg bg-primary-fixed text-on-primary-fixed">
                        <x-icon name="trophy" class="size-[18px]" />
                    </span>
                </div>
                <p class="text-headline-md font-bold text-on-surface">{{ $totalEkskul ?? 0 }}</p>
            </div>

            <div class="flex flex-col justify-between gap-8 rounded-xl bg-warning-container p-5 text-on-warning-container">
                <div class="flex items-center justify-between gap-3">
                    <span class="text-label-md">Pendaftaran pending</span>
                    <span class="flex size-9 items-center justify-center rounded-lg bg-surface/60">
                        <x-icon name="hourglass" class="size-[18px]" />
                    </span>
                </div>
                <p class="text-headline-md font-bold">{{ $pendingRegistrations ?? 0 }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-8 xl:grid-cols-3">
            <!-- Kolom utama -->
            <div class="space-y-8 xl:col-span-2">
                <!-- Jadwal terdekat -->
                <section>
                    <h4 class="mb-3 text-title-md font-bold text-on-surface">Jadwal terdekat</h4>
                    <div class="overflow-hidden rounded-xl border border-outline-variant bg-surface">
                        <ul class="divide-y divide-outline-variant">
                            @forelse($upcomingSchedules as $jadwal)
                            <li class="flex items-center gap-4 px-5 py-4">
                                <div class="w-16 shrink-0 text-center">
                                    <p class="text-title-sm font-bold text-on-surface">{{ \Carbon\Carbon::parse($jadwal->start_time)->format('H:i') }}</p>
                                    <p class="text-label-xs text-secondary">{{ \Carbon\Carbon::parse($jadwal->activity_date)->translatedFormat('d M') }}</p>
                                </div>
                                <span class="h-10 w-1 shrink-0 rounded-full bg-primary"></span>
                                <div class="min-w-0 flex-1">
                                    <p class="truncate text-body-md font-semibold text-on-surface">{{ $jadwal->title }}</p>
                                    <p class="truncate text-body-xs text-secondary">{{ $jadwal->extracurricular->name }} &middot; {{ $jadwal->location ?? '-' }}</p>
                                </div>
                            </li>
                            @empty
                            <li class="px-5 py-8 text-center text-body-md text-secondary">Belum ada jadwal yang akan datang.</li>
                            @endforelse
                        </ul>
                    </div>
                </section>

                <!-- Pendaftaran terbaru -->
                <section>
                    <div class="mb-3 flex items-center justify-between">
                        <h4 class="text-title-md font-bold text-on-surface">Pendaftaran terbaru</h4>
                        <a href="{{ route('admin.registrations.index') }}" class="inline-flex items-center gap-1 text-label-md font-bold text-primary-container hover:underline">
                            Lihat semua <x-icon name="arrow-right" class="size-[16px]" />
                        </a>
                    </div>
                    <div class="overflow-hidden rounded-xl border border-outline-variant bg-surface">
                        <ul class="divide-y divide-outline-variant">
                            @forelse($recentRegistrations as $reg)
                            <li class="flex items-center gap-4 px-5 py-4">
                                <div class="flex size-10 shrink-0 items-center justify-center rounded-full bg-primary-fixed text-label-md font-bold text-on-primary-fixed">
                                    {{ strtoupper(substr($reg->student->name ?? 'A', 0, 1)) }}
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="truncate text-body-md font-semibold text-on-surface">{{ $reg->student->name ?? '-' }}</p>
                                    <p class="truncate text-body-xs text-secondary">{{ $reg->extracurricular->name ?? '-' }} &middot; {{ $reg->created_at->diffForHumans() }}</p>
                                </div>
                                @if($reg->status === 'approved')
                                    <span class="shrink-0 rounded-full bg-tertiary-container px-2.5 py-0.5 text-label-xs font-bold text-on-tertiary-container">Disetujui</span>
                                @elseif($reg->status === 'pending')
                                    <span class="shrink-0 rounded-full bg-surface-container px-2.5 py-0.5 text-label-xs font-bold text-on-surface-variant">Pending</span>
                                @else
                                    <span class="shrink-0 rounded-full bg-error-container px-2.5 py-0.5 text-label-xs font-bold text-on-error-container">Ditolak</span>
                                @endif
                            </li>
                            @empty
                            <li class="px-5 py-8 text-center text-body-md text-secondary">Belum ada pendaftaran.</li>
                            @endforelse
                        </ul>
                    </div>
                </section>
            </div>

            <!-- Kolom samping -->
            <div class="space-y-8">
                <!-- Aksi cepat -->
                <section>
                    <h4 class="mb-3 text-title-md font-bold text-on-surface">Kelola</h4>
                    <div class="overflow-hidden rounded-xl border border-outline-variant bg-surface">
                        <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-4 py-4 transition-colors hover:bg-surface-container-low">
                            <span class="flex size-9 items-center justify-center rounded-lg bg-primary-fixed text-on-primary-fixed"><x-icon name="users" class="size-[18px]" /></span>
                            <span class="flex-1 text-label-lg text-on-surface">Data pengguna</span>
                            <x-icon name="chevron-right" class="size-[18px] text-outline" />
                        </a>
                        <a href="{{ route('admin.extracurriculars.index') }}" class="flex items-center gap-3 border-t border-outline-variant px-4 py-4 transition-colors hover:bg-surface-container-low">
                            <span class="flex size-9 items-center justify-center rounded-lg bg-primary-fixed text-on-primary-fixed"><x-icon name="trophy" class="size-[18px]" /></span>
                            <span class="flex-1 text-label-lg text-on-surface">Data ekskul</span>
                            <x-icon name="chevron-right" class="size-[18px] text-outline" />
                        </a>
                        <a href="{{ route('admin.registrations.index') }}" class="flex items-center gap-3 border-t border-outline-variant px-4 py-4 transition-colors hover:bg-surface-container-low">
                            <span class="flex size-9 items-center justify-center rounded-lg bg-primary-fixed text-on-primary-fixed"><x-icon name="clipboard-list" class="size-[18px]" /></span>
                            <span class="flex-1 text-label-lg text-on-surface">Pendaftaran</span>
                            <x-icon name="chevron-right" class="size-[18px] text-outline" />
                        </a>
                    </div>
                </section>

                <!-- Pengumuman terbaru -->
                <section>
                    <h4 class="mb-3 text-title-md font-bold text-on-surface">Pengumuman terbaru</h4>
                    <div class="overflow-hidden rounded-xl border border-outline-variant bg-surface">
                        <ul class="divide-y divide-outline-variant">
                            @forelse($announcements as $announcement)
                            <li class="flex gap-3 px-4 py-4">
                                <span class="flex size-9 shrink-0 items-center justify-center rounded-full bg-primary-fixed text-on-primary-fixed"><x-icon name="megaphone" class="size-[16px]" /></span>
                                <div class="min-w-0">
                                    <p class="truncate text-label-md font-semibold text-on-surface">{{ $announcement->title }}</p>
                                    <p class="text-body-xs text-secondary">{{ $announcement->extracurricular->name ?? '-' }} &middot; {{ $announcement->created_at->diffForHumans() }}</p>
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
            </div>
        </div>
    </div>
</x-app-layout>
