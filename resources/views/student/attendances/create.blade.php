<x-student-layout>
    <div class="mb-6">
        <a href="{{ route('student.dashboard') }}" class="text-primary hover:underline flex items-center gap-1 font-label-md mb-2">
            <span class="material-symbols-outlined text-[18px]">arrow_back</span> Kembali
        </a>
        <h3 class="font-headline-lg text-headline-lg text-on-surface">Presensi Ekskul</h3>
    </div>

    <div class="bg-white p-6 md:p-8 rounded-xl border border-outline-variant soft-shadow max-w-lg mx-auto">
        <h4 class="font-title-lg text-on-surface text-center mb-2">{{ $schedule->extracurricular->name }}</h4>
        <p class="text-secondary text-center mb-6">{{ \Carbon\Carbon::parse($schedule->activity_date)->format('d F Y') }} | {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} WIB</p>
        
        <form action="{{ route('student.attendances.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            <input type="hidden" name="schedule_id" value="{{ $schedule->id }}">
            
            <div class="space-y-2">
                <label class="font-label-md text-on-surface-variant block text-center">Foto Selfie di Lokasi (Opsional)</label>
                <div class="flex items-center justify-center w-full">
                    <label class="flex flex-col items-center justify-center w-full h-48 border-2 border-outline-variant border-dashed rounded-xl cursor-pointer bg-surface hover:bg-surface-container transition-colors">
                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                            <span class="material-symbols-outlined text-[40px] text-secondary mb-2">add_a_photo</span>
                            <p class="mb-2 text-sm text-secondary"><span class="font-semibold">Klik untuk ambil foto</span> atau pilih file</p>
                        </div>
                        <input type="file" name="selfie" accept="image/*" capture="user" class="hidden" />
                    </label>
                </div>
                <p class="text-xs text-center text-outline">Gunakan kamera depan untuk bukti kehadiran fisik (Max 5MB).</p>
            </div>

            <button type="submit" class="w-full bg-primary text-on-primary py-3 rounded-lg font-title-md hover:bg-primary-container active:scale-95 transition-all flex items-center justify-center gap-2">
                <span class="material-symbols-outlined text-[20px]">how_to_reg</span> Kirim Kehadiran
            </button>
        </form>
    </div>
</x-student-layout>
