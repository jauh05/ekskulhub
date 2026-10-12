<x-app-layout>
    <x-slot name="title">QR Code Absensi</x-slot>

    <div class="mx-auto max-w-lg space-y-6">
        <div>
            <a href="{{ route('teacher.schedules.index') }}" class="inline-flex items-center gap-1 text-label-md text-primary-container hover:underline">
                <x-icon name="arrow-left" class="size-[18px]" /> Kembali ke Jadwal
            </a>
            <p class="mt-2 text-body-md text-secondary">QR Code Absensi</p>
            <h3 class="mt-1 text-headline-md font-bold text-on-surface md:text-headline-lg">{{ $schedule->extracurricular->name }}</h3>
        </div>

        <!-- Ringkasan sesi -->
        <section class="rounded-xl bg-primary-container p-5 text-on-primary-container">
            <p class="text-label-xs font-bold uppercase tracking-wider opacity-80">{{ \Carbon\Carbon::parse($schedule->activity_date)->translatedFormat('l, d F Y') }}</p>
            <p class="mt-1 text-headline-md font-bold">{{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }} WIB</p>
            <p class="mt-2 text-body-md opacity-90">{{ $schedule->material ?? 'Kegiatan Ekskul' }}</p>
            <p class="mt-3 inline-flex items-center gap-1.5 text-body-sm opacity-90"><x-icon name="map-pin" class="size-[16px]" />{{ $schedule->location }}</p>
        </section>

        <!-- QR code -->
        <section class="flex flex-col items-center gap-5 rounded-xl border border-outline-variant bg-surface p-6 text-center">
            @php
                $qrData = json_encode([
                    'type' => 'attendance',
                    'schedule_id' => $schedule->id,
                    'timestamp' => now()->timestamp
                ]);
            @endphp
            {{-- Membutuhkan paket Simple QrCode --}}
            <div class="flex justify-center [&>svg]:size-[260px] sm:[&>svg]:size-[300px]">
                {!! QrCode::size(300)->generate($qrData) !!}
            </div>

            <p class="rounded-lg bg-surface-container-low p-4 text-body-md text-on-surface-variant">
                Minta siswa memindai QR Code ini menggunakan aplikasi mereka untuk mencatat kehadiran. QR code ini bersifat unik untuk sesi ini.
            </p>
        </section>
    </div>
</x-app-layout>
