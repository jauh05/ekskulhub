<x-app-layout>
    <div class="flex flex-col md:flex-row justify-between md:items-end gap-4 mb-6">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 bg-primary/10 text-primary rounded-xl flex items-center justify-center">
                <span class="material-symbols-outlined text-[28px]">workspace_premium</span>
            </div>
            <div>
                <h3 class="font-headline-md text-headline-md text-on-surface font-bold">Data Penilaian</h3>
                <p class="text-body-md text-secondary mt-1">Kelola dan input nilai peserta ekstrakurikuler.</p>
            </div>
        </div>
        
        <form action="{{ route('teacher.grading.index') }}" method="GET" class="flex flex-wrap items-center gap-2">
            <input type="text" name="period_name" value="{{ request('period_name') }}" placeholder="Contoh: Ganjil 2023" class="px-4 py-2 border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary/20 bg-white font-body-md w-40">
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
            <a href="{{ route('teacher.grading.create') }}" class="bg-[#10B981] text-white px-4 py-2 rounded-lg font-label-md font-bold hover:bg-[#059669] transition-all flex items-center gap-1 ml-2 shadow-md">
                <span class="material-symbols-outlined text-[20px]">add</span> Tambah
            </a>
        </form>
    </div>

    <!-- Summary Cards (1 Baris Kanan Kiri) -->
    <div class="bg-white rounded-xl border border-outline-variant card-shadow mb-8 overflow-x-auto">
        <div class="flex flex-row items-center divide-x divide-outline-variant min-w-max w-full">
            
            <div class="flex-1 p-4 flex flex-row items-center justify-center gap-2">
                <span class="material-symbols-outlined text-primary text-[24px]">grade</span>
                <span class="text-body-md text-secondary font-medium whitespace-nowrap">Rata-rata:</span>
                <span class="text-title-lg font-bold text-on-surface">{{ $rataRataNilai }}</span>
            </div>

            <div class="flex-1 p-4 flex flex-row items-center justify-center gap-2">
                <span class="material-symbols-outlined text-tertiary text-[24px]">looks_one</span>
                <span class="text-body-md text-secondary font-medium whitespace-nowrap">Predikat A:</span>
                <span class="text-title-lg font-bold text-on-surface">{{ $predikatA }}</span>
            </div>

            <div class="flex-1 p-4 flex flex-row items-center justify-center gap-2">
                <span class="material-symbols-outlined text-primary-container text-[24px]">looks_two</span>
                <span class="text-body-md text-secondary font-medium whitespace-nowrap">Predikat B:</span>
                <span class="text-title-lg font-bold text-on-surface">{{ $predikatB }}</span>
            </div>

            <div class="flex-1 p-4 flex flex-row items-center justify-center gap-2">
                <span class="material-symbols-outlined text-[#854D0E] text-[24px]">looks_3</span>
                <span class="text-body-md text-secondary font-medium whitespace-nowrap">Predikat C:</span>
                <span class="text-title-lg font-bold text-on-surface">{{ $predikatC }}</span>
            </div>

        </div>
    </div>

    <div class="bg-white rounded-xl border border-outline-variant card-shadow overflow-hidden">
        <div class="p-4 bg-surface-container-low border-b border-outline-variant flex justify-between items-center">
            <h4 class="font-title-md font-bold text-on-surface flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">table_view</span> Detail Penilaian Siswa
            </h4>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-outline-variant bg-surface-container-lowest">
                        <th class="p-4 font-label-md text-secondary flex items-center gap-2"><span class="material-symbols-outlined text-[16px]">person</span> SISWA</th>
                        <th class="p-4 font-label-md text-secondary"><span class="material-symbols-outlined text-[16px]">sports_esports</span> EKSKUL</th>
                        <th class="p-4 font-label-md text-secondary"><span class="material-symbols-outlined text-[16px]">calendar_today</span> PERIODE</th>
                        <th class="p-4 font-label-md text-secondary"><span class="material-symbols-outlined text-[16px]">score</span> NILAI ANGKA</th>
                        <th class="p-4 font-label-md text-secondary"><span class="material-symbols-outlined text-[16px]">emoji_events</span> PREDIKAT</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant">
                    @forelse($assessments as $val)
                    <tr class="hover:bg-surface-container-low transition-colors">
                        <td class="p-4 font-body-md text-on-surface font-semibold">{{ $val->registration->student->name ?? '-' }}</td>
                        <td class="p-4 font-body-md text-on-surface-variant">{{ $val->registration->extracurricular->name ?? '-' }}</td>
                        <td class="p-4 font-body-md text-on-surface-variant">{{ $val->period_name }}</td>
                        <td class="p-4 font-headline-md text-primary font-bold">{{ $val->final_score }}</td>
                        <td class="p-4">
                            @php
                                $color = 'bg-surface-container-high';
                                if($val->predicate == 'A') $color = 'bg-tertiary-container text-on-tertiary-container';
                                elseif($val->predicate == 'B') $color = 'bg-primary-container text-on-primary-container';
                                elseif($val->predicate == 'C') $color = 'bg-[#FEF08A] text-[#854D0E]';
                                else $color = 'bg-error-container text-on-error-container';
                            @endphp
                            <span class="px-4 py-1 {{ $color }} text-label-md font-bold rounded-lg">{{ $val->predicate }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="p-4 text-center text-on-surface-variant">Belum ada data penilaian</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-outline-variant">
            {{ $assessments->links() }}
        </div>
    </div>
</x-app-layout>
