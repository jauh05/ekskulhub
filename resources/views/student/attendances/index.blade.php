<x-student-layout>
    <x-student-active-session-poller />
    
    <div class="mb-6">
        <h3 class="font-headline-lg text-headline-lg text-on-surface">Riwayat Absensi</h3>
    </div>

    <div class="bg-white rounded-xl border border-outline-variant soft-shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-outline-variant bg-surface-container-lowest">
                        <th class="p-4 font-label-md text-secondary">TANGGAL & WAKTU</th>
                        <th class="p-4 font-label-md text-secondary">EKSKUL</th>
                        <th class="p-4 font-label-md text-secondary">STATUS</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant">
                    @forelse($attendances as $att)
                    <tr class="hover:bg-surface-container-low transition-colors">
                        <td class="p-4 font-body-md text-on-surface-variant">
                            <span class="font-bold text-on-surface">{{ \Carbon\Carbon::parse($att->attendanceSession->schedule->activity_date)->format('d M Y') }}</span><br>
                            <span class="text-label-sm">{{ \Carbon\Carbon::parse($att->attendanceSession->schedule->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($att->attendanceSession->schedule->end_time)->format('H:i') }}</span>
                        </td>
                        <td class="p-4 font-body-md text-on-surface font-semibold">{{ $att->attendanceSession->schedule->extracurricular->name ?? '-' }}</td>
                        <td class="p-4">
                            @if($att->status == 'present')
                                <span class="px-3 py-1 bg-tertiary-container text-on-tertiary-container text-label-sm font-bold rounded-full">Hadir</span>
                            @elseif($att->status == 'absent')
                                <span class="px-3 py-1 bg-error-container text-on-error-container text-label-sm font-bold rounded-full">Alpa</span>
                            @elseif($att->status == 'sick')
                                <span class="px-3 py-1 bg-surface-variant text-on-surface-variant text-label-sm font-bold rounded-full">Sakit</span>
                            @else
                                <span class="px-3 py-1 bg-primary-container text-on-primary-container text-label-sm font-bold rounded-full">Izin</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="p-4 text-center text-on-surface-variant">Belum ada data absensi</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-outline-variant">
            {{ $attendances->links() }}
        </div>
    </div>
</x-student-layout>
