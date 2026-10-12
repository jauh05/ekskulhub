<x-app-layout>
    <x-slot name="title">Pengumuman</x-slot>

    <div class="space-y-8">
        <!-- Header -->
        <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
            <div>
                <p class="text-body-md text-secondary">Kelola informasi dan pengumuman untuk siswa ekstrakurikuler Anda.</p>
                <h3 class="mt-1 text-headline-md font-bold text-on-surface md:text-headline-lg">Pengumuman</h3>
            </div>
            <a href="{{ route('teacher.announcements.create') }}" class="inline-flex w-full items-center justify-center gap-2 rounded-lg bg-primary-container px-4 py-2.5 text-label-lg text-on-primary-container transition-opacity hover:opacity-90 md:w-auto">
                <x-icon name="circle-plus" class="size-[20px]" /> Buat Pengumuman
            </a>
        </div>

        <!-- Daftar pengumuman -->
        <div class="overflow-hidden rounded-xl border border-outline-variant bg-surface">
            <ul class="divide-y divide-outline-variant">
                @forelse($announcements as $announcement)
                <li class="flex items-start gap-4 px-4 py-4 md:px-5">
                    <span class="flex size-10 shrink-0 items-center justify-center rounded-full bg-primary-fixed text-on-primary-fixed">
                        <x-icon name="megaphone" class="size-[18px]" />
                    </span>
                    <div class="min-w-0 flex-1">
                        <p class="truncate text-title-sm font-bold text-on-surface" title="{{ $announcement->title }}">{{ $announcement->title }}</p>
                        <div class="mt-1 flex flex-wrap items-center gap-x-2 gap-y-1">
                            @if($announcement->status == 'published')
                                <span class="shrink-0 rounded-full bg-tertiary-container px-2.5 py-0.5 text-label-xs font-bold uppercase text-on-tertiary-container">Published</span>
                            @else
                                <span class="shrink-0 rounded-full bg-surface-container px-2.5 py-0.5 text-label-xs font-bold uppercase text-on-surface-variant">Draft</span>
                            @endif
                            <span class="text-body-xs text-secondary">{{ $announcement->extracurricular->name }}</span>
                        </div>
                        <p class="mt-2 line-clamp-2 text-body-sm text-on-surface-variant">{{ $announcement->content }}</p>
                        <p class="mt-2 inline-flex items-center gap-1.5 text-label-xs text-secondary">
                            <x-icon name="clock" class="size-[14px]" />{{ $announcement->created_at->format('d M Y, H:i') }}
                        </p>
                    </div>
                    <div class="flex shrink-0 items-center gap-1">
                        <a href="{{ route('teacher.announcements.edit', $announcement->id) }}" aria-label="Ubah pengumuman" class="inline-flex size-9 items-center justify-center rounded-lg text-on-surface-variant transition-colors hover:bg-surface-container hover:text-primary-container">
                            <x-icon name="pencil" class="size-[18px]" />
                        </a>
                        <form action="{{ route('teacher.announcements.destroy', $announcement->id) }}" method="POST" onsubmit="confirmAction(event, 'Apakah Anda yakin ingin menghapus pengumuman ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" aria-label="Hapus pengumuman" class="inline-flex size-9 items-center justify-center rounded-lg text-error transition-colors hover:bg-error-container">
                                <x-icon name="trash-2" class="size-[18px]" />
                            </button>
                        </form>
                    </div>
                </li>
                @empty
                <li class="flex flex-col items-center gap-2 px-5 py-10 text-center">
                    <span class="flex size-10 items-center justify-center rounded-full bg-surface-container text-on-surface-variant">
                        <x-icon name="bell-off" class="size-[18px]" />
                    </span>
                    <p class="text-title-sm font-bold text-on-surface">Belum ada pengumuman</p>
                    <p class="text-body-sm text-secondary">Buat pengumuman baru untuk memberitahu siswa Anda.</p>
                </li>
                @endforelse
            </ul>

            @if($announcements->hasPages())
                <div class="border-t border-outline-variant p-4">
                    {{ $announcements->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
