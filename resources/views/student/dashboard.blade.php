<x-student-layout>
<div class="flex w-full flex-col gap-6">
    <x-student-active-session-poller />

    @php
        $namaDepan = explode(' ', trim(Auth::user()->name))[0];
        $inisial = strtoupper(substr(Auth::user()->name, 0, 1));
        $persenHadir = min(100, max(0, (float) ($attendancePercentage ?? 0)));
        $hariIni = \Carbon\Carbon::now();
        $awalMinggu = $hariIni->copy()->startOfWeek(\Carbon\Carbon::MONDAY);
        $ekskulAktif = $ekskulTerdaftar->where('status', 'approved');
    @endphp

    <!-- Header gelap: sapaan dan kalender minggu ini -->
    <section class="-mx-4 rounded-b-[2rem] bg-dark px-5 pb-10 pt-6 text-surface md:mx-0 md:rounded-2xl md:px-8">
        <div class="flex items-center justify-between gap-3">
            <div class="min-w-0">
                <p class="text-body-sm text-surface/70">Semangat!</p>
                <h2 class="mt-0.5 truncate text-headline-md font-bold">Halo, {{ $namaDepan }}!</h2>
            </div>
            <span class="flex size-11 shrink-0 items-center justify-center rounded-full bg-primary text-title-md font-bold text-on-primary">{{ $inisial }}</span>
        </div>

        <p class="mt-6 text-label-md text-surface/70">{{ $hariIni->translatedFormat('F Y') }}</p>
        <div class="mt-2 grid grid-cols-7 gap-1.5 text-center">
            @for ($i = 0; $i < 7; $i++)
                @php
                    $tanggal = $awalMinggu->copy()->addDays($i);
                    $adalahHariIni = $tanggal->isSameDay($hariIni);
                @endphp
                <div class="flex flex-col items-center gap-1 rounded-xl py-2 {{ $adalahHariIni ? 'bg-primary text-on-primary' : 'border border-surface/15 text-surface' }}">
                    <span class="text-label-xs {{ $adalahHariIni ? 'opacity-90' : 'text-surface/70' }}">{{ $tanggal->translatedFormat('D') }}</span>
                    <span class="text-title-sm font-bold">{{ $tanggal->format('d') }}</span>
                </div>
            @endfor
        </div>
    </section>

    <!-- Kartu ringkas (sedikit menindih header) -->
    <section class="-mt-6 grid grid-cols-2 gap-3 md:mt-0">
        <div class="flex flex-col items-center rounded-2xl border border-outline-variant bg-surface p-4 shadow-md">
            <svg viewBox="0 0 40 24" class="h-auto w-full max-w-[9rem]" aria-hidden="true">
                <path d="M4 20 A16 16 0 0 1 36 20" fill="none" stroke="currentColor" stroke-width="3.5" stroke-linecap="round" class="text-surface-container-high"></path>
                <path d="M4 20 A16 16 0 0 1 36 20" fill="none" stroke="currentColor" stroke-width="3.5" stroke-linecap="round" pathLength="100" stroke-dasharray="{{ $persenHadir }} 100" class="text-primary-container"></path>
            </svg>
            <p class="mt-2 text-headline-sm font-bold text-on-surface">{{ $attendancePercentage ?? 0 }}%</p>
            <p class="text-label-sm text-secondary">Kehadiran</p>
        </div>

        <div class="flex flex-col justify-between gap-6 rounded-2xl border border-outline-variant bg-surface p-4 shadow-md">
            <div class="flex items-center justify-between gap-2">
                <span class="min-w-0 truncate text-label-sm text-secondary">Ekskul aktif</span>
                <span class="flex size-9 shrink-0 items-center justify-center rounded-lg bg-primary-fixed text-on-primary-fixed">
                    <x-icon name="dumbbell" class="size-[18px]" />
                </span>
            </div>
            <p class="text-headline-md font-bold text-on-surface">{{ $totalEkskul }}</p>
        </div>
    </section>

    <!-- Jadwal hari ini -->
    <section class="flex flex-col gap-3">
        <div class="flex items-center justify-between gap-3">
            <h3 class="text-title-md font-bold text-on-surface">Jadwal hari ini</h3>
            <a href="{{ route('student.attendances.index') }}" class="shrink-0 text-label-md font-semibold text-primary-container hover:underline">Riwayat</a>
        </div>

        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
            @forelse($jadwalHariIni as $jadwal)
            <article class="flex min-h-40 flex-col justify-between gap-4 rounded-2xl p-5 {{ $loop->even ? 'bg-accent text-on-accent' : 'bg-primary-container text-on-primary-container' }}">
                <div class="min-w-0">
                    <p class="text-label-xs uppercase tracking-wider opacity-80">Hari ini</p>
                    <h4 class="mt-1 truncate text-title-md font-bold">{{ $jadwal->extracurricular->name }}</h4>
                    <p class="mt-1 flex items-center gap-1 text-body-xs opacity-90">
                        <x-icon name="map-pin" class="size-[14px] shrink-0" />
                        <span class="truncate">{{ $jadwal->location ?? '-' }}</span>
                    </p>
                </div>
                <div class="flex items-end justify-between gap-3">
                    <div class="min-w-0">
                        <p class="text-headline-sm font-bold leading-none">{{ \Carbon\Carbon::parse($jadwal->start_time)->format('H:i') }}</p>
                        <p class="mt-1 text-body-xs opacity-90">s/d {{ \Carbon\Carbon::parse($jadwal->end_time)->format('H:i') }} WIB</p>
                    </div>
                    <a href="{{ route('student.attendances.create', ['schedule_id' => $jadwal->id]) }}" aria-label="Presensi {{ $jadwal->extracurricular->name }}"
                       class="flex size-11 shrink-0 items-center justify-center rounded-full bg-surface text-primary-container transition-opacity hover:opacity-90">
                        <x-icon name="arrow-right" class="size-[20px]" />
                    </a>
                </div>
            </article>
            @empty
            <div class="rounded-2xl border border-dashed border-outline-variant bg-surface p-5 sm:col-span-2">
                <p class="text-title-sm font-bold text-on-surface">Tidak ada jadwal hari ini</p>
                <p class="mt-1 text-body-sm text-secondary">Jadwal ekskul yang berlangsung hari ini akan muncul di sini.</p>
            </div>
            @endforelse
        </div>
    </section>

    <!-- Ekskul saya -->
    <section class="flex flex-col gap-3">
        <div class="flex items-center justify-between gap-3">
            <h3 class="text-title-md font-bold text-on-surface">Ekskul saya</h3>
            <a href="{{ route('student.extracurriculars.index') }}" class="shrink-0 text-label-md font-semibold text-primary-container hover:underline">Lihat semua</a>
        </div>
        <ul class="flex flex-col gap-3">
            @forelse($ekskulAktif as $reg)
            <li>
                <a href="{{ route('student.extracurriculars.index') }}" class="flex items-center gap-4 rounded-2xl border border-outline-variant bg-surface p-3 pr-4 transition-colors hover:bg-surface-container-low">
                    <span class="flex size-14 shrink-0 items-center justify-center rounded-xl bg-primary-fixed text-on-primary-fixed">
                        <x-icon name="trophy" class="size-[22px]" />
                    </span>
                    <div class="min-w-0 flex-1">
                        <p class="truncate text-title-sm font-bold text-on-surface">{{ $reg->extracurricular->name }}</p>
                        <p class="mt-0.5 flex items-center gap-1 text-body-xs text-secondary">
                            <x-icon name="calendar-days" class="size-[14px] shrink-0" />
                            <span class="truncate">{{ $reg->extracurricular->regular_day ?? 'Jadwal rutin' }}</span>
                        </p>
                    </div>
                    <span class="flex size-9 shrink-0 items-center justify-center rounded-full bg-primary-container text-on-primary-container">
                        <x-icon name="chevron-right" class="size-[18px]" />
                    </span>
                </a>
            </li>
            @empty
            <li class="rounded-2xl border border-dashed border-outline-variant bg-surface p-5 text-body-md text-secondary">Belum mengikuti ekskul aktif.</li>
            @endforelse
        </ul>
    </section>

    <!-- Status registrasi -->
    <section class="flex flex-col gap-3">
        <h3 class="text-title-md font-bold text-on-surface">Status registrasi</h3>
        <ul class="flex flex-col gap-3">
            @forelse($ekskulTerdaftar as $reg)
            <li class="flex items-center gap-4 rounded-2xl border border-outline-variant bg-surface p-4">
                <span class="flex size-11 shrink-0 items-center justify-center rounded-xl {{ $reg->status == 'approved' ? 'bg-tertiary-container text-on-tertiary-container' : ($reg->status == 'pending' ? 'bg-surface-container text-on-surface-variant' : 'bg-error-container text-on-error-container') }}">
                    <x-icon :name="$reg->status == 'approved' ? 'circle-check' : ($reg->status == 'pending' ? 'hourglass' : 'circle-x')" class="size-[20px]" />
                </span>
                <div class="min-w-0 flex-1">
                    <p class="truncate text-title-sm font-bold text-on-surface">{{ $reg->extracurricular->name }}</p>
                    <p class="truncate text-body-xs text-secondary">{{ $reg->status == 'approved' ? 'Pendaftaran disetujui' : ($reg->status == 'pending' ? 'Menunggu verifikasi' : 'Pendaftaran ditolak') }}</p>
                </div>
                @if($reg->status == 'approved')
                    <span class="shrink-0 rounded-full bg-tertiary-container px-2.5 py-1 text-label-xs font-bold text-on-tertiary-container">Disetujui</span>
                @elseif($reg->status == 'pending')
                    <span class="shrink-0 rounded-full bg-surface-container px-2.5 py-1 text-label-xs font-bold text-on-surface-variant">Pending</span>
                @else
                    <span class="shrink-0 rounded-full bg-error-container px-2.5 py-1 text-label-xs font-bold text-on-error-container">Ditolak</span>
                @endif
            </li>
            @empty
            <li class="rounded-2xl border border-dashed border-outline-variant bg-surface p-5 text-center text-body-md text-secondary">Belum ada pendaftaran ekskul.</li>
            @endforelse
        </ul>
    </section>

    <!-- Pengumuman terbaru -->
    @if(isset($pengumuman) && $pengumuman->count() > 0)
    <section class="flex flex-col gap-3">
        <h3 class="text-title-md font-bold text-on-surface">Pengumuman terbaru</h3>
        <ul class="flex flex-col gap-3">
            @foreach($pengumuman as $p)
            <li class="flex gap-3 overflow-hidden rounded-2xl border border-outline-variant bg-surface p-4">
                <span class="flex size-10 shrink-0 items-center justify-center rounded-full bg-primary-fixed text-label-md font-bold text-on-primary-fixed">{{ strtoupper(substr($p->extracurricular->name, 0, 1)) }}</span>
                <div class="min-w-0 flex-1">
                    <div class="flex items-center justify-between gap-2">
                        <span class="truncate text-label-sm font-semibold text-primary-container">{{ $p->extracurricular->name }}</span>
                        <span class="shrink-0 text-label-xs text-outline">{{ \Carbon\Carbon::parse($p->published_at)->diffForHumans() }}</span>
                    </div>
                    <p class="mt-0.5 truncate text-title-sm font-bold text-on-surface">{{ $p->title }}</p>
                    <p class="mt-1 line-clamp-2 text-body-sm text-on-surface-variant">{{ $p->content }}</p>
                </div>
            </li>
            @endforeach
        </ul>
    </section>
    @endif

    <!-- Interactive Script for Micro-interactions -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const cards = document.querySelectorAll('.bg-surface-container-low, .bg-surface-container-lowest, .bg-primary');

            cards.forEach(card => {
                card.addEventListener('touchstart', () => {
                    card.style.transform = 'scale(0.98)';
                    card.style.transition = 'transform 0.2s cubic-bezier(0.34, 1.56, 0.64, 1)';
                }, {passive: true});

                card.addEventListener('touchend', () => {
                    card.style.transform = 'scale(1)';
                }, {passive: true});
            });
        });
    </script>
</div>
</x-student-layout>
