<x-app-layout>
    <x-slot name="title">Pendaftaran Ekstrakurikuler</x-slot>

    <div class="space-y-8">
        <!-- Header -->
        <div>
            <p class="text-body-md text-secondary">Tinjau dan proses pendaftaran siswa ke ekstrakurikuler.</p>
            <h3 class="mt-1 text-headline-md font-bold text-on-surface md:text-headline-lg">Pendaftaran Ekstrakurikuler</h3>
        </div>

        <div class="grid grid-cols-1 gap-8 xl:grid-cols-3">
            <!-- Daftar pendaftaran -->
            <section class="xl:col-span-2">
                <div class="mb-3 flex items-center justify-between gap-3">
                    <h4 class="text-title-md font-bold text-on-surface">Daftar pendaftaran</h4>
                    <span class="text-label-md text-secondary">{{ $registrations->total() }} pendaftaran</span>
                </div>

                <div class="overflow-hidden rounded-xl border border-outline-variant bg-surface">
                    <ul class="divide-y divide-outline-variant">
                        @forelse($registrations as $reg)
                        <li class="flex flex-wrap items-center gap-x-4 gap-y-3 px-5 py-4">
                            <div class="flex min-w-0 flex-1 basis-56 items-center gap-4">
                                <div class="flex size-10 shrink-0 items-center justify-center rounded-full bg-primary-fixed text-label-md font-bold text-on-primary-fixed">
                                    {{ strtoupper(substr($reg->student->name ?? '?', 0, 1)) }}
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="truncate text-body-md font-semibold text-on-surface">{{ $reg->student->name ?? '-' }}</p>
                                    <p class="truncate text-body-xs text-secondary">{{ $reg->extracurricular->name ?? '-' }}</p>
                                </div>
                            </div>

                            <div class="flex shrink-0 items-center gap-3">
                                <div class="flex flex-col items-end gap-1">
                                    <span class="text-label-xs text-secondary">{{ $reg->created_at->format('d M Y') }}</span>
                                    @if($reg->status == 'approved')
                                        <span class="rounded-full bg-tertiary-container px-2.5 py-0.5 text-label-xs font-bold text-on-tertiary-container">Diterima</span>
                                    @elseif($reg->status == 'rejected')
                                        <span class="rounded-full bg-error-container px-2.5 py-0.5 text-label-xs font-bold text-on-error-container">Ditolak</span>
                                    @else
                                        <span class="rounded-full bg-warning-container px-2.5 py-0.5 text-label-xs font-bold text-on-warning-container">Menunggu</span>
                                    @endif
                                </div>

                                @if($reg->status == 'pending')
                                <form action="{{ route('admin.registrations.update', $reg->id) }}" method="POST" class="flex gap-1">
                                    @csrf @method('PATCH')
                                    <button type="submit" name="status" value="approved" aria-label="Terima pendaftaran" title="Terima" class="inline-flex size-9 items-center justify-center rounded-lg text-tertiary transition-colors hover:bg-tertiary-container">
                                        <x-icon name="circle-check" class="size-[18px]" />
                                    </button>
                                    <button type="submit" name="status" value="rejected" aria-label="Tolak pendaftaran" title="Tolak" class="inline-flex size-9 items-center justify-center rounded-lg text-error transition-colors hover:bg-error-container">
                                        <x-icon name="circle-x" class="size-[18px]" />
                                    </button>
                                </form>
                                @endif
                            </div>
                        </li>
                        @empty
                        <li class="px-5 py-8 text-center text-body-md text-secondary">Belum ada pendaftaran.</li>
                        @endforelse
                    </ul>
                </div>

                <div class="mt-4">
                    {{ $registrations->links() }}
                </div>
            </section>

            <!-- Kolom samping -->
            <aside class="space-y-8">
                <section>
                    <h4 class="mb-3 text-title-md font-bold text-on-surface">Aksi cepat</h4>
                    <div class="overflow-hidden rounded-xl border border-outline-variant bg-surface">
                        <a href="{{ route('admin.extracurriculars.index') }}" class="flex items-center gap-3 px-4 py-4 transition-colors hover:bg-surface-container-low">
                            <span class="flex size-9 shrink-0 items-center justify-center rounded-lg bg-primary-fixed text-on-primary-fixed"><x-icon name="trophy" class="size-[18px]" /></span>
                            <span class="flex-1 text-label-lg text-on-surface">Data ekskul</span>
                            <x-icon name="chevron-right" class="size-[18px] text-outline" />
                        </a>
                        <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 border-t border-outline-variant px-4 py-4 transition-colors hover:bg-surface-container-low">
                            <span class="flex size-9 shrink-0 items-center justify-center rounded-lg bg-primary-fixed text-on-primary-fixed"><x-icon name="users" class="size-[18px]" /></span>
                            <span class="flex-1 text-label-lg text-on-surface">Data pengguna</span>
                            <x-icon name="chevron-right" class="size-[18px] text-outline" />
                        </a>
                    </div>
                </section>

                <section>
                    <h4 class="mb-3 text-title-md font-bold text-on-surface">Catatan</h4>
                    <div class="flex gap-3 rounded-xl border border-outline-variant bg-surface p-4">
                        <x-icon name="info" class="mt-0.5 size-[18px] shrink-0 text-secondary" />
                        <p class="text-body-sm text-secondary">Tombol terima dan tolak hanya muncul untuk pendaftaran yang masih menunggu.</p>
                    </div>
                </section>
            </aside>
        </div>
    </div>
</x-app-layout>
