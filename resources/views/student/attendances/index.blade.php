<x-student-layout>
<x-student-active-session-poller />

<div class="flex w-full flex-col gap-6">
    <x-student-page-header title="Riwayat Absensi" subtitle="Kehadiran kamu" />

    <!-- Ringkasan -->
    <section class="flex items-center justify-between gap-4 rounded-2xl bg-dark p-5 text-surface">
        <div class="min-w-0">
            <p class="text-label-md text-surface/70">Total presensi tercatat</p>
            <p class="mt-1 text-display-md font-bold">{{ $attendances->total() }}</p>
        </div>
        <span class="flex size-12 shrink-0 items-center justify-center rounded-xl bg-primary text-on-primary">
            <x-icon name="clipboard-check" class="size-[24px]" />
        </span>
    </section>

    <!-- Daftar riwayat -->
    <section class="flex flex-col gap-3">
        @forelse($attendances as $att)
        @php
            $jadwalAbsen = $att->attendanceSession->schedule;
            $tanggalAbsen = \Carbon\Carbon::parse($jadwalAbsen->activity_date);
        @endphp
        <article class="flex items-center gap-4 rounded-2xl border border-outline-variant bg-surface p-3 pr-4">
            <div class="flex w-14 shrink-0 flex-col items-center justify-center rounded-xl bg-primary-container py-2.5 text-on-primary-container">
                <span class="text-label-xs uppercase">{{ $tanggalAbsen->translatedFormat('M') }}</span>
                <span class="text-title-md font-bold leading-tight">{{ $tanggalAbsen->format('d') }}</span>
            </div>

            <div class="min-w-0 flex-1">
                <p class="truncate text-title-sm font-bold text-on-surface">{{ $jadwalAbsen->extracurricular->name ?? '-' }}</p>
                <p class="mt-1 flex items-center gap-1.5 text-body-xs text-secondary">
                    <x-icon name="clock" class="size-[14px] shrink-0 text-primary-container" />
                    <span class="truncate">{{ \Carbon\Carbon::parse($jadwalAbsen->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($jadwalAbsen->end_time)->format('H:i') }} WIB</span>
                </p>
            </div>

            @if($att->status == 'present')
                <span class="inline-flex shrink-0 items-center gap-1 rounded-full bg-tertiary-container px-2.5 py-1 text-label-xs font-bold text-on-tertiary-container">
                    <x-icon name="circle-check" class="size-[14px]" /> Hadir
                </span>
            @elseif($att->status == 'absent')
                <span class="inline-flex shrink-0 items-center gap-1 rounded-full bg-error-container px-2.5 py-1 text-label-xs font-bold text-on-error-container">
                    <x-icon name="circle-x" class="size-[14px]" /> Alpa
                </span>
            @elseif($att->status == 'sick')
                <span class="inline-flex shrink-0 items-center gap-1 rounded-full bg-surface-container px-2.5 py-1 text-label-xs font-bold text-on-surface-variant">
                    <x-icon name="thermometer" class="size-[14px]" /> Sakit
                </span>
            @else
                <span class="inline-flex shrink-0 items-center gap-1 rounded-full bg-primary-fixed px-2.5 py-1 text-label-xs font-bold text-on-primary-fixed">
                    <x-icon name="file-pen" class="size-[14px]" /> Izin
                </span>
            @endif
        </article>
        @empty
        <div class="flex flex-col items-center gap-3 rounded-2xl border border-dashed border-outline-variant bg-surface p-8 text-center">
            <span class="flex size-12 items-center justify-center rounded-xl bg-primary-fixed text-on-primary-fixed">
                <x-icon name="calendar-x" class="size-[22px]" />
            </span>
            <p class="text-body-md text-secondary">Belum ada data absensi</p>
        </div>
        @endforelse

        @if($attendances->hasPages())
        <div class="pt-2">
            {{ $attendances->links() }}
        </div>
        @endif
    </section>
</div>
</x-student-layout>
