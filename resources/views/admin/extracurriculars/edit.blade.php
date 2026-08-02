<x-app-layout>
    <div class="mb-6">
        <a href="{{ route('admin.extracurriculars.index') }}" class="text-primary hover:underline flex items-center gap-1 font-label-md mb-2">
            <span class="material-symbols-outlined text-[18px]">arrow_back</span> Kembali
        </a>
        <h3 class="font-headline-lg text-headline-lg text-on-surface">{{ isset($extracurricular) ? 'Edit Ekskul' : 'Tambah Ekskul' }}</h3>
    </div>

    <div class="bg-white p-8 rounded-xl border border-outline-variant card-shadow max-w-2xl">
        <form action="{{ isset($extracurricular) ? route('admin.extracurriculars.update', $extracurricular->id) : route('admin.extracurriculars.store') }}" method="POST" class="space-y-5">
            @csrf
            @if(isset($extracurricular)) @method('PUT') @endif
            
            <div class="space-y-1.5">
                <label class="font-label-md text-on-surface-variant">Nama Ekstrakurikuler</label>
                <input type="text" name="name" value="{{ old('name', $extracurricular->name ?? '') }}" class="w-full px-4 py-3 bg-white border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all font-body-md" required>
            </div>

            <div class="space-y-1.5">
                <label class="font-label-md text-on-surface-variant">Deskripsi</label>
                <textarea name="description" rows="3" class="w-full px-4 py-3 bg-white border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all font-body-md">{{ old('description', $extracurricular->description ?? '') }}</textarea>
            </div>
            
            <div class="space-y-1.5">
                <label class="font-label-md text-on-surface-variant">Guru Pembina</label>
                <select name="teacher_id" class="w-full px-4 py-3 bg-white border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all font-body-md" required>
                    <option value="">-- Pilih Guru --</option>
                    @foreach($teachers as $teacher)
                        <option value="{{ $teacher->id }}" {{ old('teacher_id', $extracurricular->teacher_id ?? '') == $teacher->id ? 'selected' : '' }}>
                            {{ $teacher->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-1.5">
                    <label class="font-label-md text-on-surface-variant">Jadwal (Contoh: Rabu, 15:00)</label>
                    <input type="text" name="schedule" value="{{ old('schedule', $extracurricular->schedule ?? '') }}" class="w-full px-4 py-3 bg-white border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all font-body-md" required>
                </div>
                <div class="space-y-1.5">
                    <label class="font-label-md text-on-surface-variant">Lokasi</label>
                    <input type="text" name="location" value="{{ old('location', $extracurricular->location ?? '') }}" class="w-full px-4 py-3 bg-white border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all font-body-md" required>
                </div>
            </div>

            @if(isset($extracurricular))
            <div class="space-y-1.5">
                <label class="font-label-md text-on-surface-variant">Status</label>
                <select name="status" class="w-full px-4 py-3 bg-white border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all font-body-md" required>
                    <option value="active" {{ old('status', $extracurricular->status ?? '') == 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="inactive" {{ old('status', $extracurricular->status ?? '') == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>
            @else
            <input type="hidden" name="status" value="active">
            @endif

            <div class="pt-4">
                <button type="submit" class="w-full bg-primary text-on-primary py-3 rounded-lg font-title-md hover:bg-primary-container transition-all">
                    Simpan Data
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
