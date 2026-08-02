<x-app-layout>
    <div class="pt-24 px-8 pb-12">
        <div class="mb-8">
            <a href="{{ route('teacher.announcements.index') }}" class="text-primary hover:underline font-label-md flex items-center gap-1 mb-2">
                <span class="material-symbols-outlined text-[16px]">arrow_back</span> Kembali ke Daftar Pengumuman
            </a>
            <h3 class="font-headline-md text-headline-md font-bold text-on-surface">Buat Pengumuman Baru</h3>
            <p class="text-body-md text-secondary mt-1">Sampaikan informasi penting kepada siswa yang mengikuti ekstrakurikuler Anda.</p>
        </div>

        <div class="bg-white rounded-xl border border-outline-variant card-shadow overflow-hidden max-w-3xl">
            <form action="{{ route('teacher.announcements.store') }}" method="POST" class="p-8 space-y-6">
                @csrf

                <div>
                    <label class="block text-label-md font-bold text-on-surface mb-2">Ekstrakurikuler <span class="text-error">*</span></label>
                    <select name="extracurricular_id" class="w-full px-4 py-3 border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary/20 bg-surface-container-lowest font-body-md" required>
                        <option value="">Pilih Ekstrakurikuler</option>
                        @foreach($ekskuls as $ek)
                            <option value="{{ $ek->id }}" {{ old('extracurricular_id') == $ek->id ? 'selected' : '' }}>{{ $ek->name }}</option>
                        @endforeach
                    </select>
                    @error('extracurricular_id')
                        <p class="text-error text-body-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-label-md font-bold text-on-surface mb-2">Judul Pengumuman <span class="text-error">*</span></label>
                    <input type="text" name="title" value="{{ old('title') }}" placeholder="Contoh: Perubahan Jadwal Latihan Minggu Ini" class="w-full px-4 py-3 border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary/20 bg-surface-container-lowest font-body-md" required>
                    @error('title')
                        <p class="text-error text-body-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-label-md font-bold text-on-surface mb-2">Isi Pengumuman <span class="text-error">*</span></label>
                    <textarea name="content" rows="6" placeholder="Tulis pesan pengumuman Anda di sini..." class="w-full px-4 py-3 border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary/20 bg-surface-container-lowest font-body-md" required>{{ old('content') }}</textarea>
                    @error('content')
                        <p class="text-error text-body-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-label-md font-bold text-on-surface mb-2">Status <span class="text-error">*</span></label>
                    <div class="flex flex-col sm:flex-row gap-4 mt-2">
                        <label class="flex items-center p-4 border border-outline-variant rounded-lg cursor-pointer hover:bg-surface-container-low transition-colors flex-1">
                            <input type="radio" name="status" value="published" class="h-4 w-4 text-primary focus:ring-primary border-outline" {{ old('status', 'published') == 'published' ? 'checked' : '' }}>
                            <div class="ml-3">
                                <span class="block text-label-lg font-bold text-on-surface">Publish Sekarang</span>
                                <span class="block text-body-sm text-secondary mt-1">Siswa akan langsung melihat pengumuman ini.</span>
                            </div>
                        </label>
                        <label class="flex items-center p-4 border border-outline-variant rounded-lg cursor-pointer hover:bg-surface-container-low transition-colors flex-1">
                            <input type="radio" name="status" value="draft" class="h-4 w-4 text-primary focus:ring-primary border-outline" {{ old('status') == 'draft' ? 'checked' : '' }}>
                            <div class="ml-3">
                                <span class="block text-label-lg font-bold text-on-surface">Simpan sebagai Draft</span>
                                <span class="block text-body-sm text-secondary mt-1">Simpan sementara tanpa ditayangkan ke siswa.</span>
                            </div>
                        </label>
                    </div>
                    @error('status')
                        <p class="text-error text-body-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="pt-4 border-t border-outline-variant flex justify-end gap-3">
                    <a href="{{ route('teacher.announcements.index') }}" class="px-6 py-2.5 rounded-lg font-label-md font-bold text-secondary border border-outline-variant hover:bg-surface-container-low transition-colors">
                        Batal
                    </a>
                    <button type="submit" class="bg-primary text-white px-6 py-2.5 rounded-lg font-label-md font-bold hover:bg-primary/90 transition-colors shadow-md">
                        Simpan Pengumuman
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
