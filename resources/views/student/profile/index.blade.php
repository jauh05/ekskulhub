<x-student-layout>
    <div class="mb-6">
        <h3 class="font-headline-lg text-headline-lg text-on-surface">Profil Lengkap</h3>
        <p class="text-secondary mt-1">Lengkapi data diri kamu untuk keperluan administrasi ekstrakurikuler.</p>
    </div>

    <div class="bg-white p-6 md:p-8 rounded-xl border border-outline-variant soft-shadow max-w-2xl">
        <form action="{{ route('student.profile.store') }}" method="POST" class="space-y-5">
            @csrf
            
            <div class="space-y-1.5">
                <label class="font-label-md text-on-surface-variant">Nomor Induk Siswa (NIS)</label>
                <input type="text" name="nis" value="{{ old('nis', $profile->nis ?? '') }}" class="w-full px-4 py-3 bg-white border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all font-body-md" required>
            </div>
            
            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-1.5">
                    <label class="font-label-md text-on-surface-variant">Kelas</label>
                    <input type="text" name="class" value="{{ old('class', $profile->class ?? '') }}" placeholder="Contoh: X MIPA 1" class="w-full px-4 py-3 bg-white border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all font-body-md" required>
                </div>
                <div class="space-y-1.5">
                    <label class="font-label-md text-on-surface-variant">Jenis Kelamin</label>
                    <select name="gender" class="w-full px-4 py-3 bg-white border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all font-body-md" required>
                        <option value="">Pilih</option>
                        <option value="male" {{ old('gender', $profile->gender ?? '') == 'male' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="female" {{ old('gender', $profile->gender ?? '') == 'female' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>
            </div>

            <div class="space-y-1.5">
                <label class="font-label-md text-on-surface-variant">Nama Orang Tua / Wali</label>
                <input type="text" name="parent_name" value="{{ old('parent_name', $profile->parent_name ?? '') }}" class="w-full px-4 py-3 bg-white border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all font-body-md" required>
            </div>

            <div class="space-y-1.5">
                <label class="font-label-md text-on-surface-variant">No. Telepon Orang Tua / Wali</label>
                <input type="text" name="parent_phone" value="{{ old('parent_phone', $profile->parent_phone ?? '') }}" class="w-full px-4 py-3 bg-white border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all font-body-md" required>
            </div>

            <div class="pt-4">
                <button type="submit" class="w-full bg-primary text-on-primary py-3 rounded-lg font-title-md hover:bg-primary-container transition-all">
                    Simpan Profil
                </button>
            </div>
        </form>
    </div>
</x-student-layout>
