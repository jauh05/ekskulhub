<x-app-layout>
    <x-slot name="title">Tambah Jadwal</x-slot>

    <div class="max-w-3xl space-y-6">
        <div>
            <a href="{{ route('teacher.schedules.index') }}" class="inline-flex items-center gap-1 text-label-md text-primary-container hover:underline">
                <x-icon name="arrow-left" class="size-[18px]" /> Kembali ke Jadwal
            </a>
            <h3 class="mt-2 text-headline-md font-bold text-on-surface md:text-headline-lg">Tambah Jadwal Baru</h3>
            <p class="mt-1 text-body-md text-secondary">Atur kegiatan latihan, lalu buka sesi absensi untuk siswa.</p>
        </div>

        <form action="{{ route('teacher.schedules.store') }}" method="POST" class="space-y-6 rounded-xl border border-outline-variant bg-surface p-6" x-data="{ repeatType: 'none' }">
            @csrf

            <section class="space-y-5">
                <h4 class="text-title-md font-bold text-on-surface">Kegiatan</h4>

                <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                    <div class="space-y-1.5 md:col-span-2">
                        <label class="text-label-md text-on-surface-variant">Ekstrakurikuler</label>
                        <select name="extracurricular_id" class="w-full rounded-lg border border-outline-variant bg-surface px-3 py-2.5 text-body-md text-on-surface focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20" required>
                            <option value="">-- Pilih Ekskul --</option>
                            @foreach($ekskuls as $ekskul)
                                <option value="{{ $ekskul->id }}" {{ old('extracurricular_id') == $ekskul->id ? 'selected' : '' }}>
                                    {{ $ekskul->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-label-md text-on-surface-variant">Tanggal Kegiatan (Awal)</label>
                        <input type="date" name="activity_date" value="{{ old('activity_date') }}" class="w-full rounded-lg border border-outline-variant bg-surface px-3 py-2.5 text-body-md text-on-surface focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20" required>
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-label-md text-on-surface-variant">Tipe Jadwal</label>
                        <div class="flex flex-wrap gap-3">
                            <label class="flex cursor-pointer items-center gap-2 rounded-lg border border-outline-variant bg-surface px-4 py-2.5 transition-colors hover:bg-surface-container-low has-[:checked]:border-primary has-[:checked]:bg-primary-fixed/40">
                                <input type="radio" name="repeat_type" value="none" x-model="repeatType" class="accent-primary">
                                <span class="text-body-md text-on-surface">Satu Kali</span>
                            </label>
                            <label class="flex cursor-pointer items-center gap-2 rounded-lg border border-outline-variant bg-surface px-4 py-2.5 transition-colors hover:bg-surface-container-low has-[:checked]:border-primary has-[:checked]:bg-primary-fixed/40">
                                <input type="radio" name="repeat_type" value="weekly" x-model="repeatType" class="accent-primary">
                                <span class="text-body-md text-on-surface">Rutin Mingguan</span>
                            </label>
                        </div>
                    </div>

                    <div class="space-y-1.5 md:col-span-2" x-show="repeatType === 'weekly'" x-transition style="display: none;">
                        <label class="text-label-md text-primary-container">Ulangi Sampai Tanggal</label>
                        <input type="date" name="repeat_until" value="{{ old('repeat_until') }}" class="w-full rounded-lg border border-primary/30 bg-primary-fixed/30 px-3 py-2.5 text-body-md text-on-surface focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
                        <p class="mt-1 text-body-sm text-secondary">Sistem akan otomatis membuat jadwal untuk setiap minggunya sampai batas tanggal ini.</p>
                    </div>
                </div>
            </section>

            <section class="space-y-5 border-t border-outline-variant pt-6">
                <h4 class="text-title-md font-bold text-on-surface">Waktu dan tempat</h4>

                <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                    <div class="space-y-1.5">
                        <label class="text-label-md text-on-surface-variant">Waktu Mulai</label>
                        <input type="time" name="start_time" value="{{ old('start_time') }}" class="w-full rounded-lg border border-outline-variant bg-surface px-3 py-2.5 text-body-md text-on-surface focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20" required>
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-label-md text-on-surface-variant">Waktu Selesai</label>
                        <input type="time" name="end_time" value="{{ old('end_time') }}" class="w-full rounded-lg border border-outline-variant bg-surface px-3 py-2.5 text-body-md text-on-surface focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20" required>
                    </div>

                    <div class="space-y-1.5 md:col-span-2">
                        <label class="text-label-md text-on-surface-variant">Lokasi</label>
                        <input type="text" name="location" value="{{ old('location') }}" placeholder="Contoh: Lapangan Utama" class="w-full rounded-lg border border-outline-variant bg-surface px-3 py-2.5 text-body-md text-on-surface placeholder:text-outline focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20" required>
                    </div>

                    <div class="space-y-1.5 md:col-span-2">
                        <label class="text-label-md text-on-surface-variant">Topik / Agenda (Opsional)</label>
                        <input type="text" name="topic" value="{{ old('topic') }}" placeholder="Contoh: Latihan fisik dasar" class="w-full rounded-lg border border-outline-variant bg-surface px-3 py-2.5 text-body-md text-on-surface placeholder:text-outline focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
                    </div>
                </div>
            </section>

            <div class="flex flex-col-reverse gap-3 border-t border-outline-variant pt-5 sm:flex-row sm:justify-end">
                <a href="{{ route('teacher.schedules.index') }}" class="inline-flex w-full items-center justify-center rounded-lg border sm:w-auto border-outline-variant bg-surface px-4 py-2.5 text-label-lg text-on-surface transition-colors hover:bg-surface-container">
                    Batal
                </a>
                <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-lg bg-primary-container sm:w-auto px-4 py-2.5 text-label-lg text-on-primary-container transition-opacity hover:opacity-90">
                    <x-icon name="save" class="size-[18px]" /> Simpan Jadwal
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
