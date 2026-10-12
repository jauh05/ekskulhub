<x-app-layout>
    <x-slot name="title">Buat Pengumuman</x-slot>

    <div class="max-w-3xl space-y-6">
        <div>
            <a href="{{ route('teacher.announcements.index') }}" class="inline-flex items-center gap-1 text-label-md text-primary-container hover:underline">
                <x-icon name="arrow-left" class="size-[18px]" /> Kembali ke Daftar Pengumuman
            </a>
            <h3 class="mt-2 text-headline-md font-bold text-on-surface md:text-headline-lg">Buat Pengumuman Baru</h3>
            <p class="mt-1 text-body-md text-secondary">Sampaikan informasi penting kepada siswa yang mengikuti ekstrakurikuler Anda.</p>
        </div>

        <form action="{{ route('teacher.announcements.store') }}" method="POST" class="space-y-6 rounded-xl border border-outline-variant bg-surface p-6">
            @csrf

            <section class="space-y-5">
                <h4 class="text-title-md font-bold text-on-surface">Isi pengumuman</h4>

                <div class="space-y-1.5">
                    <label class="block text-label-md text-on-surface-variant">Ekstrakurikuler <span class="text-error">*</span></label>
                    <select name="extracurricular_id" class="w-full rounded-lg border border-outline-variant bg-surface px-3 py-2.5 text-body-md text-on-surface focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20" required>
                        <option value="">Pilih Ekstrakurikuler</option>
                        @foreach($ekskuls as $ek)
                            <option value="{{ $ek->id }}" {{ old('extracurricular_id') == $ek->id ? 'selected' : '' }}>{{ $ek->name }}</option>
                        @endforeach
                    </select>
                    @error('extracurricular_id')
                        <p class="mt-1 text-body-sm text-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-1.5">
                    <label class="block text-label-md text-on-surface-variant">Judul Pengumuman <span class="text-error">*</span></label>
                    <input type="text" name="title" value="{{ old('title') }}" placeholder="Contoh: Perubahan Jadwal Latihan Minggu Ini" class="w-full rounded-lg border border-outline-variant bg-surface px-3 py-2.5 text-body-md text-on-surface placeholder:text-outline focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20" required>
                    @error('title')
                        <p class="mt-1 text-body-sm text-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-1.5">
                    <label class="block text-label-md text-on-surface-variant">Isi Pengumuman <span class="text-error">*</span></label>
                    <textarea name="content" rows="6" placeholder="Tulis pesan pengumuman Anda di sini..." class="w-full resize-y rounded-lg border border-outline-variant bg-surface px-3 py-2.5 text-body-md text-on-surface placeholder:text-outline focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20" required>{{ old('content') }}</textarea>
                    @error('content')
                        <p class="mt-1 text-body-sm text-error">{{ $message }}</p>
                    @enderror
                </div>
            </section>

            <section class="space-y-3 border-t border-outline-variant pt-6">
                <h4 class="text-title-md font-bold text-on-surface">Status penayangan</h4>
                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                    <label class="flex cursor-pointer items-start gap-3 rounded-lg border border-outline-variant bg-surface p-4 transition-colors hover:bg-surface-container-low has-[:checked]:border-primary has-[:checked]:bg-primary-fixed/40">
                        <input type="radio" name="status" value="published" class="mt-1 accent-primary" {{ old('status', 'published') == 'published' ? 'checked' : '' }}>
                        <span class="block">
                            <span class="block text-label-lg text-on-surface">Publish Sekarang</span>
                            <span class="mt-1 block text-body-sm text-secondary">Siswa akan langsung melihat pengumuman ini.</span>
                        </span>
                    </label>
                    <label class="flex cursor-pointer items-start gap-3 rounded-lg border border-outline-variant bg-surface p-4 transition-colors hover:bg-surface-container-low has-[:checked]:border-primary has-[:checked]:bg-primary-fixed/40">
                        <input type="radio" name="status" value="draft" class="mt-1 accent-primary" {{ old('status') == 'draft' ? 'checked' : '' }}>
                        <span class="block">
                            <span class="block text-label-lg text-on-surface">Simpan sebagai Draft</span>
                            <span class="mt-1 block text-body-sm text-secondary">Simpan sementara tanpa ditayangkan ke siswa.</span>
                        </span>
                    </label>
                </div>
                @error('status')
                    <p class="mt-1 text-body-sm text-error">{{ $message }}</p>
                @enderror
            </section>

            <div class="flex flex-col-reverse gap-3 border-t border-outline-variant pt-5 sm:flex-row sm:justify-end">
                <a href="{{ route('teacher.announcements.index') }}" class="inline-flex w-full items-center justify-center rounded-lg border sm:w-auto border-outline-variant bg-surface px-4 py-2.5 text-label-lg text-on-surface transition-colors hover:bg-surface-container">
                    Batal
                </a>
                <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-lg bg-primary-container sm:w-auto px-4 py-2.5 text-label-lg text-on-primary-container transition-opacity hover:opacity-90">
                    <x-icon name="save" class="size-[18px]" /> Simpan Pengumuman
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
