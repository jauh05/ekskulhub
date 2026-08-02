<x-student-layout>
    <div class="mb-6">
        <a href="{{ route('student.dashboard') }}" class="text-primary hover:underline flex items-center gap-1 font-label-md mb-2">
            <span class="material-symbols-outlined text-[18px]">arrow_back</span> Kembali
        </a>
        <h3 class="font-headline-lg text-headline-lg text-on-surface">Presensi Ekskul</h3>
    </div>

    @if(session('error'))
    <div class="mb-4 bg-error/10 border border-error/20 text-error px-4 py-3 rounded-lg flex items-center gap-2">
        <span class="material-symbols-outlined text-[20px]">error</span> {{ session('error') }}
    </div>
    @endif

    <div class="bg-white p-6 md:p-8 rounded-xl border border-outline-variant soft-shadow max-w-lg mx-auto" x-data="{ step: 'main', method: '' }">
        <h4 class="font-title-lg text-on-surface text-center mb-2">{{ $schedule->extracurricular->name }}</h4>
        <p class="text-secondary text-center mb-6">{{ \Carbon\Carbon::parse($schedule->activity_date)->format('d F Y') }} | {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} WIB</p>
        
        <form action="{{ route('student.attendances.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6" id="attendanceForm">
            @csrf
            <input type="hidden" name="schedule_id" value="{{ $schedule->id }}">
            <input type="hidden" name="type" :value="step === 'izin' ? 'izin' : 'hadir'">
            <input type="hidden" name="method" x-model="method">
            
            <!-- STEP 1: MAIN OPTIONS (HADIR vs IZIN) -->
            <div x-show="step === 'main'" x-transition.opacity class="flex flex-col gap-4">
                <button @click="step = 'hadir'" type="button" class="w-full bg-primary text-white py-4 rounded-xl font-title-lg font-bold hover:bg-primary/90 active:scale-95 transition-all flex items-center justify-center gap-3 shadow-md">
                    <span class="material-symbols-outlined text-[28px]">how_to_reg</span> HADIR
                </button>
                
                <button @click="step = 'izin'" type="button" class="w-full bg-surface-container-high text-on-surface py-4 rounded-xl font-title-lg font-bold border border-outline-variant hover:bg-surface-container-highest active:scale-95 transition-all flex items-center justify-center gap-3 shadow-sm">
                    <span class="material-symbols-outlined text-[28px]">edit_document</span> IZIN / SAKIT
                </button>
            </div>

            <!-- STEP 2: IZIN FORM -->
            <div x-show="step === 'izin'" x-transition.opacity style="display: none;" class="space-y-6">
                <button @click="step = 'main'" type="button" class="text-secondary hover:text-primary flex items-center gap-1 font-label-md mb-2">
                    <span class="material-symbols-outlined text-[18px]">arrow_back</span> Pilih Kembali
                </button>
                
                <div class="space-y-2">
                    <label class="font-label-md font-bold text-on-surface block">Alasan Izin / Sakit <span class="text-error">*</span></label>
                    <textarea name="reason" rows="3" class="w-full px-4 py-3 border border-outline-variant rounded-xl focus:ring-2 focus:ring-primary/20 bg-white" placeholder="Sebutkan alasan dengan jelas..." :required="step === 'izin'"></textarea>
                </div>
                
                <div class="space-y-2">
                    <label class="font-label-md font-bold text-on-surface block">Lampirkan Bukti (Opsional)</label>
                    <input type="file" name="proof" accept="image/*,.pdf" class="w-full px-4 py-3 border border-outline-variant rounded-xl bg-white text-body-md file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20">
                    <p class="text-xs text-secondary mt-1">Maks 1MB. Format: JPG, PNG, PDF (Misal: Surat Dokter).</p>
                </div>

                <button type="submit" class="w-full bg-primary text-white py-3 rounded-lg font-title-md hover:bg-primary/90 active:scale-95 transition-all flex items-center justify-center gap-2 shadow-sm">
                    <span class="material-symbols-outlined text-[20px]">send</span> Kirim Pengajuan Izin
                </button>
            </div>

            <!-- STEP 2: HADIR OPTIONS -->
            <div x-show="step === 'hadir'" x-transition.opacity style="display: none;" class="space-y-4">
                <button @click="step = 'main'" type="button" class="text-secondary hover:text-primary flex items-center gap-1 font-label-md mb-4">
                    <span class="material-symbols-outlined text-[18px]">arrow_back</span> Batal
                </button>
                
                <p class="text-center font-bold text-on-surface mb-2">Pilih Metode Kehadiran:</p>
                
                <button @click="step = 'qr'; method = 'qr'; startQRScanner();" type="button" class="w-full bg-[#10B981] text-white py-3 rounded-xl font-title-md font-bold hover:bg-[#10B981]/90 active:scale-95 transition-all flex items-center justify-center gap-3 shadow-md">
                    <span class="material-symbols-outlined">qr_code_scanner</span> Scan QR
                </button>
                
                <button @click="step = 'manual'; method = 'manual'" type="button" class="w-full bg-primary text-white py-3 rounded-xl font-title-md font-bold hover:bg-primary/90 active:scale-95 transition-all flex items-center justify-center gap-3 shadow-md">
                    <span class="material-symbols-outlined">keyboard</span> Manual Kode PIN
                </button>
                
                <button @click="step = 'selfie'; method = 'selfie'" type="button" class="w-full bg-[#F59E0B] text-white py-3 rounded-xl font-title-md font-bold hover:bg-[#F59E0B]/90 active:scale-95 transition-all flex items-center justify-center gap-3 shadow-md">
                    <span class="material-symbols-outlined">add_a_photo</span> Foto Selfie
                </button>
            </div>

            <!-- STEP 3: QR SCANNER -->
            <div x-show="step === 'qr'" x-transition.opacity style="display: none;" class="space-y-6 text-center">
                <button @click="step = 'hadir'; method = ''; stopQRScanner();" type="button" class="text-secondary hover:text-primary flex items-center gap-1 font-label-md mb-2">
                    <span class="material-symbols-outlined text-[18px]">arrow_back</span> Ganti Metode
                </button>
                
                <h5 class="font-bold text-on-surface">Scan QR di Layar Guru</h5>
                <div id="reader" class="mx-auto w-full max-w-[300px] overflow-hidden rounded-xl border-2 border-primary"></div>
                <input type="hidden" name="qr_code" id="qr_code_input">
                <p class="text-xs text-secondary mt-2">Arahkan kamera ke QR Code yang ditampilkan oleh guru Anda.</p>
            </div>

            <!-- STEP 3: MANUAL KODE -->
            <div x-show="step === 'manual'" x-transition.opacity style="display: none;" class="space-y-6">
                <button @click="step = 'hadir'; method = ''" type="button" class="text-secondary hover:text-primary flex items-center gap-1 font-label-md mb-2">
                    <span class="material-symbols-outlined text-[18px]">arrow_back</span> Ganti Metode
                </button>
                
                <div class="space-y-2 text-center">
                    <label class="font-label-lg font-bold text-on-surface block">Masukkan 6-Digit PIN</label>
                    <p class="text-xs text-secondary mb-4">Tanyakan kode PIN sesi kepada guru ekskul Anda.</p>
                    <input type="text" name="session_code" class="w-full max-w-[250px] mx-auto text-center text-2xl tracking-[0.3em] font-mono font-bold px-4 py-3 border-2 border-primary rounded-xl focus:ring-4 focus:ring-primary/20 bg-white uppercase" maxlength="6" placeholder="------" :required="method === 'manual'">
                </div>

                <button type="submit" class="w-full bg-primary text-white py-3 rounded-lg font-title-md hover:bg-primary/90 active:scale-95 transition-all flex items-center justify-center gap-2 shadow-sm mt-4">
                    <span class="material-symbols-outlined text-[20px]">check_circle</span> Konfirmasi Kehadiran
                </button>
            </div>

            <!-- STEP 3: SELFIE -->
            <div x-show="step === 'selfie'" x-transition.opacity style="display: none;" class="space-y-6">
                <button @click="step = 'hadir'; method = ''" type="button" class="text-secondary hover:text-primary flex items-center gap-1 font-label-md mb-2">
                    <span class="material-symbols-outlined text-[18px]">arrow_back</span> Ganti Metode
                </button>
                
                <div class="space-y-2">
                    <label class="font-label-md font-bold text-on-surface block text-center">Ambil Foto Bukti Kehadiran</label>
                    <div class="flex items-center justify-center w-full">
                        <label class="flex flex-col items-center justify-center w-full h-48 border-2 border-primary border-dashed rounded-xl cursor-pointer bg-primary/5 hover:bg-primary/10 transition-colors relative overflow-hidden">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6 text-primary z-10" id="selfie-placeholder">
                                <span class="material-symbols-outlined text-[40px] mb-2">photo_camera</span>
                                <p class="mb-2 text-sm font-bold">Buka Kamera</p>
                            </div>
                            <img id="selfie-preview" class="absolute inset-0 w-full h-full object-cover hidden z-20">
                            <input type="file" name="selfie" accept="image/*" capture="user" class="hidden" id="selfie-input" :required="method === 'selfie'" onchange="previewSelfie(event)" />
                        </label>
                    </div>
                    <p class="text-xs text-center text-secondary">Pastikan wajah dan lingkungan terlihat jelas (Max 5MB).</p>
                </div>

                <button type="submit" class="w-full bg-primary text-white py-3 rounded-lg font-title-md hover:bg-primary/90 active:scale-95 transition-all flex items-center justify-center gap-2 shadow-sm">
                    <span class="material-symbols-outlined text-[20px]">upload</span> Kirim Foto Kehadiran
                </button>
            </div>

        </form>
    </div>

    <!-- HTML5 QR Code Scanner Script -->
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <script>
        let html5QrcodeScanner = null;

        function startQRScanner() {
            if (html5QrcodeScanner) return; // already started
            
            html5QrcodeScanner = new Html5QrcodeScanner(
                "reader",
                { fps: 10, qrbox: {width: 250, height: 250}, aspectRatio: 1.0 },
                false
            );
            
            html5QrcodeScanner.render(onScanSuccess, onScanFailure);
        }
        
        function stopQRScanner() {
            if (html5QrcodeScanner) {
                html5QrcodeScanner.clear().catch(error => {
                    console.error("Failed to clear html5QrcodeScanner. ", error);
                });
                html5QrcodeScanner = null;
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
            // handle scan failure, usually better to ignore and keep scanning.
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
