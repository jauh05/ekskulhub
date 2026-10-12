<x-app-layout>
    <x-slot name="title">Data Penilaian</x-slot>
    <div class="space-y-8">
        <!-- Header -->
        <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
            <div>
                <p class="text-body-md text-secondary">Kelola dan input nilai peserta ekstrakurikuler.</p>
                <h3 class="mt-1 text-headline-md font-bold text-on-surface md:text-headline-lg">Data Penilaian</h3>
            </div>

            <form action="{{ route('teacher.grading.index') }}" method="GET" class="flex flex-col gap-2 sm:flex-row sm:flex-wrap sm:items-center">
                <input type="text" name="period_name" value="{{ request('period_name') }}" placeholder="Contoh: Ganjil 2023" class="w-full rounded-lg border border-outline-variant bg-surface px-3 py-2.5 text-body-md text-on-surface placeholder:text-outline focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20 sm:w-40">
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
                <a href="{{ route('teacher.grading.create') }}" class="inline-flex w-full items-center justify-center gap-2 rounded-lg bg-primary-container px-4 py-2.5 text-label-lg text-on-primary-container transition-opacity hover:opacity-90 sm:w-auto">
                    <x-icon name="plus" class="size-[20px]" /> Tambah
                </a>
            </form>
        </div>

        <!-- Statistik -->
        <div class="grid grid-cols-2 gap-4 lg:grid-cols-4">
            <div class="flex flex-col justify-between gap-8 rounded-xl bg-primary-container p-5 text-on-primary-container">
                <div class="flex items-center justify-between gap-3">
                    <span class="text-label-md opacity-90">Rata-rata nilai</span>
                    <span class="flex size-9 items-center justify-center rounded-lg bg-on-primary-container/15">
                        <x-icon name="star" class="size-[18px]" />
                    </span>
                </div>
                <p class="text-headline-md font-bold">{{ $rataRataNilai }}</p>
            </div>

            <div class="flex flex-col justify-between gap-8 rounded-xl border border-outline-variant bg-surface p-5">
                <div class="flex items-center justify-between gap-3">
                    <span class="text-label-md text-secondary">Predikat A</span>
                    <span class="flex size-9 items-center justify-center rounded-lg bg-tertiary-container text-title-sm font-bold text-on-tertiary-container" aria-hidden="true">A</span>
                </div>
                <p class="text-headline-md font-bold text-on-surface">{{ $predikatA }}</p>
            </div>

            <div class="flex flex-col justify-between gap-8 rounded-xl border border-outline-variant bg-surface p-5">
                <div class="flex items-center justify-between gap-3">
                    <span class="text-label-md text-secondary">Predikat B</span>
                    <span class="flex size-9 items-center justify-center rounded-lg bg-primary-fixed text-title-sm font-bold text-on-primary-fixed" aria-hidden="true">B</span>
                </div>
                <p class="text-headline-md font-bold text-on-surface">{{ $predikatB }}</p>
            </div>

            <div class="flex flex-col justify-between gap-8 rounded-xl border border-outline-variant bg-surface p-5">
                <div class="flex items-center justify-between gap-3">
                    <span class="text-label-md text-secondary">Predikat C</span>
                    <span class="flex size-9 items-center justify-center rounded-lg bg-warning-container text-title-sm font-bold text-on-warning-container" aria-hidden="true">C</span>
                </div>
                <p class="text-headline-md font-bold text-on-surface">{{ $predikatC }}</p>
            </div>
        </div>

        <!-- Kartu berwarna dengan header band -->
        <section class="overflow-hidden rounded-xl border border-outline-variant bg-surface">
            <div class="flex items-center justify-between gap-3 bg-primary-fixed px-5 py-4 text-on-primary-fixed">
                <div class="flex min-w-0 items-center gap-3">
                    <x-icon name="table" class="size-[20px] shrink-0" />
                    <h4 class="truncate text-title-sm font-bold">Detail Penilaian Siswa</h4>
                </div>
                <span class="shrink-0 rounded-full bg-surface px-2.5 py-0.5 text-label-xs font-bold text-on-surface">{{ $totalDinilai }} nilai</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full border-collapse text-left">
                    <thead>
                        <tr class="border-b border-outline-variant bg-surface-container-low">
                            <th class="whitespace-nowrap px-5 py-3 text-label-sm uppercase tracking-wide text-secondary">Siswa</th>
                            <th class="whitespace-nowrap px-5 py-3 text-label-sm uppercase tracking-wide text-secondary">Ekskul</th>
                            <th class="whitespace-nowrap px-5 py-3 text-label-sm uppercase tracking-wide text-secondary">Periode</th>
                            <th class="whitespace-nowrap px-5 py-3 text-label-sm uppercase tracking-wide text-secondary">Nilai Angka</th>
                            <th class="whitespace-nowrap px-5 py-3 text-label-sm uppercase tracking-wide text-secondary">Predikat</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant">
                        @forelse($assessments as $val)
                        <tr class="transition-colors hover:bg-surface-container-low">
                            <td class="px-5 py-4 text-body-md font-semibold text-on-surface">{{ $val->registration->student->name ?? '-' }}</td>
                            <td class="px-5 py-4 text-body-md text-on-surface-variant">{{ $val->registration->extracurricular->name ?? '-' }}</td>
                            <td class="px-5 py-4 text-body-md text-on-surface-variant">{{ $val->period_name }}</td>
                            <td class="px-5 py-4 text-title-md font-bold text-primary-container">{{ $val->final_score }}</td>
                            <td class="px-5 py-4">
                                @php
                                    $color = 'bg-surface-container-high text-on-surface-variant';
                                    if($val->predicate == 'A') $color = 'bg-tertiary-container text-on-tertiary-container';
                                    elseif($val->predicate == 'B') $color = 'bg-primary-container text-on-primary-container';
                                    elseif($val->predicate == 'C') $color = 'bg-warning-container text-on-warning-container';
                                    else $color = 'bg-error-container text-on-error-container';
                                @endphp
                                <span class="inline-block rounded-full px-2.5 py-0.5 text-label-xs font-bold {{ $color }}">{{ $val->predicate }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-5 py-8 text-center text-body-md text-on-surface-variant">Belum ada data penilaian</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="border-t border-outline-variant px-5 py-4">
                {{ $assessments->links() }}
            </div>
        </section>
    </div>
</x-app-layout>
