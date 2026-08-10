<x-app-layout>
    <div class="flex justify-between items-center mb-6">
        <h3 class="font-headline-lg text-headline-lg text-on-surface">Data Ekstrakurikuler</h3>
        <a href="{{ route('admin.extracurriculars.create') }}" class="bg-primary text-on-primary px-4 py-2 rounded-lg font-title-md hover:bg-primary-container transition-all">
            + Tambah Ekskul
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($extracurriculars as $ekskul)
        <div class="bg-white rounded-xl border border-outline-variant card-shadow overflow-hidden flex flex-col">
            <div class="p-6 flex-1">
                <div class="flex justify-between items-start mb-4">
                    <div class="w-12 h-12 bg-primary-container text-on-primary-container rounded-lg flex items-center justify-center">
                        <span class="material-symbols-outlined text-2xl">sports_volleyball</span>
                    </div>
                    @if($ekskul->status == 'active')
                        <span class="px-2.5 py-1 bg-tertiary-container text-on-tertiary-container text-[10px] font-bold rounded-full uppercase tracking-wider">Aktif</span>
                    @else
                        <span class="px-2.5 py-1 bg-error-container text-on-error-container text-[10px] font-bold rounded-full uppercase tracking-wider">Nonaktif</span>
                    @endif
                </div>
                <h4 class="font-title-lg text-title-lg text-on-surface mb-1">{{ $ekskul->name }}</h4>
                <p class="text-body-md text-secondary line-clamp-2 mb-4">{{ $ekskul->description ?? 'Tidak ada deskripsi' }}</p>
                
                <div class="space-y-2 text-on-surface-variant text-label-md">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-[16px]">person</span>
                        Pembina: {{ $ekskul->teacher->name ?? 'Belum ada' }}
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-[16px]">schedule</span>
                        {{ $ekskul->schedule }}
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-[16px]">location_on</span>
                        {{ $ekskul->location }}
                    </div>
                </div>
            </div>
            <div class="bg-surface-container-lowest border-t border-outline-variant p-4 flex justify-end gap-2">
                <a href="{{ route('admin.extracurriculars.edit', $ekskul->id) }}" class="text-primary hover:bg-primary-container/20 px-4 py-2 rounded-lg transition-colors font-label-md font-semibold">
                    Edit
                </a>
                <form action="{{ route('admin.extracurriculars.destroy', $ekskul->id) }}" method="POST" onsubmit="confirmAction(event, 'Hapus ekskul ini?');">
                    @csrf @method('DELETE')
                    <button type="submit" class="text-error hover:bg-error-container/20 px-4 py-2 rounded-lg transition-colors font-label-md font-semibold">
                        Hapus
                    </button>
                </form>
            </div>
        </div>
        @endforeach
    </div>
</x-app-layout>
