<x-app-layout>
    <x-slot name="title">{{ isset($user) ? 'Edit Pengguna' : 'Tambah Pengguna' }}</x-slot>

    <div class="max-w-3xl space-y-8">
        <!-- Header -->
        <div>
            <a href="{{ route('admin.users.index') }}" class="inline-flex items-center gap-1.5 text-label-md text-primary-container hover:underline">
                <x-icon name="arrow-left" class="size-[18px]" />
                Kembali
            </a>
            <p class="mt-3 text-body-md text-secondary">{{ isset($user) ? 'Perbarui data akun pengguna.' : 'Isi data untuk membuat akun baru.' }}</p>
            <h3 class="mt-1 text-headline-md font-bold text-on-surface md:text-headline-lg">{{ isset($user) ? 'Edit Pengguna' : 'Tambah Pengguna' }}</h3>
        </div>

        <form action="{{ isset($user) ? route('admin.users.update', $user->id) : route('admin.users.store') }}" method="POST" class="space-y-6">
            @csrf
            @if(isset($user)) @method('PUT') @endif

            <!-- Data diri -->
            <section class="space-y-5 rounded-xl border border-outline-variant bg-surface p-6">
                <div class="flex items-center gap-3">
                    <span class="flex size-9 items-center justify-center rounded-lg bg-primary-fixed text-on-primary-fixed"><x-icon name="user-round" class="size-[18px]" /></span>
                    <h4 class="text-title-md font-bold text-on-surface">Data diri</h4>
                </div>

                <div class="grid gap-5 md:grid-cols-2">
                    <div class="flex flex-col gap-1.5 md:col-span-2">
                        <label for="user_name" class="text-label-md text-on-surface-variant">Nama Lengkap</label>
                        <input id="user_name" type="text" name="name" value="{{ old('name', $user->name ?? '') }}" class="w-full rounded-lg border border-outline-variant bg-surface px-3 py-2.5 text-body-md text-on-surface placeholder:text-outline focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20" required>
                        @error('name') <p class="text-label-sm text-error">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex flex-col gap-1.5">
                        <label for="user_email" class="text-label-md text-on-surface-variant">Email</label>
                        <input id="user_email" type="email" name="email" value="{{ old('email', $user->email ?? '') }}" class="w-full rounded-lg border border-outline-variant bg-surface px-3 py-2.5 text-body-md text-on-surface placeholder:text-outline focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20" required>
                        @error('email') <p class="text-label-sm text-error">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex flex-col gap-1.5">
                        <label for="user_phone" class="text-label-md text-on-surface-variant">No. Telepon</label>
                        <input id="user_phone" type="text" name="phone" value="{{ old('phone', $user->phone ?? '') }}" class="w-full rounded-lg border border-outline-variant bg-surface px-3 py-2.5 text-body-md text-on-surface placeholder:text-outline focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
                        @error('phone') <p class="text-label-sm text-error">{{ $message }}</p> @enderror
                    </div>
                </div>
            </section>

            <!-- Akses dan kata sandi -->
            <section class="space-y-5 rounded-xl border border-outline-variant bg-surface p-6">
                <div class="flex items-center gap-3">
                    <span class="flex size-9 items-center justify-center rounded-lg bg-primary-fixed text-on-primary-fixed"><x-icon name="shield-check" class="size-[18px]" /></span>
                    <h4 class="text-title-md font-bold text-on-surface">Akses dan kata sandi</h4>
                </div>

                <div class="grid gap-5 md:grid-cols-2">
                    <div class="flex flex-col gap-1.5">
                        <label for="user_role" class="text-label-md text-on-surface-variant">Role</label>
                        <select id="user_role" name="role" class="w-full rounded-lg border border-outline-variant bg-surface px-3 py-2.5 text-body-md text-on-surface focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20" required>
                            <option value="admin" {{ old('role', $user->role ?? '') == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="teacher" {{ old('role', $user->role ?? '') == 'teacher' ? 'selected' : '' }}>Guru (Teacher)</option>
                            <option value="student" {{ old('role', $user->role ?? '') == 'student' ? 'selected' : '' }}>Siswa (Student)</option>
                        </select>
                    </div>

                    @if(isset($user))
                    <div class="flex flex-col gap-1.5">
                        <label for="user_status" class="text-label-md text-on-surface-variant">Status</label>
                        <select id="user_status" name="status" class="w-full rounded-lg border border-outline-variant bg-surface px-3 py-2.5 text-body-md text-on-surface focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20" required>
                            <option value="active" {{ old('status', $user->status ?? '') == 'active' ? 'selected' : '' }}>Aktif</option>
                            <option value="inactive" {{ old('status', $user->status ?? '') == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                        </select>
                    </div>
                    @endif

                    <div class="flex flex-col gap-1.5">
                        <label for="user_password" class="text-label-md text-on-surface-variant">Kata Sandi {{ isset($user) ? '(Kosongkan jika tidak ingin mengubah)' : '' }}</label>
                        <input id="user_password" type="password" name="password" class="w-full rounded-lg border border-outline-variant bg-surface px-3 py-2.5 text-body-md text-on-surface placeholder:text-outline focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20" {{ isset($user) ? '' : 'required' }}>
                        @error('password') <p class="text-label-sm text-error">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex flex-col gap-1.5">
                        <label for="user_password_confirmation" class="text-label-md text-on-surface-variant">Konfirmasi Kata Sandi</label>
                        <input id="user_password_confirmation" type="password" name="password_confirmation" class="w-full rounded-lg border border-outline-variant bg-surface px-3 py-2.5 text-body-md text-on-surface placeholder:text-outline focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20" {{ isset($user) ? '' : 'required' }}>
                    </div>
                </div>
            </section>

            <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                <a href="{{ route('admin.users.index') }}" class="inline-flex items-center justify-center gap-2 rounded-lg border border-outline-variant bg-surface px-4 py-2.5 text-label-lg text-on-surface transition-colors hover:bg-surface-container">
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
