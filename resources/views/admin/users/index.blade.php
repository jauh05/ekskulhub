<x-app-layout>
    <x-slot name="title">Data Pengguna</x-slot>

    <div class="space-y-8">
        <!-- Header -->
        <div class="flex flex-wrap items-end justify-between gap-4">
            <div>
                <p class="text-body-md text-secondary">Kelola akun admin, guru, dan siswa.</p>
                <h3 class="mt-1 text-headline-md font-bold text-on-surface md:text-headline-lg">Data Pengguna</h3>
            </div>
            <a href="{{ route('admin.users.create') }}" class="inline-flex items-center justify-center gap-2 rounded-lg bg-primary-container px-4 py-2.5 text-label-lg text-on-primary-container transition-opacity hover:opacity-90">
                <x-icon name="plus" class="size-[18px]" />
                Tambah Pengguna
            </a>
        </div>

        <div class="grid grid-cols-1 gap-8 xl:grid-cols-3">
            <!-- Daftar pengguna -->
            <section class="xl:col-span-2">
                <div class="mb-3 flex items-center justify-between gap-3">
                    <h4 class="text-title-md font-bold text-on-surface">Daftar pengguna</h4>
                    <span class="text-label-md text-secondary">{{ $users->total() }} pengguna</span>
                </div>

                <div class="overflow-hidden rounded-xl border border-outline-variant bg-surface">
                    <ul class="divide-y divide-outline-variant">
                        @forelse($users as $user)
                        <li class="flex flex-wrap items-center gap-x-4 gap-y-3 px-5 py-4">
                            <div class="flex min-w-0 flex-1 basis-56 items-center gap-4">
                                <div class="flex size-10 shrink-0 items-center justify-center rounded-full bg-primary-fixed text-label-md font-bold text-on-primary-fixed">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="truncate text-body-md font-semibold text-on-surface">{{ $user->name }}</p>
                                    <p class="truncate text-body-xs text-secondary">{{ $user->email }} &middot; <span class="capitalize">{{ $user->role }}</span></p>
                                </div>
                            </div>

                            <div class="flex shrink-0 items-center gap-3">
                                @if($user->status == 'active')
                                    <span class="rounded-full bg-tertiary-container px-2.5 py-0.5 text-label-xs font-bold text-on-tertiary-container">Aktif</span>
                                @else
                                    <span class="rounded-full bg-error-container px-2.5 py-0.5 text-label-xs font-bold text-on-error-container">Nonaktif</span>
                                @endif

                                <div class="flex items-center gap-1">
                                    <a href="{{ route('admin.users.edit', $user->id) }}" aria-label="Edit pengguna" title="Edit" class="inline-flex size-9 items-center justify-center rounded-lg text-primary-container transition-colors hover:bg-primary-fixed">
                                        <x-icon name="pencil" class="size-[18px]" />
                                    </a>
                                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="confirmAction(event, 'Yakin ingin menghapus?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" aria-label="Hapus pengguna" title="Hapus" class="inline-flex size-9 items-center justify-center rounded-lg text-error transition-colors hover:bg-error-container">
                                            <x-icon name="trash-2" class="size-[18px]" />
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </li>
                        @empty
                        <li class="px-5 py-8 text-center text-body-md text-secondary">Belum ada pengguna.</li>
                        @endforelse
                    </ul>
                </div>

                <div class="mt-4">
                    {{ $users->links() }}
                </div>
            </section>

            <!-- Aksi cepat -->
            <aside class="space-y-8">
                <section>
                    <h4 class="mb-3 text-title-md font-bold text-on-surface">Aksi cepat</h4>
                    <div class="overflow-hidden rounded-xl border border-outline-variant bg-surface">
                        <a href="{{ route('admin.users.create') }}" class="flex items-center gap-3 px-4 py-4 transition-colors hover:bg-surface-container-low">
                            <span class="flex size-9 shrink-0 items-center justify-center rounded-lg bg-primary-fixed text-on-primary-fixed"><x-icon name="user-round-plus" class="size-[18px]" /></span>
                            <span class="flex-1 text-label-lg text-on-surface">Tambah pengguna</span>
                            <x-icon name="chevron-right" class="size-[18px] text-outline" />
                        </a>
                        <a href="{{ route('admin.extracurriculars.index') }}" class="flex items-center gap-3 border-t border-outline-variant px-4 py-4 transition-colors hover:bg-surface-container-low">
                            <span class="flex size-9 shrink-0 items-center justify-center rounded-lg bg-primary-fixed text-on-primary-fixed"><x-icon name="trophy" class="size-[18px]" /></span>
                            <span class="flex-1 text-label-lg text-on-surface">Data ekskul</span>
                            <x-icon name="chevron-right" class="size-[18px] text-outline" />
                        </a>
                        <a href="{{ route('admin.registrations.index') }}" class="flex items-center gap-3 border-t border-outline-variant px-4 py-4 transition-colors hover:bg-surface-container-low">
                            <span class="flex size-9 shrink-0 items-center justify-center rounded-lg bg-primary-fixed text-on-primary-fixed"><x-icon name="clipboard-list" class="size-[18px]" /></span>
                            <span class="flex-1 text-label-lg text-on-surface">Pendaftaran</span>
                            <x-icon name="chevron-right" class="size-[18px] text-outline" />
                        </a>
                    </div>
                </section>
            </aside>
        </div>
    </div>
</x-app-layout>
