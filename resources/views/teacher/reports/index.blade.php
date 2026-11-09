<x-app-layout>
    <x-slot name="title">Hasil</x-slot>

    @php
        $bobotAwal = [
            'hadir' => (float) ($ekskul?->attendance_weight ?? 0),
            'komponen' => $komponen->mapWithKeys(fn ($c) => [$c->id => (float) $c->weight])->all(),
        ];
        $rataRata = $ringkasan['rata_rata'] !== null ? number_format($ringkasan['rata_rata'], 2, ',', '.') : '-';
    @endphp

    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
            <div>
                <p class="text-body-md text-secondary">Rekap nilai akhir siswa dan pembagian persen per kolom.</p>
                <h3 class="mt-1 text-headline-md font-bold text-on-surface md:text-headline-lg">Hasil</h3>
            </div>

            @if($ekskuls->isNotEmpty())
            <div class="flex flex-wrap items-center gap-3">
                <form method="GET" action="{{ route('teacher.reports.index') }}">
                    <label class="sr-only" for="ekskul_id">Ekstrakurikuler</label>
                    <select id="ekskul_id" name="ekskul_id" onchange="this.form.submit()"
                        class="rounded-xl border border-outline-variant bg-surface py-2.5 pl-4 pr-10 text-label-lg text-on-surface shadow-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
                        @foreach($ekskuls as $e)
                            <option value="{{ $e->id }}" @selected($ekskul && $ekskul->id === $e->id)>{{ $e->name }}</option>
                        @endforeach
                    </select>
                </form>
                @if($ekskul)
                <a href="{{ route('teacher.reports.export', ['ekskul_id' => $ekskul->id]) }}"
                    class="inline-flex items-center gap-2 rounded-xl bg-dark px-5 py-2.5 text-label-lg text-surface shadow-md transition hover:-translate-y-0.5 hover:shadow-lg">
                    <x-icon name="download" class="size-[18px]" /> Unduh CSV
                </a>
                @endif
            </div>
            @endif
        </div>

        @if($ekskuls->isEmpty())
        <div class="flex flex-col items-center gap-3 rounded-2xl border border-dashed border-outline-variant bg-surface p-10 text-center">
            <span class="flex size-14 items-center justify-center rounded-2xl bg-primary-fixed text-primary"><x-icon name="chart-column" class="size-[24px]" /></span>
            <p class="text-title-md font-bold text-on-surface">Belum ada ekstrakurikuler</p>
            <p class="max-w-md text-body-md text-secondary">Hasil akan tampil setelah Anda mengajar minimal satu ekstrakurikuler.</p>
        </div>
        @else
        <!-- Ringkasan -->
        <div class="grid grid-cols-2 gap-4 lg:grid-cols-5">
            <div class="rounded-2xl border border-outline-variant bg-surface p-5 shadow-sm">
                <p class="text-label-md text-secondary">Siswa</p>
                <p class="mt-2 text-headline-sm font-bold text-on-surface">{{ $ringkasan['siswa'] }}</p>
            </div>
            <div class="rounded-2xl border border-outline-variant bg-surface p-5 shadow-sm">
                <p class="text-label-md text-secondary">Sudah dinilai</p>
                <p class="mt-2 text-headline-sm font-bold text-on-surface">{{ $ringkasan['sudah_dinilai'] }}</p>
            </div>
            <div class="rounded-2xl bg-tertiary-container p-5 text-on-tertiary-container shadow-sm">
                <p class="text-label-md opacity-90">Lulus</p>
                <p class="mt-2 text-headline-sm font-bold">{{ $ringkasan['lulus'] }}</p>
            </div>
            <div class="rounded-2xl bg-error-container p-5 text-on-error-container shadow-sm">
                <p class="text-label-md opacity-90">Tidak lulus</p>
                <p class="mt-2 text-headline-sm font-bold">{{ $ringkasan['tidak_lulus'] }}</p>
            </div>
            <div class="rounded-2xl bg-primary p-5 text-on-primary shadow-md">
                <p class="text-label-md opacity-90">Rata-rata nilai</p>
                <p class="mt-2 text-headline-sm font-bold">{{ $rataRata }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 xl:grid-cols-3">
            <!-- Rekap per siswa -->
            <section class="overflow-hidden rounded-2xl border border-outline-variant bg-surface shadow-sm xl:col-span-2">
                <header class="flex items-center justify-between gap-3 border-b border-outline-variant px-5 py-4">
                    <div>
                        <h4 class="text-title-md font-bold text-on-surface">Rekap per siswa</h4>
                        <p class="text-label-sm text-secondary">Nilai akhir = kehadiran dan semua kolom, sesuai pembagian persen.</p>
                    </div>
                </header>

                <div class="overflow-x-auto">
                    <table class="w-full min-w-max text-left text-body-md">
                        <thead>
                            <tr class="bg-surface-container-low text-label-md text-secondary">
                                <th class="px-5 py-3 font-semibold">No</th>
                                <th class="px-5 py-3 font-semibold">Nama</th>
                                <th class="px-5 py-3 font-semibold">Kelas</th>
                                <th class="px-5 py-3 font-semibold">Kehadiran</th>
                                @foreach($komponen as $c)
                                    <th class="px-5 py-3 text-center font-semibold">{{ $c->name }}</th>
                                @endforeach
                                <th class="px-5 py-3 text-center font-semibold">Nilai akhir</th>
                                <th class="px-5 py-3 text-center font-semibold">Predikat</th>
                                <th class="px-5 py-3 text-center font-semibold">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($baris as $i => $row)
                            <tr class="border-t border-outline-variant transition-colors hover:bg-primary-fixed/20">
                                <td class="px-5 py-3 text-secondary">{{ $i + 1 }}</td>
                                <td class="px-5 py-3 font-semibold text-on-surface">{{ $row['nama'] }}</td>
                                <td class="px-5 py-3 text-secondary">{{ $row['kelas'] }}</td>
                                <td class="px-5 py-3">
                                    <div class="flex min-w-[8rem] items-center gap-3">
                                        <div class="h-2 flex-1 overflow-hidden rounded-full bg-surface-container">
                                            <div class="h-full rounded-full bg-primary" style="width: {{ min(100, $row['persen_hadir']) }}%"></div>
                                        </div>
                                        <span class="w-14 text-right text-label-md font-semibold text-on-surface">{{ rtrim(rtrim(number_format($row['persen_hadir'], 1, ',', '.'), '0'), ',') }}%</span>
                                    </div>
                                </td>
                                @foreach($komponen as $c)
                                    <td class="px-5 py-3 text-center">
                                        @php $nilaiSel = $row['nilai'][$c->id] ?? null; @endphp
                                        @if($nilaiSel === null)
                                            <span class="text-outline">-</span>
                                        @else
                                            <span class="font-semibold text-on-surface">{{ rtrim(rtrim(number_format($nilaiSel, 2, ',', '.'), '0'), ',') }}</span>
                                        @endif
                                    </td>
                                @endforeach
                                <td class="px-5 py-3 text-center text-title-sm font-bold text-on-surface">
                                    {{ $row['nilai_akhir'] !== null ? number_format($row['nilai_akhir'], 2, ',', '.') : '-' }}
                                </td>
                                <td class="px-5 py-3 text-center">
                                    @if($row['predikat'])
                                        <span class="inline-flex size-8 items-center justify-center rounded-lg font-bold {{ $row['lulus'] ? 'bg-tertiary-container text-on-tertiary-container' : 'bg-error-container text-on-error-container' }}">{{ $row['predikat'] }}</span>
                                    @else
                                        <span class="text-outline">-</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3 text-center">
                                    @if($row['lulus'] === null)
                                        <span class="rounded-full border border-outline-variant px-2.5 py-1 text-label-xs font-semibold text-secondary">Belum dinilai</span>
                                    @elseif($row['lulus'])
                                        <span class="rounded-full bg-tertiary-container px-2.5 py-1 text-label-xs font-bold text-on-tertiary-container">Lulus</span>
                                    @else
                                        <span class="rounded-full bg-error-container px-2.5 py-1 text-label-xs font-bold text-on-error-container">Tidak lulus</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="{{ 8 + $komponen->count() }}" class="px-5 py-12 text-center text-body-md text-secondary">Belum ada siswa yang disetujui di ekstrakurikuler ini.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>

            <!-- Pembagian persen -->
            <section class="rounded-2xl border border-outline-variant bg-surface p-5 shadow-sm md:p-6"
                x-data="bobotForm(@js($bobotAwal))">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <h4 class="text-title-md font-bold text-on-surface">Pembagian persen</h4>
                        <p class="mt-1 text-label-sm text-secondary">Total semua bagian maksimal 100%.</p>
                    </div>
                    <span class="flex size-10 items-center justify-center rounded-xl bg-primary-fixed text-primary"><x-icon name="chart-column" class="size-[18px]" /></span>
                </div>

                @if($ekskul)
                <form method="POST" action="{{ route('teacher.reports.weights.update') }}" class="mt-6 space-y-4">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="extracurricular_id" value="{{ $ekskul->id }}">

                    <!-- Meter total -->
                    <div class="rounded-xl bg-surface-container-low p-4">
                        <div class="flex items-center justify-between text-label-md">
                            <span class="text-secondary">Total pembagian</span>
                            <span class="font-bold" :class="total() > 100 ? 'text-error' : (total() === 100 ? 'text-tertiary' : 'text-on-surface')">
                                <span x-text="formatTotal()"></span>%
                            </span>
                        </div>
                        <div class="mt-3 h-3 overflow-hidden rounded-full bg-surface-container-high">
                            <div class="h-full rounded-full transition-all duration-300"
                                :class="total() > 100 ? 'bg-error' : (total() === 100 ? 'bg-tertiary' : 'bg-primary')"
                                :style="`width: ${Math.min(total(), 100)}%`"></div>
                        </div>
                        <p class="mt-2 text-label-xs" :class="total() > 100 ? 'text-error' : 'text-secondary'">
                            <span x-show="total() > 100">Melebihi 100%. Kurangi salah satu bagian.</span>
                            <span x-show="total() < 100">Sisa <span x-text="formatSisa()"></span>% belum terbagi.</span>
                            <span x-show="total() === 100">Pas 100%.</span>
                        </p>
                    </div>

                    <!-- Kehadiran -->
                    <label class="block rounded-xl border border-outline-variant p-4">
                        <span class="flex items-center justify-between gap-3">
                            <span class="flex items-center gap-2 text-title-sm font-bold text-on-surface">
                                <span class="flex size-8 items-center justify-center rounded-lg bg-primary-fixed text-primary"><x-icon name="calendar-check" class="size-[16px]" /></span>
                                Kehadiran
                            </span>
                            <span class="flex items-center gap-1">
                                <input type="number" name="attendance_weight" min="0" max="100" step="0.01" x-model.number="hadir"
                                    class="w-24 rounded-lg border border-outline-variant bg-surface px-3 py-2 text-right font-semibold focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
                                <span class="text-label-md text-secondary">%</span>
                            </span>
                        </span>
                        <span class="mt-2 block text-label-xs text-secondary">Dihitung dari sesi yang sudah berlangsung.</span>
                    </label>

                    <!-- Kolom nilai -->
                    @forelse($komponen as $c)
                    <label class="block rounded-xl border border-outline-variant p-4">
                        <span class="flex items-center justify-between gap-3">
                            <span class="min-w-0 truncate text-title-sm font-bold text-on-surface">{{ $c->name }}</span>
                            <span class="flex shrink-0 items-center gap-1">
                                <input type="number" name="weights[{{ $c->id }}]" min="0" max="100" step="0.01" x-model.number="komponen[{{ $c->id }}]"
                                    class="w-24 rounded-lg border border-outline-variant bg-surface px-3 py-2 text-right font-semibold focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
                                <span class="text-label-md text-secondary">%</span>
                            </span>
                        </span>
                    </label>
                    @empty
                    <p class="rounded-xl border border-dashed border-outline-variant p-4 text-body-sm text-secondary">Belum ada kolom nilai. Tambahkan kolom di halaman Penilaian dulu.</p>
                    @endforelse

                    @error('weights')
                        <p class="rounded-xl bg-error-container px-4 py-3 text-label-md font-semibold text-on-error-container">{{ $message }}</p>
                    @enderror

                    <button type="submit" :disabled="total() > 100"
                        class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-primary px-5 py-3 text-label-lg text-on-primary shadow-md transition enabled:hover:-translate-y-0.5 enabled:hover:shadow-lg disabled:cursor-not-allowed disabled:opacity-50">
                        <x-icon name="save" class="size-[18px]" /> Simpan pembagian
                    </button>
                </form>
                @else
                <p class="mt-6 text-body-sm text-secondary">Pilih ekstrakurikuler untuk mengatur pembagian persen.</p>
                @endif
            </section>
        </div>
        @endif
    </div>

    @push('scripts')
    <script>
        function bobotForm(awal) {
            return {
                hadir: awal.hadir,
                komponen: awal.komponen,

                total() {
                    const jumlahKomponen = Object.values(this.komponen).reduce((a, v) => a + (Number(v) || 0), 0);
                    return Math.round(((Number(this.hadir) || 0) + jumlahKomponen) * 100) / 100;
                },

                formatTotal() {
                    return this.total().toLocaleString('id-ID', { maximumFractionDigits: 2 });
                },

                formatSisa() {
                    return (Math.round((100 - this.total()) * 100) / 100).toLocaleString('id-ID', { maximumFractionDigits: 2 });
                },
            };
        }
    </script>
    @endpush
</x-app-layout>
