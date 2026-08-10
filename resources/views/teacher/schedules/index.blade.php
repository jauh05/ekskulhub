<x-app-layout>
    <div class="flex justify-between items-center mb-6">
        <h3 class="font-headline-lg text-headline-lg text-on-surface">Jadwal Ekskul</h3>
        <a href="{{ route('teacher.schedules.create') }}" class="bg-primary text-on-primary px-4 py-2 rounded-lg font-title-md hover:bg-primary-container transition-all">
            + Tambah Jadwal
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($schedules as $sched)
        <div class="bg-white rounded-xl border border-outline-variant card-shadow overflow-hidden flex flex-col">
            <div class="p-6 flex-1">
                <div class="flex justify-between items-start mb-4">
                    <span class="px-2.5 py-1 bg-primary-container text-on-primary-container text-[10px] font-bold rounded-full uppercase tracking-wider">{{ \Carbon\Carbon::parse($sched->activity_date)->format('d M Y') }}</span>
                </div>
                <h4 class="font-title-lg text-title-lg text-on-surface mb-1">{{ $sched->extracurricular->name }}</h4>
                <p class="text-body-md text-secondary mb-4 font-semibold">{{ $sched->topic ?? 'Tidak ada topik spesifik' }}</p>
                
                <div class="space-y-2 text-on-surface-variant text-label-md">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-[16px]">schedule</span>
                        {{ \Carbon\Carbon::parse($sched->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($sched->end_time)->format('H:i') }} WIB
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-[16px]">location_on</span>
                        {{ $sched->location }}
                    </div>
                </div>
            </div>
            <div class="bg-surface-container-lowest border-t border-outline-variant p-4 flex justify-between gap-2">
                <a href="{{ route('teacher.attendances.index') }}" class="text-primary font-bold hover:bg-primary/10 px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">assignment_turned_in</span> Absensi
                </a>
                <div class="flex items-center gap-2">
                    <a href="{{ route('teacher.schedules.edit', $sched->id) }}" class="text-on-surface-variant hover:bg-surface-container-high hover:text-primary px-3 py-2 rounded-lg transition-colors flex items-center">
                        <span class="material-symbols-outlined text-[18px]">edit</span>
                    </a>
                    <form action="{{ route('teacher.schedules.destroy', $sched->id) }}" method="POST" class="inline" onsubmit="confirmAction(event, 'Hapus jadwal ini?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-error hover:bg-error/10 px-3 py-2 rounded-lg transition-colors flex items-center">
                            <span class="material-symbols-outlined text-[18px]">delete</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <p class="text-on-surface-variant">Belum ada jadwal. Silakan tambahkan jadwal baru.</p>
        @endforelse
    </div>
    
    <div class="mt-6">
        {{ $schedules->links() }}
    </div>
</x-app-layout>
