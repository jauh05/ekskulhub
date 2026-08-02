<x-student-layout>
    <div class="mb-6">
        <h3 class="font-headline-lg text-headline-lg text-on-surface">Gabung Ekstrakurikuler</h3>
        <p class="text-on-surface-variant font-body-md mt-2">Masukkan kode kelas dari pembina ekstrakurikuler untuk bergabung.</p>
    </div>

    @if(session('error'))
        <div class="mb-4 bg-error-container text-on-error-container p-4 rounded-lg font-bold">
            {{ session('error') }}
        </div>
    @endif
    
    @if(session('success'))
        <div class="mb-4 bg-primary-container text-on-primary-container p-4 rounded-lg font-bold">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-4 bg-error-container text-on-error-container p-4 rounded-lg font-bold">
            {{ $errors->first() }}
        </div>
    @endif

    <div class="bg-white p-6 md:p-8 rounded-xl border border-outline-variant soft-shadow max-w-xl mb-12">
        <form action="{{ route('student.extracurriculars.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="font-label-md text-on-surface-variant mb-2 block">Kode Kelas</label>
                <input type="text" name="class_code" placeholder="Misal: ROB-2024" class="w-full px-4 py-3 bg-surface-container-low border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all font-mono tracking-widest uppercase" required>
            </div>
            <button type="submit" class="w-full bg-primary text-on-primary py-3 rounded-lg font-title-md hover:bg-primary-container transition-all">
                Gabung Sekarang
            </button>
        </form>
    </div>

    @if($extracurriculars->count() > 0)
    <div class="mb-6">
        <h3 class="font-title-lg text-title-lg text-on-surface">Ekstrakurikuler Saya</h3>
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
                        {{ $ekskul->regular_day }}
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
                @endif
            </div>
        </div>
        @endforeach
    </div>
    @endif
</x-student-layout>
