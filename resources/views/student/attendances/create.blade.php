<x-student-layout>
<div class="flex w-full flex-col gap-6">
    <x-student-page-header title="Presensi Ekskul" back="{{ route('student.dashboard') }}" />

    @if ($errors->any())
    <div class="rounded-2xl bg-error-container px-4 py-3 text-on-error-container">
        <div class="mb-2 flex items-center gap-2 text-label-md">
            <x-icon name="triangle-alert" class="size-[20px]" /> Terdapat kesalahan:
        </div>
        <ul class="list-inside list-disc text-body-sm">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="flex flex-col overflow-hidden rounded-2xl border border-outline-variant bg-surface" x-data="{ step: 'main', method: '' }">
        <!-- Jadwal -->
        <div class="flex flex-col gap-4 bg-dark p-5 text-surface">
            <div class="min-w-0">
                <p class="text-label-xs uppercase tracking-wider text-surface/70">Jadwal ekskul</p>
                <h3 class="mt-1 truncate text-title-lg font-bold">{{ $schedule->extracurricular->name }}</h3>
            </div>
            <div class="flex flex-wrap items-end justify-between gap-3">
                <p class="text-display-md font-bold leading-none">
                    {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }}
                    <span class="ml-1 text-title-md font-semibold text-surface/80">WIB</span>
                </p>
                <p class="text-body-sm text-surface/80">{{ \Carbon\Carbon::parse($schedule->activity_date)->translatedFormat('d F Y') }}</p>
            </div>
        </div>

        <form action="{{ route('student.attendances.store') }}" method="POST" enctype="multipart/form-data" class="flex flex-col gap-5 p-5" id="attendanceForm">
            @csrf
            <input type="hidden" name="schedule_id" value="{{ $schedule->id }}">
            <input type="hidden" name="type" :value="step === 'izin' ? 'izin' : 'hadir'">
            <input type="hidden" name="method" x-model="method">

            <!-- Langkah 1: pilihan utama (hadir atau izin) -->
            <div x-show="step === 'main'" x-transition.opacity class="flex flex-col gap-3">
                <button @click="step = 'hadir'" type="button" class="flex w-full items-center justify-center gap-3 rounded-2xl bg-primary-container px-4 py-4 text-title-md text-on-primary-container transition-opacity hover:opacity-90">
                    <x-icon name="user-check" class="size-[22px]" /> Hadir
                </button>

                <button @click="step = 'izin'" type="button" class="flex w-full items-center justify-center gap-3 rounded-2xl border border-outline-variant bg-surface px-4 py-4 text-title-md text-on-surface transition-colors hover:bg-surface-container">
                    <x-icon name="file-pen" class="size-[22px]" /> Izin / Sakit
                </button>
            </div>

            <!-- Langkah 2: form izin -->
            <div x-show="step === 'izin'" x-transition.opacity style="display: none;" class="flex flex-col gap-5">
                <button @click="step = 'main'" type="button" class="inline-flex items-center gap-1 text-label-md text-secondary hover:text-on-surface">
                    <x-icon name="arrow-left" class="size-[18px]" /> Kembali
                </button>

                <div class="space-y-1.5">
                    <label class="block text-label-md text-on-surface-variant">Alasan izin / sakit <span class="text-error">*</span></label>
                    <textarea name="reason" rows="3" class="w-full rounded-xl border border-outline-variant bg-surface px-3 py-2.5 text-body-md text-on-surface placeholder:text-outline focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20" placeholder="Sebutkan alasan dengan jelas..." :required="step === 'izin'"></textarea>
                </div>

                <div class="space-y-1.5">
                    <label class="block text-label-md text-on-surface-variant">Lampirkan bukti (opsional)</label>
                    <input type="file" name="proof" accept="image/*,.pdf" class="block w-full rounded-xl border border-outline-variant bg-surface px-3 py-2 text-body-md text-on-surface file:mr-4 file:rounded-full file:border-0 file:bg-primary-fixed file:px-4 file:py-2 file:text-label-md file:text-on-primary-fixed hover:file:bg-primary-fixed-dim">
                    <p class="text-body-xs text-secondary">Maks 1MB. Format: JPG, PNG, PDF (contoh: surat dokter).</p>
                </div>

                <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-primary-container px-4 py-3 text-label-lg text-on-primary-container transition-opacity hover:opacity-90">
                    <x-icon name="send" class="size-[20px]" /> Kirim pengajuan izin
                </button>
            </div>

            <!-- Langkah 2: pilih metode hadir -->
            <div x-show="step === 'hadir'" x-transition.opacity style="display: none;" class="flex flex-col gap-3">
                <button @click="step = 'main'" type="button" class="inline-flex items-center gap-1 text-label-md text-secondary hover:text-on-surface">
                    <x-icon name="arrow-left" class="size-[18px]" /> Batal
                </button>

                <p class="text-title-sm text-on-surface">Pilih metode kehadiran</p>

                <button @click="step = 'qr'; method = 'qr'; startQRScanner();" type="button" class="flex w-full items-center gap-4 rounded-2xl border border-outline-variant bg-surface p-4 text-left transition-colors hover:bg-surface-container">
                    <span class="flex size-11 shrink-0 items-center justify-center rounded-xl bg-primary-fixed text-on-primary-fixed">
                        <x-icon name="scan-qr-code" class="size-[22px]" />
                    </span>
                    <span class="min-w-0 flex-1 truncate text-title-sm text-on-surface">Scan QR</span>
                    <x-icon name="chevron-right" class="size-[18px] text-outline" />
                </button>

                <button @click="step = 'manual'; method = 'manual'" type="button" class="flex w-full items-center gap-4 rounded-2xl border border-outline-variant bg-surface p-4 text-left transition-colors hover:bg-surface-container">
                    <span class="flex size-11 shrink-0 items-center justify-center rounded-xl bg-primary-fixed text-on-primary-fixed">
                        <x-icon name="keyboard" class="size-[22px]" />
                    </span>
                    <span class="min-w-0 flex-1 truncate text-title-sm text-on-surface">Kode PIN manual</span>
                    <x-icon name="chevron-right" class="size-[18px] text-outline" />
                </button>

                <button @click="step = 'selfie'; method = 'selfie'" type="button" class="flex w-full items-center gap-4 rounded-2xl border border-outline-variant bg-surface p-4 text-left transition-colors hover:bg-surface-container">
                    <span class="flex size-11 shrink-0 items-center justify-center rounded-xl bg-primary-fixed text-on-primary-fixed">
                        <x-icon name="camera" class="size-[22px]" />
                    </span>
                    <span class="min-w-0 flex-1 truncate text-title-sm text-on-surface">Foto selfie</span>
                    <x-icon name="chevron-right" class="size-[18px] text-outline" />
                </button>
            </div>

            <!-- Langkah 3: scanner QR -->
            <div x-show="step === 'qr'" x-transition.opacity style="display: none;" class="flex flex-col gap-5">
                <button @click="step = 'hadir'; method = ''; stopQRScanner();" type="button" class="inline-flex items-center gap-1 text-label-md text-secondary hover:text-on-surface">
                    <x-icon name="arrow-left" class="size-[18px]" /> Ganti metode
                </button>

                <div class="flex flex-col items-center gap-2 text-center">
                    <h5 class="text-title-sm text-on-surface">Scan QR di layar guru</h5>
                    <p class="text-body-xs text-secondary">Arahkan kamera ke QR yang ditampilkan guru.</p>
                </div>

                <div class="mx-auto w-full max-w-[300px] rounded-2xl bg-primary-fixed/40 p-3">
                    <div id="reader" class="mx-auto w-full overflow-hidden rounded-xl border-2 border-primary bg-surface"></div>
                </div>
                <input type="hidden" name="qr_code" id="qr_code_input">
            </div>

            <!-- Langkah 3: kode PIN manual -->
            <div x-show="step === 'manual'" x-transition.opacity style="display: none;" class="flex flex-col gap-5">
                <button @click="step = 'hadir'; method = ''" type="button" class="inline-flex items-center gap-1 text-label-md text-secondary hover:text-on-surface">
                    <x-icon name="arrow-left" class="size-[18px]" /> Ganti metode
                </button>

                <div class="flex flex-col gap-2 text-center">
                    <label class="block text-title-sm text-on-surface">Masukkan PIN 6 digit</label>
                    <p class="text-body-xs text-secondary">Tanyakan kode PIN sesi kepada guru ekskulmu.</p>
                    <input type="text" name="session_code" class="mx-auto mt-3 block w-full max-w-[250px] rounded-xl border-2 border-primary bg-surface px-4 py-3 text-center font-mono text-headline-md uppercase tracking-[0.3em] text-on-surface focus:outline-none focus:ring-4 focus:ring-primary/20" maxlength="6" placeholder="------" :required="method === 'manual'">
                </div>

                <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-primary-container px-4 py-3 text-label-lg text-on-primary-container transition-opacity hover:opacity-90">
                    <x-icon name="circle-check" class="size-[20px]" /> Konfirmasi kehadiran
                </button>
            </div>

            <!-- Langkah 3: selfie -->
            <div x-show="step === 'selfie'" x-transition.opacity style="display: none;" class="flex flex-col gap-5">
                <button @click="step = 'hadir'; method = ''" type="button" class="inline-flex items-center gap-1 text-label-md text-secondary hover:text-on-surface">
                    <x-icon name="arrow-left" class="size-[18px]" /> Ganti metode
                </button>

                <div class="flex flex-col gap-2">
                    <label class="block text-center text-title-sm text-on-surface">Ambil foto bukti kehadiran</label>
                    <label class="relative flex h-48 w-full cursor-pointer flex-col items-center justify-center overflow-hidden rounded-2xl border-2 border-dashed border-primary bg-primary-fixed/40 transition-colors hover:bg-primary-fixed">
                        <div class="z-10 flex flex-col items-center justify-center gap-2 py-5" id="selfie-placeholder">
                            <x-icon name="camera" class="size-[40px] text-primary-container" />
                            <p class="text-label-md text-primary-container">Buka kamera</p>
                        </div>
                        <img id="selfie-preview" class="absolute inset-0 z-20 hidden h-full w-full object-cover">
                        <input type="file" name="selfie" accept="image/*" capture="user" class="hidden" id="selfie-input" :required="method === 'selfie'" onchange="previewSelfie(event)" />
                    </label>
                    <p class="text-center text-body-xs text-secondary">Pastikan wajah dan lingkungan terlihat jelas (maks 5MB).</p>
                </div>

                <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-primary-container px-4 py-3 text-label-lg text-on-primary-container transition-opacity hover:opacity-90">
                    <x-icon name="upload" class="size-[20px]" /> Kirim foto kehadiran
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    let html5QrCode = null;

    function startQRScanner() {
        if (html5QrCode) return; // already started

        html5QrCode = new Html5Qrcode("reader");

        // Start scanning directly using the back camera
        html5QrCode.start(
            { facingMode: "environment" },
            {
                fps: 10,
                qrbox: { width: 250, height: 250 },
                aspectRatio: 1.0
            },
            onScanSuccess,
            onScanFailure
        ).catch((err) => {
            console.error("Camera access error:", err);
            Swal.fire('Akses Ditolak', 'Gagal mengakses kamera. Pastikan Anda memberikan izin akses kamera pada browser Anda.', 'error');
        });
    }

    function stopQRScanner() {
        if (html5QrCode) {
            html5QrCode.stop().then(() => {
                html5QrCode.clear();
                html5QrCode = null;
            }).catch(error => {
                console.error("Failed to stop scanner. ", error);
            });
        }
    }

    function onScanSuccess(decodedText, decodedResult) {
        // Stop scanning and set the hidden input
        stopQRScanner();
        document.getElementById('qr_code_input').value = decodedText;

        // Automatically submit the form
        document.getElementById('attendanceForm').submit();
    }

    function onScanFailure(error) {
        // handle scan failure silently
    }

    function previewSelfie(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('selfie-preview');
                const placeholder = document.getElementById('selfie-placeholder');
                preview.src = e.target.result;
                preview.classList.remove('hidden');
                placeholder.classList.add('hidden');
            }
            reader.readAsDataURL(file);
        }
    }
</script>
</x-student-layout>
