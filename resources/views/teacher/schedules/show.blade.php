<x-app-layout>
    <div class="mb-6">
        <a href="{{ route('teacher.schedules.index') }}" class="text-primary hover:underline flex items-center gap-1 font-label-md mb-2">
            <span class="material-symbols-outlined text-[18px]">arrow_back</span> Kembali
        </a>
        <h3 class="font-headline-lg text-headline-lg text-on-surface">QR Code Absensi: {{ $schedule->extracurricular->name }}</h3>
    </div>

    <div class="bg-white p-8 rounded-xl border border-outline-variant card-shadow max-w-lg mx-auto text-center">
        <h4 class="font-title-lg text-title-lg text-on-surface mb-2">{{ $schedule->topic ?? 'Kegiatan Ekskul' }}</h4>
        <p class="text-secondary mb-6">{{ \Carbon\Carbon::parse($schedule->activity_date)->format('d F Y') }} | {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}</p>
        
        <div class="flex justify-center mb-6">
            @php
                $qrData = json_encode([
                    'type' => 'attendance',
                    'schedule_id' => $schedule->id,
                    'timestamp' => now()->timestamp
                ]);
            @endphp
            <!-- Requires Simple QrCode Package -->
            {!! QrCode::size(300)->generate($qrData) !!}
        </div>
        
        <p class="text-body-md text-on-surface-variant bg-surface-container-low p-4 rounded-lg">
            Minta siswa memindai QR Code ini menggunakan aplikasi mereka untuk mencatat kehadiran. QR code ini bersifat unik untuk sesi ini.
        </p>
    </div>
</x-app-layout>
