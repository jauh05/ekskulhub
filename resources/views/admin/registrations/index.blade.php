<x-app-layout>
    <div class="mb-6">
        <h3 class="font-headline-lg text-headline-lg text-on-surface">Pendaftaran Ekstrakurikuler</h3>
    </div>

    <div class="bg-white rounded-xl border border-outline-variant card-shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-outline-variant bg-surface-container-lowest">
                        <th class="p-4 font-label-md text-secondary">TANGGAL</th>
                        <th class="p-4 font-label-md text-secondary">SISWA</th>
                        <th class="p-4 font-label-md text-secondary">EKSKUL</th>
                        <th class="p-4 font-label-md text-secondary">STATUS</th>
                        <th class="p-4 font-label-md text-secondary">AKSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant">
                    @foreach($registrations as $reg)
                    <tr class="hover:bg-surface-container-low transition-colors">
                        <td class="p-4 font-body-md text-on-surface-variant">{{ $reg->created_at->format('d M Y') }}</td>
                        <td class="p-4 font-body-md text-on-surface font-semibold">{{ $reg->student->name ?? '-' }}</td>
                        <td class="p-4 font-body-md text-on-surface-variant">{{ $reg->extracurricular->name ?? '-' }}</td>
                        <td class="p-4">
                            @if($reg->status == 'approved')
                                <span class="px-3 py-1 bg-tertiary-container text-on-tertiary-container text-label-sm font-bold rounded-full">Diterima</span>
                            @elseif($reg->status == 'rejected')
                                <span class="px-3 py-1 bg-error-container text-on-error-container text-label-sm font-bold rounded-full">Ditolak</span>
                            @else
                                <span class="px-3 py-1 bg-surface-variant text-on-surface-variant text-label-sm font-bold rounded-full">Menunggu</span>
                            @endif
                        </td>
                        <td class="p-4">
                            @if($reg->status == 'pending')
                            <form action="{{ route('admin.registrations.update', $reg->id) }}" method="POST" class="flex gap-2">
                                @csrf @method('PATCH')
                                <button type="submit" name="status" value="approved" class="text-tertiary-container hover:bg-tertiary-container/20 p-2 rounded-full transition-colors tooltip" title="Terima">
                                    <span class="material-symbols-outlined text-[20px] text-tertiary">check_circle</span>
                                </button>
                                <button type="submit" name="status" value="rejected" class="text-error hover:bg-error-container/20 p-2 rounded-full transition-colors tooltip" title="Tolak">
                                    <span class="material-symbols-outlined text-[20px]">cancel</span>
                                </button>
                            </form>
                            @else
                            <span class="text-outline font-label-sm">-</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-outline-variant">
            {{ $registrations->links() }}
        </div>
    </div>
</x-app-layout>
