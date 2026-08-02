<x-app-layout>
    <div class="mb-6">
        <a href="{{ route('teacher.schedules.index') }}" class="text-primary hover:underline flex items-center gap-1 font-label-md mb-2">
            <span class="material-symbols-outlined text-[18px]">arrow_back</span> Kembali
        </a>
        <h3 class="font-headline-lg text-headline-lg text-on-surface">Tambah Jadwal Baru</h3>
    </div>

    <div class="bg-white p-8 rounded-xl border border-outline-variant card-shadow max-w-2xl" x-data="{ repeatType: 'none' }">
        <form action="{{ route('teacher.schedules.store') }}" method="POST" class="space-y-5">
            @csrf
            
            <div class="space-y-1.5">
                <label class="font-label-md text-on-surface-variant">Ekstrakurikuler</label>
                <select name="extracurricular_id" class="w-full px-4 py-3 bg-white border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all font-body-md" required>
                    <option value="">-- Pilih Ekskul --</option>
                    @foreach($ekskuls as $ekskul)
                        <option value="{{ $ekskul->id }}" {{ old('extracurricular_id') == $ekskul->id ? 'selected' : '' }}>
                            {{ $ekskul->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="space-y-1.5">
                <label class="font-label-md text-on-surface-variant">Tanggal Kegiatan (Awal)</label>
                <input type="date" name="activity_date" value="{{ old('activity_date') }}" class="w-full px-4 py-3 bg-white border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all font-body-md" required>
            </div>

            <div class="space-y-1.5">
                <label class="font-label-md text-on-surface-variant">Tipe Jadwal</label>
                <div class="flex gap-4 mt-2">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="repeat_type" value="none" x-model="repeatType" class="text-primary focus:ring-primary">
                        <span class="font-body-md">Satu Kali</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="repeat_type" value="weekly" x-model="repeatType" class="text-primary focus:ring-primary">
                        <span class="font-body-md">Rutin Mingguan</span>
                    </label>
                </div>
            </div>

            <div class="space-y-1.5" x-show="repeatType === 'weekly'" x-transition style="display: none;">
                <label class="font-label-md text-on-surface-variant text-primary font-bold">Ulangi Sampai Tanggal</label>
                <input type="date" name="repeat_until" value="{{ old('repeat_until') }}" class="w-full px-4 py-3 bg-primary/5 border border-primary/20 rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all font-body-md">
                <p class="text-body-sm text-secondary mt-1">Sistem akan otomatis membuat jadwal untuk setiap minggunya sampai batas tanggal ini.</p>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-1.5">
                    <label class="font-label-md text-on-surface-variant">Waktu Mulai</label>
                    <input type="time" name="start_time" value="{{ old('start_time') }}" class="w-full px-4 py-3 bg-white border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all font-body-md" required>
                </div>
                <div class="space-y-1.5">
                    <label class="font-label-md text-on-surface-variant">Waktu Selesai</label>
                    <input type="time" name="end_time" value="{{ old('end_time') }}" class="w-full px-4 py-3 bg-white border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all font-body-md" required>
                </div>
            </div>
            
            <div class="space-y-1.5">
                <label class="font-label-md text-on-surface-variant">Lokasi</label>
                <input type="text" name="location" value="{{ old('location') }}" placeholder="Contoh: Lapangan Utama" class="w-full px-4 py-3 bg-white border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all font-body-md" required>
            </div>
            
            <div class="space-y-1.5">
                <label class="font-label-md text-on-surface-variant">Topik / Agenda (Opsional)</label>
                <input type="text" name="topic" value="{{ old('topic') }}" placeholder="Contoh: Latihan fisik dasar" class="w-full px-4 py-3 bg-white border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all font-body-md">
            </div>

            <div class="pt-4">
                <button type="submit" class="w-full bg-primary text-on-primary py-3 rounded-lg font-title-md hover:bg-primary-container transition-all">
                    Simpan Jadwal
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
