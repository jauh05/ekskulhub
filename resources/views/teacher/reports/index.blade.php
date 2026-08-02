<x-app-layout>
    <div class="flex flex-col md:flex-row justify-between md:items-end gap-4 mb-6">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 bg-primary/10 text-primary rounded-xl flex items-center justify-center">
                <span class="material-symbols-outlined text-[28px]">analytics</span>
            </div>
            <div>
                <h3 class="font-headline-md text-headline-md text-on-surface font-bold">Laporan & Evaluasi</h3>
                <p class="text-body-md text-secondary mt-1">Unduh hasil rekapitulasi nilai dan kehadiran.</p>
            </div>
        </div>
        
        <form action="{{ route('teacher.reports.index') }}" method="GET" class="flex flex-wrap items-center gap-2">
            <select name="ekskul_id" class="px-4 py-2 border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary/20 bg-white font-body-md min-w-[150px]">
                <option value="">Semua Ekskul</option>
                @foreach($ekskuls as $ek)
                    <option value="{{ $ek->id }}" {{ request('ekskul_id') == $ek->id ? 'selected' : '' }}>
                        {{ $ek->name }}
                    </option>
                @endforeach
            </select>
            <button type="submit" class="bg-primary text-white px-6 py-2.5 rounded-lg font-label-md font-bold hover:bg-primary/90 transition-colors flex items-center gap-2">
                <span class="material-symbols-outlined text-[20px]">filter_list</span> Terapkan Filter
            </button>
            <a href="{{ route('teacher.reports.export_csv', request()->query()) }}" class="bg-[#10B981] text-white px-4 py-2 rounded-lg font-label-md font-bold hover:bg-[#059669] transition-colors flex items-center gap-2 shadow-md">
                <span class="material-symbols-outlined text-[20px]">download</span> Export CSV
            </a>
        </form>
    </div>

    <!-- Summary Cards (1 Baris Kanan Kiri) -->
    <div class="bg-white rounded-xl border border-outline-variant card-shadow mb-8 overflow-x-auto">
        <div class="flex flex-row items-center divide-x divide-outline-variant min-w-max w-full">
            
            <div class="flex-1 p-4 flex flex-row items-center justify-center gap-2">
                <span class="material-symbols-outlined text-primary text-[24px]">group</span>
                <span class="text-body-md text-secondary font-medium whitespace-nowrap">Total Siswa Aktif:</span>
                <span class="text-title-lg font-bold text-on-surface">{{ $totalSiswaAktif }}</span>
            </div>

            <div class="flex-1 p-4 flex flex-row items-center justify-center gap-2">
                <span class="material-symbols-outlined text-[#10B981] text-[24px]">fact_check</span>
                <span class="text-body-md text-secondary font-medium whitespace-nowrap">Rata-rata Kehadiran:</span>
                <span class="text-title-lg font-bold text-on-surface">{{ $rataRataKehadiran }}%</span>
            </div>

            <div class="flex-1 p-4 flex flex-row items-center justify-center gap-2">
                <span class="material-symbols-outlined text-[#D97706] text-[24px]">workspace_premium</span>
                <span class="text-body-md text-secondary font-medium whitespace-nowrap">Rata-rata Nilai Global:</span>
                <span class="text-title-lg font-bold text-on-surface">{{ $rataRataNilai }}</span>
            </div>

        </div>
    </div>

    <div class="bg-white rounded-xl border border-outline-variant card-shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-outline-variant bg-surface-container-lowest">
                        <th class="p-4 font-label-md text-secondary flex items-center gap-2"><span class="material-symbols-outlined text-[16px]">person</span> NAMA SISWA</th>
                        <th class="p-4 font-label-md text-secondary"><span class="material-symbols-outlined text-[16px]">school</span> KELAS</th>
                        <th class="p-4 font-label-md text-secondary"><span class="material-symbols-outlined text-[16px]">sports_soccer</span> EKSKUL</th>
                        <th class="p-4 font-label-md text-secondary text-center"><span class="material-symbols-outlined text-[16px]">percent</span> KEHADIRAN</th>
                        <th class="p-4 font-label-md text-secondary text-center"><span class="material-symbols-outlined text-[16px]">score</span> NILAI AKHIR</th>
                        <th class="p-4 font-label-md text-secondary text-center"><span class="material-symbols-outlined text-[16px]">emoji_events</span> PREDIKAT</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant">
                    @forelse($reports as $report)
                    <tr class="hover:bg-surface-container-low transition-colors">
                        <td class="p-4 font-body-md text-on-surface font-semibold">{{ $report->student->name ?? '-' }}</td>
                        <td class="p-4 font-body-md text-on-surface-variant">{{ $report->student->studentProfile->class ?? '-' }}</td>
                        <td class="p-4 font-body-md text-on-surface-variant">{{ $report->extracurricular->name ?? '-' }}</td>
                        <td class="p-4 text-center">
                            @php
                                $attColor = 'text-primary';
                                if ($report->attendance_percentage < 50) $attColor = 'text-error';
                                elseif ($report->attendance_percentage < 75) $attColor = 'text-[#D97706]';
                            @endphp
                            <span class="font-headline-sm font-bold {{ $attColor }}">{{ $report->attendance_percentage }}%</span>
                        </td>
                        <td class="p-4 text-center font-headline-sm text-on-surface font-bold">
                            {{ $report->final_score }}
                        </td>
                        <td class="p-4 text-center">
                            @if($report->predicate != '-')
                                @php
                                    $color = 'bg-surface-container-high';
                                    if($report->predicate == 'A') $color = 'bg-tertiary-container text-on-tertiary-container';
                                    elseif($report->predicate == 'B') $color = 'bg-primary-container text-on-primary-container';
                                    elseif($report->predicate == 'C') $color = 'bg-[#FEF08A] text-[#854D0E]';
                                    else $color = 'bg-error-container text-on-error-container';
                                @endphp
                                <span class="px-4 py-1 {{ $color }} text-label-md font-bold rounded-lg">{{ $report->predicate }}</span>
                            @else
                                <span class="text-outline text-label-md">-</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="p-4 text-center text-on-surface-variant">Belum ada data siswa / laporan</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
