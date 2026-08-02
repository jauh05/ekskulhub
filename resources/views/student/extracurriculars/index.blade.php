<x-student-layout>
    <div class="mb-6">
        <h3 class="font-headline-lg text-headline-lg text-on-surface">Pilih Ekstrakurikuler</h3>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($extracurriculars as $ekskul)
        <div class="bg-white rounded-xl border border-outline-variant soft-shadow overflow-hidden flex flex-col">
            <div class="p-6 flex-1">
                <div class="flex justify-between items-start mb-4">
                    <div class="w-12 h-12 bg-primary-container text-on-primary-container rounded-lg flex items-center justify-center">
                        <span class="material-symbols-outlined text-2xl">sports_volleyball</span>
                    </div>
                </div>
                <h4 class="font-title-lg text-title-lg text-on-surface mb-1">{{ $ekskul->name }}</h4>
                <p class="text-body-md text-secondary line-clamp-2 mb-4">{{ $ekskul->description ?? 'Tidak ada deskripsi' }}</p>
                
                <div class="space-y-2 text-on-surface-variant text-label-md">
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
            <div class="bg-surface-container-lowest border-t border-outline-variant p-4">
                @if(isset($myRegistrations[$ekskul->id]))
                    @if($myRegistrations[$ekskul->id] == 'approved')
                        <div class="w-full text-center py-2 bg-tertiary-container text-on-tertiary-container rounded-lg font-label-md font-bold">
                            Telah Bergabung
                        </div>
                    @elseif($myRegistrations[$ekskul->id] == 'rejected')
                        <div class="w-full text-center py-2 bg-error-container text-on-error-container rounded-lg font-label-md font-bold">
                            Ditolak
                        </div>
                    @else
                        <div class="w-full text-center py-2 bg-surface-variant text-on-surface-variant rounded-lg font-label-md font-bold">
                            Menunggu Persetujuan
                        </div>
                    @endif
                @else
                    <form action="{{ route('student.extracurriculars.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="extracurricular_id" value="{{ $ekskul->id }}">
                        <button type="submit" onclick="return confirm('Daftar ekstrakurikuler ini?')" class="w-full text-center py-2 bg-primary text-on-primary rounded-lg font-label-md font-bold hover:bg-primary-container transition-colors">
                            Daftar Sekarang
                        </button>
                    </form>
                @endif
            </div>
        </div>
        @endforeach
    </div>
</x-student-layout>
