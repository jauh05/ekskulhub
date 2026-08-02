<x-app-layout>
    <div class="flex justify-between items-center mb-6">
        <h3 class="font-headline-lg text-headline-lg text-on-surface">Data Pengguna</h3>
        <a href="{{ route('admin.users.create') }}" class="bg-primary text-on-primary px-4 py-2 rounded-lg font-title-md hover:bg-primary-container transition-all">
            + Tambah Pengguna
        </a>
    </div>

    <div class="bg-white rounded-xl border border-outline-variant card-shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-outline-variant bg-surface-container-lowest">
                        <th class="p-4 font-label-md text-secondary">NAMA</th>
                        <th class="p-4 font-label-md text-secondary">EMAIL</th>
                        <th class="p-4 font-label-md text-secondary">ROLE</th>
                        <th class="p-4 font-label-md text-secondary">STATUS</th>
                        <th class="p-4 font-label-md text-secondary text-right">AKSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant">
                    @foreach($users as $user)
                    <tr class="hover:bg-surface-container-low transition-colors">
                        <td class="p-4 font-body-md text-on-surface font-semibold">{{ $user->name }}</td>
                        <td class="p-4 font-body-md text-on-surface-variant">{{ $user->email }}</td>
                        <td class="p-4">
                            <span class="px-3 py-1 bg-surface-container text-on-surface text-label-sm font-bold rounded-full capitalize">
                                {{ $user->role }}
                            </span>
                        </td>
                        <td class="p-4">
                            @if($user->status == 'active')
                                <span class="px-3 py-1 bg-tertiary-container text-on-tertiary-container text-label-sm font-bold rounded-full">Aktif</span>
                            @else
                                <span class="px-3 py-1 bg-error-container text-on-error-container text-label-sm font-bold rounded-full">Nonaktif</span>
                            @endif
                        </td>
                        <td class="p-4 flex justify-end gap-2">
                            <a href="{{ route('admin.users.edit', $user->id) }}" class="text-primary hover:bg-primary-container/20 p-2 rounded-full transition-colors">
                                <span class="material-symbols-outlined text-[20px]">edit</span>
                            </a>
                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-error hover:bg-error-container/20 p-2 rounded-full transition-colors">
                                    <span class="material-symbols-outlined text-[20px]">delete</span>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-outline-variant">
            {{ $users->links() }}
        </div>
    </div>
</x-app-layout>
