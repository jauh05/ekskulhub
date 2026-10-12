<x-app-layout>
    <x-slot name="title">Data Ekstrakurikuler</x-slot>

    <div class="space-y-8">
        <!-- Header -->
        <div class="flex flex-wrap items-end justify-between gap-4">
            <div>
                <p class="text-body-md text-secondary">Kelola kegiatan ekstrakurikuler dan guru pembinanya.</p>
                <h3 class="mt-1 text-headline-md font-bold text-on-surface md:text-headline-lg">Data Ekstrakurikuler</h3>
            </div>
            <a href="{{ route('admin.extracurriculars.create') }}" class="inline-flex items-center justify-center gap-2 rounded-lg bg-primary-container px-4 py-2.5 text-label-lg text-on-primary-container transition-opacity hover:opacity-90">
                <x-icon name="plus" class="size-[18px]" />
                Tambah Ekskul
            </a>
        </div>

        <div class="grid grid-cols-1 gap-5 md:grid-cols-2 xl:grid-cols-3">
            @forelse($extracurriculars as $ekskul)
            <div class="flex flex-col overflow-hidden rounded-xl border border-outline-variant bg-surface">
                <!-- Header band -->
                <div class="flex items-center gap-3 bg-primary-fixed px-5 py-4 text-on-primary-fixed">
                    <span class="flex size-10 shrink-0 items-center justify-center rounded-lg bg-surface/60">
                        <x-icon name="trophy" class="size-[20px]" />
                    </span>
                    <h4 class="min-w-0 flex-1 truncate text-title-md font-bold">{{ $ekskul->name }}</h4>
                    @if($ekskul->status == 'active')
                        <span class="shrink-0 rounded-full bg-tertiary-container px-2.5 py-0.5 text-label-xs font-bold text-on-tertiary-container">Aktif</span>
                    @else
                        <span class="shrink-0 rounded-full bg-error-container px-2.5 py-0.5 text-label-xs font-bold text-on-error-container">Nonaktif</span>
                    @endif
                </div>

                <!-- Isi -->
                <div class="flex flex-1 flex-col gap-4 p-5">
                    <p class="line-clamp-2 text-body-md text-on-surface-variant">{{ $ekskul->description ?? 'Tidak ada deskripsi' }}</p>

                    <div class="mt-auto space-y-2 text-label-md text-on-surface-variant">
                        <div class="flex items-center gap-2">
                            <x-icon name="user" class="size-[16px] shrink-0 text-secondary" />
                            <span class="min-w-0 truncate">Pembina: {{ $ekskul->teacher->name ?? 'Belum ada' }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <x-icon name="clock" class="size-[16px] shrink-0 text-secondary" />
                            <span class="min-w-0 truncate {{ $ekskul->schedule ? '' : 'text-outline' }}">{{ $ekskul->schedule ?: 'Jadwal belum diatur' }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <x-icon name="map-pin" class="size-[16px] shrink-0 text-secondary" />
                            <span class="min-w-0 truncate {{ $ekskul->location ? '' : 'text-outline' }}">{{ $ekskul->location ?: 'Lokasi belum diatur' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Aksi -->
                <div class="flex justify-end gap-2 border-t border-outline-variant px-4 py-3">
                    <a href="{{ route('admin.extracurriculars.edit', $ekskul->id) }}" class="inline-flex items-center gap-1.5 rounded-lg px-3 py-2 text-label-md text-primary-container transition-colors hover:bg-primary-fixed">
                        <x-icon name="pencil" class="size-[16px]" />
                        Edit
                    </a>
                    <form action="{{ route('admin.extracurriculars.destroy', $ekskul->id) }}" method="POST" onsubmit="confirmAction(event, 'Hapus ekskul ini?');">
                        @csrf @method('DELETE')
                        <button type="submit" class="inline-flex items-center gap-1.5 rounded-lg px-3 py-2 text-label-md text-error transition-colors hover:bg-error-container">
                            <x-icon name="trash-2" class="size-[16px]" />
                            Hapus
                        </button>
                    </form>
                </div>
            </div>
            @empty
            <div class="col-span-full rounded-xl border border-dashed border-outline-variant bg-surface p-6 text-center">
                <p class="text-title-sm font-bold text-on-surface">Belum ada ekstrakurikuler</p>
                <p class="mt-1 text-body-sm text-secondary">Tambahkan ekskul pertama untuk mulai mengelola pendaftaran siswa.</p>
            </div>
            @endforelse
        </div>
    </div>
</x-app-layout>
