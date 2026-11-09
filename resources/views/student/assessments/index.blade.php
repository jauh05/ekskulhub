<x-student-layout>
<div class="flex w-full flex-col gap-6">
    <x-student-page-header title="Penilaian" subtitle="Hasil penilaian kamu" />

    @forelse($penilaian as $item)
        @php
            $ekskul = $item['ekskul'];
            $batas = \App\Services\PenilaianService::batasPredikat($ekskul);
            $baris = $item['baris'];
            $komponen = $item['komponen'];
            $nilaiAkhir = $baris['nilai_akhir'];
        @endphp
        <article class="overflow-hidden rounded-2xl border border-outline-variant bg-surface shadow-sm">
            <header class="bg-dark p-5 text-surface">
                <div class="flex items-start justify-between gap-4">
                    <div class="min-w-0">
                        <p class="text-label-xs uppercase tracking-wider text-surface/70">Ekstrakurikuler</p>
                        <h3 class="mt-1 truncate text-title-lg font-bold">{{ $ekskul->name }}</h3>
                    </div>
                    @if($baris['predikat'])
                        <span class="flex size-14 shrink-0 items-center justify-center rounded-2xl bg-accent text-headline-sm font-bold text-on-accent">{{ $baris['predikat'] }}</span>
                    @endif
                </div>

                <div class="mt-6 flex flex-wrap items-end justify-between gap-4">
                    <div>
                        <p class="text-label-md text-surface/70">Nilai akhir</p>
                        <p class="mt-1 text-display-md font-bold leading-none">
                            {{ $nilaiAkhir !== null ? number_format($nilaiAkhir, 2, ',', '.') : '-' }}
                        </p>
                    </div>
                    @if($baris['lulus'] === null)
                        <span class="rounded-full bg-surface/15 px-3 py-1.5 text-label-md font-semibold text-surface">{{ $baris['sudah_dinilai'] ? 'Belum final' : 'Belum dinilai' }}</span>
                    @elseif($baris['lulus'])
                        <span class="rounded-full bg-tertiary-container px-3 py-1.5 text-label-md font-bold text-on-tertiary-container">Lulus</span>
                    @else
                        <span class="rounded-full bg-error-container px-3 py-1.5 text-label-md font-bold text-on-error-container">Tidak lulus</span>
                    @endif
                </div>
            </header>

            <!-- Rincian -->
            <div class="overflow-x-auto">
                <table class="w-full min-w-[28rem] text-left text-body-md">
                    <thead>
                        <tr class="bg-surface-container-low text-label-md text-secondary">
                            <th class="px-5 py-3 font-semibold">Komponen</th>
                            <th class="px-5 py-3 text-center font-semibold">Nilai</th>
                            <th class="px-5 py-3 text-center font-semibold">Bobot</th>
                            <th class="px-5 py-3 text-right font-semibold">Kontribusi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant">
                        <tr>
                            <td class="px-5 py-3 font-semibold text-on-surface">Kehadiran</td>
                            <td class="px-5 py-3 text-center text-secondary">{{ rtrim(rtrim(number_format($baris['persen_hadir'], 1, ',', '.'), '0'), ',') }}%</td>
                            <td class="px-5 py-3 text-center text-secondary">{{ rtrim(rtrim(number_format($baris['bobot_hadir'], 2, ',', '.'), '0'), ',') }}%</td>
                            <td class="px-5 py-3 text-right font-semibold text-on-surface">{{ number_format($baris['kontribusi_hadir'], 2, ',', '.') }}</td>
                        </tr>
                        @foreach($komponen as $c)
                            @php
                                $nilaiSel = $baris['nilai'][$c->id] ?? null;
                                $kontribusi = ($nilaiSel ?? 0) * (float) $c->weight / 100;
                            @endphp
                            <tr>
                                <td class="px-5 py-3 font-semibold text-on-surface">{{ $c->name }}</td>
                                <td class="px-5 py-3 text-center">
                                    @if($nilaiSel === null)
                                        <span class="text-outline">Belum diisi</span>
                                    @else
                                        <span class="font-semibold text-on-surface">{{ rtrim(rtrim(number_format($nilaiSel, 2, ',', '.'), '0'), ',') }}</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3 text-center text-secondary">{{ rtrim(rtrim(number_format((float) $c->weight, 2, ',', '.'), '0'), ',') }}%</td>
                                <td class="px-5 py-3 text-right font-semibold text-on-surface">{{ number_format($kontribusi, 2, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <p class="border-t border-outline-variant px-5 py-3 text-body-sm text-secondary">
                Predikat: A ≥ {{ $batas['A'] + 0 }} · B ≥ {{ $batas['B'] + 0 }} · C ≥ {{ $batas['C'] + 0 }} · D ≥ {{ $batas['D'] + 0 }} · E &lt; {{ $batas['D'] + 0 }}. Lulus untuk A, B, dan C.
            </p>
        </article>
    @empty
        <div class="flex flex-col items-center gap-3 rounded-2xl border border-dashed border-outline-variant bg-surface p-10 text-center">
            <span class="flex size-12 items-center justify-center rounded-xl bg-primary-fixed text-primary">
                <x-icon name="award" class="size-[22px]" />
            </span>
            <p class="text-title-sm font-bold text-on-surface">Belum ada penilaian</p>
            <p class="text-body-sm text-secondary">Penilaian dari pembina akan tampil di sini setelah kamu bergabung ke ekstrakurikuler.</p>
        </div>
    @endforelse

</div>
</x-student-layout>
