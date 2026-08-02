<x-app-layout>
    <div class="mb-6">
        <a href="{{ route('admin.users.index') }}" class="text-primary hover:underline flex items-center gap-1 font-label-md mb-2">
            <span class="material-symbols-outlined text-[18px]">arrow_back</span> Kembali
        </a>
        <h3 class="font-headline-lg text-headline-lg text-on-surface">{{ isset($user) ? 'Edit Pengguna' : 'Tambah Pengguna' }}</h3>
    </div>

    <div class="bg-white p-8 rounded-xl border border-outline-variant card-shadow max-w-2xl">
        <form action="{{ isset($user) ? route('admin.users.update', $user->id) : route('admin.users.store') }}" method="POST" class="space-y-5">
            @csrf
            @if(isset($user)) @method('PUT') @endif
            
            <div class="space-y-1.5">
                <label class="font-label-md text-on-surface-variant">Nama Lengkap</label>
                <input type="text" name="name" value="{{ old('name', $user->name ?? '') }}" class="w-full px-4 py-3 bg-white border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all font-body-md" required>
                @error('name') <p class="text-error text-label-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="space-y-1.5">
                <label class="font-label-md text-on-surface-variant">Email</label>
                <input type="email" name="email" value="{{ old('email', $user->email ?? '') }}" class="w-full px-4 py-3 bg-white border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all font-body-md" required>
                @error('email') <p class="text-error text-label-sm mt-1">{{ $message }}</p> @enderror
            </div>
            
            <div class="space-y-1.5">
                <label class="font-label-md text-on-surface-variant">No. Telepon</label>
                <input type="text" name="phone" value="{{ old('phone', $user->phone ?? '') }}" class="w-full px-4 py-3 bg-white border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all font-body-md">
                @error('phone') <p class="text-error text-label-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-1.5">
                    <label class="font-label-md text-on-surface-variant">Role</label>
                    <select name="role" class="w-full px-4 py-3 bg-white border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all font-body-md" required>
                        <option value="admin" {{ old('role', $user->role ?? '') == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="teacher" {{ old('role', $user->role ?? '') == 'teacher' ? 'selected' : '' }}>Guru (Teacher)</option>
                        <option value="student" {{ old('role', $user->role ?? '') == 'student' ? 'selected' : '' }}>Siswa (Student)</option>
                    </select>
                </div>
                @if(isset($user))
                <div class="space-y-1.5">
                    <label class="font-label-md text-on-surface-variant">Status</label>
                    <select name="status" class="w-full px-4 py-3 bg-white border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all font-body-md" required>
                        <option value="active" {{ old('status', $user->status ?? '') == 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="inactive" {{ old('status', $user->status ?? '') == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div>
                @endif
            </div>

            <div class="space-y-1.5">
                <label class="font-label-md text-on-surface-variant">Kata Sandi {{ isset($user) ? '(Kosongkan jika tidak ingin mengubah)' : '' }}</label>
                <input type="password" name="password" class="w-full px-4 py-3 bg-white border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all font-body-md" {{ isset($user) ? '' : 'required' }}>
                @error('password') <p class="text-error text-label-sm mt-1">{{ $message }}</p> @enderror
            </div>
            
            <div class="space-y-1.5">
                <label class="font-label-md text-on-surface-variant">Konfirmasi Kata Sandi</label>
                <input type="password" name="password_confirmation" class="w-full px-4 py-3 bg-white border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all font-body-md" {{ isset($user) ? '' : 'required' }}>
            </div>

            <div class="pt-4">
                <button type="submit" class="w-full bg-primary text-on-primary py-3 rounded-lg font-title-md hover:bg-primary-container transition-all">
                    Simpan Data
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
