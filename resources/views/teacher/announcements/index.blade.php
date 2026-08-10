<x-app-layout>
    <div class="pt-24 px-8 pb-12">
        <!-- Header -->
        <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h3 class="font-headline-md text-headline-md font-bold text-on-surface">Pengumuman</h3>
                <p class="text-body-md text-secondary mt-1">Kelola informasi dan pengumuman untuk siswa ekstrakurikuler Anda.</p>
            </div>
            <a href="{{ route('teacher.announcements.create') }}" class="bg-primary text-white px-6 py-2.5 rounded-lg font-label-md font-bold hover:bg-primary/90 transition-colors flex items-center gap-2 shadow-md">
                <span class="material-symbols-outlined text-[20px]">add_circle</span> Buat Pengumuman
            </a>
        </div>

        @if(session('success'))
            <div class="mb-6 bg-green-100 text-green-800 p-4 rounded-lg flex items-center gap-2 border border-green-200">
                <span class="material-symbols-outlined">check_circle</span>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
        @endif

        <div class="bg-white rounded-xl border border-outline-variant card-shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse min-w-max">
                    <thead>
                        <tr class="bg-surface-container-low border-b border-outline-variant">
                            <th class="px-6 py-4 text-label-sm font-bold text-secondary uppercase tracking-wider">Judul</th>
                            <th class="px-6 py-4 text-label-sm font-bold text-secondary uppercase tracking-wider">Ekstrakurikuler</th>
                            <th class="px-6 py-4 text-label-sm font-bold text-secondary uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-label-sm font-bold text-secondary uppercase tracking-wider">Dibuat Pada</th>
                            <th class="px-6 py-4 text-label-sm font-bold text-secondary uppercase tracking-wider text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant/50">
                        @forelse($announcements as $announcement)
                        <tr class="hover:bg-surface-container-lowest transition-colors">
                            <td class="px-6 py-4">
                                <span class="font-bold text-on-surface line-clamp-1 max-w-xs" title="{{ $announcement->title }}">{{ $announcement->title }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-body-sm text-secondary">{{ $announcement->extracurricular->name }}</span>
                            </td>
                            <td class="px-6 py-4">
                                @if($announcement->status == 'published')
                                    <span class="px-3 py-1 bg-green-100 text-green-700 text-[11px] font-bold uppercase rounded-full border border-green-200">Published</span>
                                @else
                                    <span class="px-3 py-1 bg-gray-100 text-gray-700 text-[11px] font-bold uppercase rounded-full border border-gray-200">Draft</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-body-sm text-secondary">{{ $announcement->created_at->format('d M Y, H:i') }}</span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('teacher.announcements.edit', $announcement->id) }}" class="p-2 text-primary hover:bg-primary/10 rounded-lg transition-colors">
                                        <span class="material-symbols-outlined text-[20px]">edit</span>
                                    </a>
                                    <form action="{{ route('teacher.announcements.destroy', $announcement->id) }}" method="POST" class="inline" onsubmit="confirmAction(event, 'Apakah Anda yakin ingin menghapus pengumuman ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-error hover:bg-error/10 rounded-lg transition-colors">
                                            <span class="material-symbols-outlined text-[20px]">delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center">
                                <div class="flex flex-col items-center justify-center text-on-surface-variant">
                                    <span class="material-symbols-outlined text-[48px] mb-2 opacity-50">campaign</span>
                                    <p class="font-medium text-body-lg">Belum ada pengumuman</p>
                                    <p class="text-body-sm mt-1">Buat pengumuman baru untuk memberitahu siswa Anda.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($announcements->hasPages())
                <div class="p-4 border-t border-outline-variant">
                    {{ $announcements->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
