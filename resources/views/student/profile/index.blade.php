<x-student-layout>
    @php
        $inisialNama = collect(explode(' ', trim($user->name)))->filter()->take(2)->map(fn ($bagian) => mb_strtoupper(mb_substr($bagian, 0, 1)))->implode('');
        $kolomProfil = ['school_name', 'class_name', 'gender', 'parent_name', 'parent_phone'];
        $jumlahTerisi = collect($kolomProfil)->filter(fn ($kolom) => filled($profile?->{$kolom}))->count();
    @endphp

    <div class="flex w-full max-w-2xl flex-col gap-6">
        <x-student-page-header title="Profil Lengkap" subtitle="Profil saya" />

        <!-- Kartu profil -->
        <section class="rounded-2xl bg-dark p-5 text-surface">
            <div class="flex items-center gap-4">
                <span class="flex size-16 shrink-0 items-center justify-center rounded-full bg-primary text-title-lg font-bold text-on-primary">{{ $inisialNama }}</span>
                <div class="min-w-0">
                    <p class="truncate text-title-lg font-bold">{{ $user->name }}</p>
                    <p class="truncate text-body-sm text-surface/70">{{ $user->email }}</p>
                </div>
            </div>

            <div class="mt-5 grid grid-cols-2 gap-3">
                <div class="flex min-w-0 flex-col justify-between gap-3 rounded-xl bg-primary-container p-4 text-on-primary-container">
                    <span class="text-label-md opacity-90">Data terisi</span>
                    <p class="text-headline-md font-bold">{{ $jumlahTerisi }}<span class="text-title-sm opacity-90">/{{ count($kolomProfil) }}</span></p>
                </div>
                <div class="flex min-w-0 flex-col justify-between gap-3 rounded-xl bg-surface/10 p-4">
                    <span class="text-label-md text-surface/70">Kelas</span>
                    <p class="truncate text-headline-md font-bold">{{ $profile->class_name ?? '-' }}</p>
                </div>
            </div>
        </section>

        <form action="{{ route('student.profile.store') }}" method="POST" class="flex flex-col gap-6">
            @csrf

            <section class="flex flex-col gap-5 rounded-2xl border border-outline-variant bg-surface p-5">
                <div class="flex items-center gap-3">
                    <span class="flex size-10 shrink-0 items-center justify-center rounded-xl bg-primary-fixed text-on-primary-fixed">
                        <x-icon name="user" class="size-[20px]" />
                    </span>
                    <h4 class="text-title-md font-bold text-on-surface">Data diri</h4>
                </div>

                <div class="space-y-1.5">
                    <label for="school_name" class="block text-label-md text-on-surface-variant">Nama sekolah</label>
                    <select id="school_name" name="school_name" required
                        class="w-full rounded-xl border border-outline-variant bg-surface px-3 py-2.5 text-body-md text-on-surface focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
                        <option value="">Pilih nama sekolah</option>
                        @foreach($schools as $school)
                            <option value="{{ $school }}" {{ old('school_name', $profile->school_name ?? '') == $school ? 'selected' : '' }}>{{ $school }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                    <div class="space-y-1.5">
                        <label for="class_name" class="block text-label-md text-on-surface-variant">Kelas</label>
                        <select id="class_name" name="class_name" required
                            class="w-full rounded-xl border border-outline-variant bg-surface px-3 py-2.5 text-body-md text-on-surface focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
                            <option value="">Pilih kelas</option>
                            @foreach(['7','8','9'] as $grade)
                                @foreach(['A','B','C','D','E','F'] as $letter)
                                    <option value="{{ $grade }} {{ $letter }}" {{ old('class_name', $profile->class_name ?? '') == "$grade $letter" ? 'selected' : '' }}>Kelas {{ $grade }} {{ $letter }}</option>
                                @endforeach
                            @endforeach
                        </select>
                    </div>
                    <div class="space-y-1.5">
                        <label for="gender" class="block text-label-md text-on-surface-variant">Jenis kelamin</label>
                        <select id="gender" name="gender" required
                            class="w-full rounded-xl border border-outline-variant bg-surface px-3 py-2.5 text-body-md text-on-surface focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
                            <option value="">Pilih</option>
                            <option value="male" {{ old('gender', $profile->gender ?? '') == 'male' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="female" {{ old('gender', $profile->gender ?? '') == 'female' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>
                </div>
            </section>

            <section class="flex flex-col gap-5 rounded-2xl border border-outline-variant bg-surface p-5">
                <div class="flex items-center gap-3">
                    <span class="flex size-10 shrink-0 items-center justify-center rounded-xl bg-primary-fixed text-on-primary-fixed">
                        <x-icon name="users" class="size-[20px]" />
                    </span>
                    <h4 class="text-title-md font-bold text-on-surface">Orang tua / wali</h4>
                </div>

                <div class="space-y-1.5">
                    <label for="parent_name" class="block text-label-md text-on-surface-variant">Nama orang tua / wali</label>
                    <input id="parent_name" type="text" name="parent_name" value="{{ old('parent_name', $profile->parent_name ?? '') }}" required
                        class="w-full rounded-xl border border-outline-variant bg-surface px-3 py-2.5 text-body-md text-on-surface placeholder:text-outline focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
                </div>

                <div class="space-y-1.5">
                    <label for="parent_phone" class="block text-label-md text-on-surface-variant">No. telepon orang tua / wali</label>
                    <input id="parent_phone" type="text" name="parent_phone" value="{{ old('parent_phone', $profile->parent_phone ?? '') }}" required
                        class="w-full rounded-xl border border-outline-variant bg-surface px-3 py-2.5 text-body-md text-on-surface placeholder:text-outline focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
                </div>
            </section>

            <button type="submit"
                class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-primary-container px-4 py-3 text-label-lg text-on-primary-container transition-opacity hover:opacity-90">
                Simpan profil
            </button>
        </form>

        <!-- Keluar (navbar siswa tidak memuat tombol ini) -->
        <section class="rounded-2xl border border-outline-variant bg-surface p-2">
            <x-logout-button class="flex w-full items-center justify-center gap-2 rounded-xl px-4 py-3 text-label-lg text-error transition-colors hover:bg-error-container/50" iconClass="size-[18px]" :showText="true" />
        </section>
    </div>
</x-student-layout>
