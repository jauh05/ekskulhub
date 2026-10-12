<x-app-layout>
    <x-slot name="title">Laporan & Evaluasi</x-slot>
    @php
        $distribusiPredikat = collect(['A', 'B', 'C', 'D', 'E'])->map(fn ($p) => [
            'label' => $p,
            'value' => collect($reports)->where('predicate', $p)->count(),
        ]);
        $nilaiMaksDistribusi = max(1, (int) $distribusiPredikat->max('value'));
    @endphp

    <div class="space-y-8">
        <!-- Header -->
        <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
            <div>
                <p class="text-body-md text-secondary">Unduh hasil rekapitulasi nilai dan kehadiran.</p>
                <h3 class="mt-1 text-headline-md font-bold text-on-surface md:text-headline-lg">Laporan & Evaluasi</h3>
            </div>

            <form action="{{ route('teacher.reports.index') }}" method="GET" class="flex flex-col gap-2 sm:flex-row sm:flex-wrap sm:items-center">
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
                <a href="{{ route('teacher.reports.export', request()->query()) }}" class="inline-flex w-full items-center justify-center gap-2 rounded-lg bg-primary-container px-4 py-2.5 text-label-lg text-on-primary-container transition-opacity hover:opacity-90 sm:w-auto">
                    <x-icon name="download" class="size-[20px]" /> Export CSV
                </a>
            </form>
        </div>

        <!-- Statistik -->
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
            <div class="flex flex-col justify-between gap-8 rounded-xl bg-primary-container p-5 text-on-primary-container">
                <div class="flex items-center justify-between gap-3">
                    <span class="text-label-md opacity-90">Total siswa aktif</span>
                    <span class="flex size-9 items-center justify-center rounded-lg bg-on-primary-container/15">
                        <x-icon name="users" class="size-[18px]" />
                    </span>
                </div>
                <p class="text-headline-md font-bold">{{ $totalSiswaAktif }}</p>
            </div>

            <div class="flex flex-col justify-between gap-8 rounded-xl border border-outline-variant bg-surface p-5">
                <div class="flex items-center justify-between gap-3">
                    <span class="text-label-md text-secondary">Rata-rata kehadiran</span>
                    <span class="flex size-9 items-center justify-center rounded-lg bg-tertiary-container text-on-tertiary-container">
                        <x-icon name="clipboard-check" class="size-[18px]" />
                    </span>
                </div>
                <p class="text-headline-md font-bold text-on-surface">{{ $rataRataKehadiran }}%</p>
            </div>

            <div class="flex flex-col justify-between gap-8 rounded-xl border border-outline-variant bg-surface p-5">
                <div class="flex items-center justify-between gap-3">
                    <span class="text-label-md text-secondary">Rata-rata nilai global</span>
                    <span class="flex size-9 items-center justify-center rounded-lg bg-warning-container text-on-warning-container">
                        <x-icon name="award" class="size-[18px]" />
                    </span>
                </div>
                <p class="text-headline-md font-bold text-on-surface">{{ $rataRataNilai }}</p>
            </div>
        </div>

        <!-- Grafik ringkasan -->
        <section class="rounded-xl border border-outline-variant bg-surface p-5">
            <div>
                <h4 class="text-title-md font-bold text-on-surface">Distribusi predikat</h4>
                <p class="text-label-sm text-secondary">Jumlah siswa per predikat nilai terakhir, dari A sampai E</p>
            </div>
            <div class="mt-6 grid grid-cols-5 gap-3 sm:gap-6">
                @foreach($distribusiPredikat as $item)
                <div class="flex flex-col items-center gap-2">
                    <span class="text-title-sm font-bold text-on-surface">{{ $item['value'] }}</span>
                    <div class="flex h-36 w-full max-w-16 items-end rounded-md bg-surface-container-low">
                        <div class="w-full rounded-md bg-primary" style="height: {{ $item['value'] > 0 ? max(4, round($item['value'] / $nilaiMaksDistribusi * 100)) : 0 }}%"></div>
                    </div>
                    <span class="text-center text-label-sm font-bold text-secondary">{{ $item['label'] }}</span>
                </div>
                @endforeach
            </div>
        </section>

        <!-- Rekap per siswa -->
        <section class="overflow-hidden rounded-xl border border-outline-variant bg-surface">
            <div class="flex items-center justify-between gap-3 border-b border-outline-variant px-5 py-4">
                <div class="flex items-center gap-3">
                    <span class="flex size-9 items-center justify-center rounded-lg bg-primary-fixed text-on-primary-fixed">
                        <x-icon name="table" class="size-[18px]" />
                    </span>
                    <h4 class="text-title-md font-bold text-on-surface">Rekap per siswa</h4>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full border-collapse text-left">
                    <thead>
                        <tr class="bg-surface-container-low">
                            <th class="whitespace-nowrap px-5 py-3 text-label-sm uppercase tracking-wide text-secondary">Nama siswa</th>
                            <th class="whitespace-nowrap px-5 py-3 text-label-sm uppercase tracking-wide text-secondary">Kelas</th>
                            <th class="whitespace-nowrap px-5 py-3 text-label-sm uppercase tracking-wide text-secondary">Ekskul</th>
                            <th class="whitespace-nowrap px-5 py-3 text-center text-label-sm uppercase tracking-wide text-secondary">Kehadiran</th>
                            <th class="whitespace-nowrap px-5 py-3 text-center text-label-sm uppercase tracking-wide text-secondary">Nilai akhir</th>
                            <th class="whitespace-nowrap px-5 py-3 text-center text-label-sm uppercase tracking-wide text-secondary">Predikat</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant">
                        @forelse($reports as $report)
                        <tr class="transition-colors hover:bg-surface-container-low">
                            <td class="px-5 py-4 text-body-md font-semibold text-on-surface">{{ $report->student->name ?? '-' }}</td>
                            <td class="px-5 py-4 text-body-md text-on-surface-variant">{{ $report->student->studentProfile->class ?? '-' }}</td>
                            <td class="px-5 py-4 text-body-md text-on-surface-variant">{{ $report->extracurricular->name ?? '-' }}</td>
                            <td class="px-5 py-4 text-center">
                                @php
                                    $attColor = 'text-primary-container';
                                    if ($report->attendance_percentage < 50) $attColor = 'text-error';
                                    elseif ($report->attendance_percentage < 75) $attColor = 'text-on-warning-container';
                                @endphp
                                <span class="text-title-md font-bold {{ $attColor }}">{{ $report->attendance_percentage }}%</span>
                            </td>
                            <td class="px-5 py-4 text-center text-title-md font-bold text-on-surface">
                                {{ $report->final_score }}
                            </td>
                            <td class="px-5 py-4 text-center">
                                @if($report->predicate != '-')
                                    @php
                                        $color = 'bg-surface-container-high text-on-surface-variant';
                                        if($report->predicate == 'A') $color = 'bg-tertiary-container text-on-tertiary-container';
                                        elseif($report->predicate == 'B') $color = 'bg-primary-container text-on-primary-container';
                                        elseif($report->predicate == 'C') $color = 'bg-warning-container text-on-warning-container';
                                        else $color = 'bg-error-container text-on-error-container';
                                    @endphp
                                    <span class="inline-block rounded-full px-2.5 py-0.5 text-label-xs font-bold {{ $color }}">{{ $report->predicate }}</span>
                                @else
                                    <span class="text-label-md text-secondary">-</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-5 py-8 text-center text-body-md text-on-surface-variant">Belum ada data siswa / laporan</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</x-app-layout>
