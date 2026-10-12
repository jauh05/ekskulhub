<x-student-layout>
<div class="flex w-full flex-col gap-6">
    <x-student-page-header title="Data Penilaian" subtitle="Hasil penilaian kamu" />

    @php
        $penilaianTerbaru = $assessments->getCollection()->first();
    @endphp

    @if($penilaianTerbaru)
    <!-- Nilai terbaru -->
    <section class="flex items-center justify-between gap-4 rounded-2xl bg-primary-container p-5 text-on-primary-container">
        <div class="min-w-0">
            <p class="text-label-md opacity-90">Nilai terbaru</p>
            <p class="mt-1 text-display-md font-bold">{{ $penilaianTerbaru->final_score }}</p>
            <p class="mt-1 truncate text-body-sm opacity-90">{{ $penilaianTerbaru->registration->extracurricular->name ?? '-' }} &middot; {{ $penilaianTerbaru->period_name }}</p>
        </div>
        <span class="flex size-16 shrink-0 items-center justify-center rounded-2xl bg-surface/20 text-headline-md font-bold">{{ $penilaianTerbaru->predicate ?? '-' }}</span>
    </section>
    @endif

    <!-- Daftar penilaian -->
    <section class="flex flex-col gap-3">
        @forelse($assessments as $val)
        @php
            $tanggalNilai = \Carbon\Carbon::parse($val->assessed_at);
            if ($val->predicate == 'A' || $val->predicate == 'Sangat Baik') $color = 'bg-tertiary-container text-on-tertiary-container';
            elseif ($val->predicate == 'B' || $val->predicate == 'Baik') $color = 'bg-primary-fixed text-on-primary-fixed';
            elseif ($val->predicate == 'C' || $val->predicate == 'Cukup') $color = 'bg-warning-container text-on-warning-container';
            else $color = 'bg-error-container text-on-error-container';
        @endphp
        <article class="flex items-center gap-4 rounded-2xl border border-outline-variant bg-surface p-3 pr-4">
            <div class="flex w-14 shrink-0 flex-col items-center justify-center rounded-xl bg-primary-container py-2.5 text-on-primary-container">
                <span class="text-label-xs uppercase">{{ $tanggalNilai->translatedFormat('M') }}</span>
                <span class="text-title-md font-bold leading-tight">{{ $tanggalNilai->format('d') }}</span>
            </div>

            <div class="min-w-0 flex-1">
                <p class="truncate text-title-sm font-bold text-on-surface">{{ $val->registration->extracurricular->name ?? '-' }}</p>
                <p class="mt-0.5 truncate text-body-xs text-secondary">{{ $val->period_name }}</p>
            </div>

            <div class="flex shrink-0 flex-col items-end gap-1.5">
                <span class="text-headline-sm font-bold text-primary-container">{{ $val->final_score }}</span>
                <span class="rounded-full px-2.5 py-0.5 text-label-xs font-bold {{ $color }}">{{ $val->predicate ?? '-' }}</span>
            </div>
        </article>
        @empty
        <div class="flex flex-col items-center gap-3 rounded-2xl border border-dashed border-outline-variant bg-surface p-8 text-center">
            <span class="flex size-12 items-center justify-center rounded-xl bg-primary-fixed text-on-primary-fixed">
                <x-icon name="award" class="size-[22px]" />
            </span>
            <p class="text-body-md text-secondary">Belum ada data penilaian</p>
        </div>
        @endforelse

        @if($assessments->hasPages())
        <div class="pt-2">
            {{ $assessments->links() }}
        </div>
        @endif
    </section>
</div>
</x-student-layout>
