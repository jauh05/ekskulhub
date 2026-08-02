<x-app-layout>
    <div class="mb-6">
        <a href="{{ route('teacher.grading.index') }}" class="text-primary hover:underline flex items-center gap-1 font-label-md mb-2">
            <span class="material-symbols-outlined text-[18px]">arrow_back</span> Kembali
        </a>
        <h3 class="font-headline-lg text-headline-lg text-on-surface">Input Penilaian</h3>
    </div>

    <div class="bg-white p-8 rounded-xl border border-outline-variant card-shadow max-w-2xl">
        <form action="{{ route('teacher.grading.store') }}" method="POST" class="space-y-5">
            @csrf
            
            <div class="space-y-1.5">
                <label class="font-label-md text-on-surface-variant">Pilih Siswa (Berdasarkan Registrasi Ekskul)</label>
                <select name="registration_id" id="registration_id" class="w-full px-4 py-3 bg-white border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all font-body-md" required>
                    <option value="">-- Pilih Siswa --</option>
                    @foreach($registrations as $reg)
                        <option value="{{ $reg->id }}" {{ old('registration_id') == $reg->id ? 'selected' : '' }}>
                            {{ $reg->student->name }} - {{ $reg->extracurricular->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="space-y-1.5">
                <label class="font-label-md text-on-surface-variant">Periode Penilaian</label>
                <input type="text" name="period_name" value="{{ old('period_name', 'Semester Ganjil 2023/2024') }}" class="w-full px-4 py-3 bg-white border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all font-body-md" required>
            </div>
            
            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-1.5">
                    <label class="font-label-md text-on-surface-variant">Nilai Angka (0-100)</label>
                    <input type="number" name="final_score" value="{{ old('final_score') }}" min="0" max="100" step="0.01" class="w-full px-4 py-3 bg-white border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all font-body-md" required>
                </div>
                <div class="space-y-1.5">
                    <label class="font-label-md text-on-surface-variant">Predikat</label>
                    <select name="predicate" class="w-full px-4 py-3 bg-white border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all font-body-md" required>
                        <option value="A">A (Sangat Baik)</option>
                        <option value="B">B (Baik)</option>
                        <option value="C">C (Cukup)</option>
                        <option value="D">D (Kurang)</option>
                        <option value="E">E (Sangat Kurang)</option>
                    </select>
                </div>
            </div>
            
            <div class="space-y-1.5">
                <label class="font-label-md text-on-surface-variant">Catatan Tambahan (Opsional)</label>
                <textarea name="notes" rows="3" class="w-full px-4 py-3 bg-white border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all font-body-md">{{ old('notes') }}</textarea>
            </div>

            <div class="pt-4">
                <button type="submit" class="w-full bg-primary text-on-primary py-3 rounded-lg font-title-md hover:bg-primary-container transition-all">
                    Simpan Penilaian
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
