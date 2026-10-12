<x-app-layout>
    <x-slot name="title">Input Penilaian</x-slot>
    <div class="space-y-8">
        <!-- Header -->
        <div class="flex flex-col gap-3">
            <a href="{{ route('teacher.grading.index') }}" class="inline-flex w-max items-center gap-1.5 text-label-md text-primary-container hover:underline">
                <x-icon name="arrow-left" class="size-[18px]" /> Kembali
            </a>
            <div>
                <p class="text-body-md text-secondary">Input nilai akhir dan predikat untuk siswa yang terdaftar di ekstrakurikuler.</p>
                <h3 class="mt-1 text-headline-md font-bold text-on-surface md:text-headline-lg">Input Penilaian</h3>
            </div>
        </div>

        <section class="max-w-2xl rounded-xl border border-outline-variant bg-surface p-5 sm:p-6">
            <h4 class="text-title-md font-bold text-on-surface">Data penilaian</h4>

            <form action="{{ route('teacher.grading.store') }}" method="POST" class="mt-5 space-y-5">
                @csrf

                <div class="space-y-1.5">
                    <label class="block text-label-md text-on-surface-variant">Pilih Siswa (Berdasarkan Registrasi Ekskul)</label>
                    <select name="registration_id" id="registration_id" class="w-full rounded-lg border border-outline-variant bg-surface px-3 py-2.5 text-body-md text-on-surface focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20" required>
                        <option value="">-- Pilih Siswa --</option>
                        @foreach($registrations as $reg)
                            <option value="{{ $reg->id }}" {{ old('registration_id') == $reg->id ? 'selected' : '' }}>
                                {{ $reg->student->name }} - {{ $reg->extracurricular->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="space-y-1.5">
                    <label class="block text-label-md text-on-surface-variant">Periode Penilaian</label>
                    <input type="text" name="period_name" value="{{ old('period_name') }}" placeholder="Contoh: Ganjil 2023" class="w-full rounded-lg border border-outline-variant bg-surface px-3 py-2.5 text-body-md text-on-surface placeholder:text-outline focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20" required>
                </div>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div class="space-y-1.5">
                        <label class="block text-label-md text-on-surface-variant">Nilai Angka (0-100)</label>
                        <input type="number" name="final_score" value="{{ old('final_score') }}" min="0" max="100" step="0.01" class="w-full rounded-lg border border-outline-variant bg-surface px-3 py-2.5 text-body-md text-on-surface placeholder:text-outline focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20" required>
                    </div>
                    <div class="space-y-1.5">
                        <label class="block text-label-md text-on-surface-variant">Predikat</label>
                        <select name="predicate" class="w-full rounded-lg border border-outline-variant bg-surface px-3 py-2.5 text-body-md text-on-surface focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20" required>
                            <option value="A">A (Sangat Baik)</option>
                            <option value="B">B (Baik)</option>
                            <option value="C">C (Cukup)</option>
                            <option value="D">D (Kurang)</option>
                            <option value="E">E (Sangat Kurang)</option>
                        </select>
                    </div>
                </div>

                <div class="space-y-1.5">
                    <label class="block text-label-md text-on-surface-variant">Catatan Tambahan (Opsional)</label>
                    <textarea name="notes" rows="3" class="w-full rounded-lg border border-outline-variant bg-surface px-3 py-2.5 text-body-md text-on-surface placeholder:text-outline focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">{{ old('notes') }}</textarea>
                </div>

                <div class="flex justify-end pt-2">
                    <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-lg bg-primary-container px-4 py-2.5 text-label-lg text-on-primary-container transition-opacity hover:opacity-90 sm:w-auto">
                        Simpan Penilaian
                    </button>
                </div>
            </form>
        </section>
    </div>
</x-app-layout>
