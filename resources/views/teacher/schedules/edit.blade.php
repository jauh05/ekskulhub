<x-app-layout>
    <div class="mb-6">
        <a href="{{ route('teacher.dashboard') }}" class="text-primary hover:underline flex items-center gap-1 font-label-md mb-2">
            <span class="material-symbols-outlined text-[18px]">arrow_back</span> Kembali ke Dasbor
        </a>
        <h3 class="font-headline-lg text-headline-lg text-on-surface">Edit Jadwal</h3>
    </div>

    <div class="bg-white p-8 rounded-xl border border-outline-variant card-shadow max-w-2xl">
        <form action="{{ route('teacher.schedules.update', $schedule->id) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')
            
            <div class="space-y-1.5">
                <label class="font-label-md text-on-surface-variant">Ekstrakurikuler</label>
                <select name="extracurricular_id" class="w-full px-4 py-3 bg-white border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all font-body-md" required>
                    <option value="">-- Pilih Ekskul --</option>
                    @foreach($ekskuls as $ekskul)
                        <option value="{{ $ekskul->id }}" {{ (old('extracurricular_id', $schedule->extracurricular_id) == $ekskul->id) ? 'selected' : '' }}>
                            {{ $ekskul->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="space-y-1.5">
                <label class="font-label-md text-on-surface-variant">Tanggal Kegiatan</label>
                <input type="date" name="activity_date" value="{{ old('activity_date', \Carbon\Carbon::parse($schedule->activity_date)->format('Y-m-d')) }}" class="w-full px-4 py-3 bg-white border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all font-body-md" required>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-1.5">
                    <label class="font-label-md text-on-surface-variant">Waktu Mulai</label>
                    <input type="time" name="start_time" value="{{ old('start_time', \Carbon\Carbon::parse($schedule->start_time)->format('H:i')) }}" class="w-full px-4 py-3 bg-white border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all font-body-md" required>
                </div>
                <div class="space-y-1.5">
                    <label class="font-label-md text-on-surface-variant">Waktu Selesai</label>
                    <input type="time" name="end_time" value="{{ old('end_time', \Carbon\Carbon::parse($schedule->end_time)->format('H:i')) }}" class="w-full px-4 py-3 bg-white border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all font-body-md" required>
                </div>
            </div>
            
            <div class="space-y-1.5">
                <label class="font-label-md text-on-surface-variant">Lokasi</label>
                <input type="text" name="location" value="{{ old('location', $schedule->location) }}" placeholder="Contoh: Lapangan Utama" class="w-full px-4 py-3 bg-white border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all font-body-md" required>
            </div>
            
            <div class="space-y-1.5">
                <label class="font-label-md text-on-surface-variant">Topik / Agenda (Opsional)</label>
                <input type="text" name="topic" value="{{ old('topic', $schedule->material) }}" placeholder="Contoh: Latihan fisik dasar" class="w-full px-4 py-3 bg-white border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all font-body-md">
            </div>

            <div class="pt-4">
                <button type="submit" class="w-full bg-primary text-on-primary py-3 rounded-lg font-title-md hover:bg-primary-container transition-all">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
