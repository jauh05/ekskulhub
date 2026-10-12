<x-app-layout>
    <x-slot name="title">Jadwal Ekskul</x-slot>

    <div class="space-y-8">
        <!-- Header -->
        <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
            <div>
                <p class="text-body-md text-secondary">Atur kegiatan latihan dan buka sesi absensi untuk siswa.</p>
                <h3 class="mt-1 text-headline-md font-bold text-on-surface md:text-headline-lg">Jadwal Ekskul</h3>
            </div>
            <a href="{{ route('teacher.schedules.create') }}" class="inline-flex w-full items-center justify-center gap-2 rounded-lg bg-primary-container px-4 py-2.5 text-label-lg text-on-primary-container transition-opacity hover:opacity-90 md:w-auto">
                <x-icon name="plus" class="size-[20px]" /> Tambah Jadwal
            </a>
        </div>

        <!-- Daftar jadwal -->
        <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
            @forelse($schedules as $sched)
            @php $tanggalJadwal = \Carbon\Carbon::parse($sched->activity_date); @endphp
            <article class="flex flex-col gap-5 rounded-xl border border-outline-variant bg-surface p-5 sm:flex-row">
                <div class="flex w-16 shrink-0 flex-col items-center justify-center rounded-xl bg-primary-container py-3 text-on-primary-container">
                    <span class="text-label-xs uppercase">{{ $tanggalJadwal->translatedFormat('M') }}</span>
                    <span class="text-title-md font-bold">{{ $tanggalJadwal->format('d') }}</span>
                </div>
                <div class="min-w-0 flex-1">
                    <h4 class="text-title-lg font-bold text-on-surface">{{ $sched->extracurricular->name }}</h4>
                    <p class="text-body-sm text-secondary">{{ $sched->material ?? 'Tidak ada topik spesifik' }}</p>
                    <div class="mt-3 flex flex-wrap gap-x-5 gap-y-2 text-body-sm text-on-surface-variant">
                        <span class="inline-flex items-center gap-1.5"><x-icon name="calendar" class="size-[16px] text-secondary" />{{ $tanggalJadwal->translatedFormat('l, d F Y') }}</span>
                        <span class="inline-flex items-center gap-1.5"><x-icon name="clock" class="size-[16px] text-secondary" />{{ \Carbon\Carbon::parse($sched->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($sched->end_time)->format('H:i') }} WIB</span>
                        <span class="inline-flex items-center gap-1.5"><x-icon name="map-pin" class="size-[16px] text-secondary" />{{ $sched->location }}</span>
                    </div>
                    <div class="mt-5 flex flex-wrap items-center gap-2">
                        <a href="{{ route('teacher.attendances.index') }}" class="inline-flex items-center gap-2 rounded-lg bg-primary-container px-4 py-2 text-label-lg text-on-primary-container transition-opacity hover:opacity-90">
                            <x-icon name="clipboard-check" class="size-[18px]" /> Absensi
                        </a>
                        <a href="{{ route('teacher.schedules.edit', $sched->id) }}" class="inline-flex items-center gap-2 rounded-lg border border-outline-variant bg-surface px-4 py-2 text-label-lg text-on-surface transition-colors hover:bg-surface-container">
                            <x-icon name="pencil" class="size-[18px]" /> Ubah
                        </a>
                        <form action="{{ route('teacher.schedules.destroy', $sched->id) }}" method="POST" onsubmit="confirmAction(event, 'Hapus jadwal ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" aria-label="Hapus jadwal" class="inline-flex size-9 items-center justify-center rounded-lg text-error transition-colors hover:bg-error-container">
                                <x-icon name="trash-2" class="size-[18px]" />
                            </button>
                        </form>
                    </div>
                </div>
            </article>
            @empty
            <div class="col-span-full flex flex-col items-start gap-2 rounded-xl border border-dashed border-outline-variant bg-surface p-6">
                <p class="text-title-sm font-bold text-on-surface">Belum ada jadwal</p>
                <p class="text-body-sm text-secondary">Silakan tambahkan jadwal baru untuk membuka sesi absensi.</p>
                <a href="{{ route('teacher.schedules.create') }}" class="mt-2 inline-flex items-center gap-2 text-label-lg text-primary-container hover:underline">
                    <x-icon name="plus" class="size-[18px]" /> Tambah jadwal
                </a>
            </div>
            @endforelse
        </div>

        <div>
            {{ $schedules->links() }}
        </div>
    </div>
</x-app-layout>
