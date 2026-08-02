<x-app-layout>
    <div class="flex flex-col md:flex-row justify-between md:items-end gap-4 mb-6">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 bg-primary/10 text-primary rounded-xl flex items-center justify-center">
                <span class="material-symbols-outlined text-[28px]">groups</span>
            </div>
            <div>
                <h3 class="font-headline-md text-headline-md text-on-surface font-bold">Data Peserta Ekskul</h3>
                <p class="text-body-md text-secondary">Ringkasan siswa yang terdaftar pada ekstrakurikuler Anda</p>
            </div>
        </div>
        
        <form action="{{ route('teacher.participants.index') }}" method="GET" class="flex items-center gap-2">
            <select name="ekskul_id" class="px-4 py-2 border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary/20 bg-white font-body-md min-w-[200px]">
                <option value="">Semua Ekstrakurikuler</option>
                @foreach($ekskuls as $ek)
                    <option value="{{ $ek->id }}" {{ request('ekskul_id') == $ek->id ? 'selected' : '' }}>
                        {{ $ek->name }}
                    </option>
                @endforeach
            </select>
            <button type="submit" class="bg-primary text-white px-6 py-2.5 rounded-lg font-label-md font-bold hover:bg-primary/90 transition-colors flex items-center gap-2">
                <span class="material-symbols-outlined text-[20px]">filter_list</span> Terapkan Filter
            </button>
        </form>
    </div>

    <!-- Summary Cards (1 Baris Kanan Kiri) -->
    <div class="bg-white rounded-xl border border-outline-variant card-shadow mb-8 overflow-x-auto">
        <div class="flex flex-row items-center divide-x divide-outline-variant min-w-max w-full">
            
            <div class="flex-1 p-4 flex flex-row items-center justify-center gap-2">
                <span class="material-symbols-outlined text-primary text-[24px]">group</span>
                <span class="text-body-md text-secondary font-medium whitespace-nowrap">Total Siswa:</span>
                <span class="text-title-lg font-bold text-on-surface">{{ $totalSiswa }}</span>
            </div>
            
            <div class="flex-1 p-4 flex flex-row items-center justify-center gap-2">
                <span class="material-symbols-outlined text-[#10B981] text-[24px]">person_add</span>
                <span class="text-body-md text-secondary font-medium whitespace-nowrap">Baru Bulan Ini:</span>
                <span class="text-title-lg font-bold text-on-surface">{{ $siswaBaruBulanIni }}</span>
            </div>

            <div class="flex-1 p-4 flex flex-row items-center justify-center gap-2">
                <span class="material-symbols-outlined text-[#D97706] text-[24px]">category</span>
                <span class="text-body-md text-secondary font-medium whitespace-nowrap">Jumlah Ekskul:</span>
                <span class="text-title-lg font-bold text-on-surface">{{ $totalEkskul }}</span>
            </div>

        </div>
    </div>

    <div class="bg-white rounded-xl border border-outline-variant card-shadow overflow-hidden">
        <div class="p-4 bg-surface-container-low border-b border-outline-variant flex justify-between items-center">
            <h4 class="font-title-md font-bold text-on-surface flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">table_view</span> Data Siswa
            </h4>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-outline-variant bg-surface-container-lowest">
                        <th class="p-4 font-label-md text-secondary flex items-center gap-2"><span class="material-symbols-outlined text-[18px]">person</span> NAMA SISWA</th>
                        <th class="p-4 font-label-md text-secondary"><span class="material-symbols-outlined text-[18px]">school</span> KELAS</th>
                        <th class="p-4 font-label-md text-secondary"><span class="material-symbols-outlined text-[18px]">sports_soccer</span> EKSKUL</th>
                        <th class="p-4 font-label-md text-secondary"><span class="material-symbols-outlined text-[18px]">calendar_today</span> TANGGAL GABUNG</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant">
                    @forelse($participants as $part)
                    <tr class="hover:bg-surface-container-low transition-colors">
                        <td class="p-4 font-body-md text-on-surface font-semibold">{{ $part->student->name ?? '-' }}</td>
                        <td class="p-4 font-body-md text-on-surface-variant">{{ $part->student->studentProfile->class ?? '-' }}</td>
                        <td class="p-4 font-body-md text-on-surface-variant">{{ $part->extracurricular->name ?? '-' }}</td>
                        <td class="p-4 font-body-md text-on-surface-variant">{{ $part->created_at->format('d M Y') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="p-4 text-center text-on-surface-variant">Belum ada peserta</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-outline-variant">
            {{ $participants->links() }}
        </div>
    </div>
</x-app-layout>
