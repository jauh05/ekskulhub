<x-app-layout>
    <x-slot name="title">{{ isset($extracurricular) ? 'Edit Ekskul' : 'Tambah Ekskul' }}</x-slot>

    <div class="max-w-3xl space-y-8">
        <!-- Header -->
        <div>
            <a href="{{ route('admin.extracurriculars.index') }}" class="inline-flex items-center gap-1.5 text-label-md text-primary-container hover:underline">
                <x-icon name="arrow-left" class="size-[18px]" />
                Kembali
            </a>
            <p class="mt-3 text-body-md text-secondary">{{ isset($extracurricular) ? 'Perbarui informasi ekstrakurikuler.' : 'Isi informasi untuk membuat ekskul baru.' }}</p>
            <h3 class="mt-1 text-headline-md font-bold text-on-surface md:text-headline-lg">{{ isset($extracurricular) ? 'Edit Ekskul' : 'Tambah Ekskul' }}</h3>
        </div>

        <form action="{{ isset($extracurricular) ? route('admin.extracurriculars.update', $extracurricular->id) : route('admin.extracurriculars.store') }}" method="POST" class="space-y-6">
            @csrf
            @if(isset($extracurricular)) @method('PUT') @endif

            <!-- Informasi ekskul -->
            <section class="space-y-5 rounded-xl border border-outline-variant bg-surface p-6">
                <div class="flex items-center gap-3">
                    <span class="flex size-9 items-center justify-center rounded-lg bg-primary-fixed text-on-primary-fixed"><x-icon name="trophy" class="size-[18px]" /></span>
                    <h4 class="text-title-md font-bold text-on-surface">Informasi ekstrakurikuler</h4>
                </div>

                <div class="grid gap-5 md:grid-cols-2">
                    <div class="flex flex-col gap-1.5 md:col-span-2">
                        <label for="ekskul_name" class="text-label-md text-on-surface-variant">Nama Ekstrakurikuler</label>
                        <input id="ekskul_name" type="text" name="name" value="{{ old('name', $extracurricular->name ?? '') }}" class="w-full rounded-lg border border-outline-variant bg-surface px-3 py-2.5 text-body-md text-on-surface placeholder:text-outline focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20" required>
                    </div>

                    <div class="flex flex-col gap-1.5 md:col-span-2">
                        <label for="ekskul_description" class="text-label-md text-on-surface-variant">Deskripsi</label>
                        <textarea id="ekskul_description" name="description" rows="3" class="w-full resize-y rounded-lg border border-outline-variant bg-surface px-3 py-2.5 text-body-md text-on-surface placeholder:text-outline focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">{{ old('description', $extracurricular->description ?? '') }}</textarea>
                    </div>

                    <div class="flex flex-col gap-1.5 md:col-span-2">
                        <label for="ekskul_teacher" class="text-label-md text-on-surface-variant">Guru Pembina</label>
                        <select id="ekskul_teacher" name="teacher_id" class="w-full rounded-lg border border-outline-variant bg-surface px-3 py-2.5 text-body-md text-on-surface focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20" required>
                            <option value="">-- Pilih Guru --</option>
                            @foreach($teachers as $teacher)
                                <option value="{{ $teacher->id }}" {{ old('teacher_id', $extracurricular->teacher_id ?? '') == $teacher->id ? 'selected' : '' }}>
                                    {{ $teacher->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </section>

            <!-- Jadwal dan lokasi -->
            <section class="space-y-5 rounded-xl border border-outline-variant bg-surface p-6">
                <div class="flex items-center gap-3">
                    <span class="flex size-9 items-center justify-center rounded-lg bg-primary-fixed text-on-primary-fixed"><x-icon name="calendar-days" class="size-[18px]" /></span>
                    <h4 class="text-title-md font-bold text-on-surface">Jadwal dan lokasi</h4>
                </div>

                <div class="grid gap-5 md:grid-cols-2">
                    <div class="flex flex-col gap-1.5">
                        <label for="ekskul_schedule" class="text-label-md text-on-surface-variant">Jadwal (Contoh: Rabu, 15:00)</label>
                        <input id="ekskul_schedule" type="text" name="schedule" value="{{ old('schedule', $extracurricular->schedule ?? '') }}" class="w-full rounded-lg border border-outline-variant bg-surface px-3 py-2.5 text-body-md text-on-surface placeholder:text-outline focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20" required>
                    </div>

                    <div class="flex flex-col gap-1.5">
                        <label for="ekskul_location" class="text-label-md text-on-surface-variant">Lokasi</label>
                        <input id="ekskul_location" type="text" name="location" value="{{ old('location', $extracurricular->location ?? '') }}" class="w-full rounded-lg border border-outline-variant bg-surface px-3 py-2.5 text-body-md text-on-surface placeholder:text-outline focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20" required>
                    </div>

                    @if(isset($extracurricular))
                    <div class="flex flex-col gap-1.5">
                        <label for="ekskul_status" class="text-label-md text-on-surface-variant">Status</label>
                        <select id="ekskul_status" name="status" class="w-full rounded-lg border border-outline-variant bg-surface px-3 py-2.5 text-body-md text-on-surface focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20" required>
                            <option value="active" {{ old('status', $extracurricular->status ?? '') == 'active' ? 'selected' : '' }}>Aktif</option>
                            <option value="inactive" {{ old('status', $extracurricular->status ?? '') == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                        </select>
                    </div>
                    @else
                    <input type="hidden" name="status" value="active">
                    @endif
                </div>
            </section>

            <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                <a href="{{ route('admin.extracurriculars.index') }}" class="inline-flex items-center justify-center gap-2 rounded-lg border border-outline-variant bg-surface px-4 py-2.5 text-label-lg text-on-surface transition-colors hover:bg-surface-container">
                    <x-icon name="x" class="size-[18px]" />
                    Batal
                </a>
                <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-lg bg-primary-container px-5 py-2.5 text-label-lg text-on-primary-container transition-opacity hover:opacity-90">
                    <x-icon name="save" class="size-[18px]" />
                    Simpan Data
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
