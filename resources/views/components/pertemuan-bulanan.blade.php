@props([
    'data' => [],
    'tahun' => now()->year,
    'judul' => 'Pertemuan per bulan',
    'keterangan' => 'Dihitung dari jadwal yang sudah ada catatan absensinya.',
])
@php
    $namaBulan = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
    $nilaiMaks = max(1, max($data));
    $total = array_sum($data);
    $bulanIni = (int) now()->format('n');
    $adaBulanIni = (int) $tahun === (int) now()->format('Y');
@endphp

<section class="rounded-2xl border border-outline-variant bg-surface p-5 shadow-sm md:p-6">
    <div class="flex flex-wrap items-start justify-between gap-3">
        <div>
            <h4 class="text-title-md font-bold text-on-surface">{{ $judul }} {{ $tahun }}</h4>
            <p class="mt-1 text-label-sm text-secondary">{{ $keterangan }}</p>
        </div>
        <span class="rounded-full bg-primary-fixed px-3 py-1 text-label-md font-bold text-on-primary-fixed">Total {{ $total }} pertemuan</span>
    </div>

    <div class="mt-6 grid grid-cols-6 gap-3 sm:grid-cols-12">
        @foreach($namaBulan as $i => $nama)
            @php
                $nomor = $i + 1;
                $n = (int) ($data[$nomor] ?? 0);
                $tinggi = $n > 0 ? max(12, round($n / $nilaiMaks * 100)) : 0;
                $inilah = $adaBulanIni && $nomor === $bulanIni;
            @endphp
            <div class="flex flex-col items-center gap-2" title="{{ $nama }} {{ $tahun }}: {{ $n }} pertemuan">
                <span class="text-label-sm font-bold {{ $n > 0 ? 'text-on-surface' : 'text-outline' }}">{{ $n }}</span>
                <div class="flex h-28 w-full items-end rounded-lg bg-surface-container-low px-1">
                    <div class="w-full rounded-md {{ $inilah ? 'bg-primary' : 'bg-primary-container/60' }}" style="height: {{ $tinggi }}%"></div>
                </div>
                <span class="text-label-sm {{ $inilah ? 'font-bold text-on-surface' : 'text-secondary' }}">{{ $nama }}</span>
            </div>
        @endforeach
    </div>
</section>
