<x-student-layout>
    <div class="mb-6">
        <h3 class="font-headline-lg text-headline-lg text-on-surface">Data Penilaian</h3>
    </div>

    <div class="bg-white rounded-xl border border-outline-variant soft-shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-outline-variant bg-surface-container-lowest">
                        <th class="p-4 font-label-md text-secondary">EKSKUL</th>
                        <th class="p-4 font-label-md text-secondary">PERIODE</th>
                        <th class="p-4 font-label-md text-secondary">NILAI ANGKA</th>
                        <th class="p-4 font-label-md text-secondary">PREDIKAT</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant">
                    @forelse($assessments as $val)
                    <tr class="hover:bg-surface-container-low transition-colors">
                        <td class="p-4 font-body-md text-on-surface font-semibold">{{ $val->registration->extracurricular->name ?? '-' }}</td>
                        <td class="p-4 font-body-md text-on-surface-variant">{{ $val->period_name }}</td>
                        <td class="p-4 font-headline-md text-primary font-bold">{{ $val->final_score }}</td>
                        <td class="p-4">
                            @php
                                $color = 'bg-surface-container-high';
                                if($val->predicate == 'A' || $val->predicate == 'Sangat Baik') $color = 'bg-tertiary-container text-on-tertiary-container';
                                elseif($val->predicate == 'B' || $val->predicate == 'Baik') $color = 'bg-primary-container text-on-primary-container';
                                elseif($val->predicate == 'C' || $val->predicate == 'Cukup') $color = 'bg-[#FEF08A] text-[#854D0E]';
                                else $color = 'bg-error-container text-on-error-container';
                            @endphp
                            <span class="px-4 py-1 {{ $color }} text-label-md font-bold rounded-lg">{{ $val->predicate }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="p-4 text-center text-on-surface-variant">Belum ada data penilaian</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-outline-variant">
            {{ $assessments->links() }}
        </div>
    </div>
</x-student-layout>
