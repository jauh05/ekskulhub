<x-app-layout>
    <x-slot name="title">Penilaian</x-slot>

    @php
        $jumlahSudah = $baris->where('sudah_dinilai', true)->count();
        $urlKolom = route('teacher.grading.components.update', ['component' => '__ID__']);
        $urlHapusKolom = route('teacher.grading.components.destroy', ['component' => '__ID__']);

        $dataGrid = [
            'ekskulId' => $ekskul?->id,
            'csrf' => csrf_token(),
            'urls' => [
                'tambahKolom' => route('teacher.grading.components.store'),
                'ubahKolom' => $urlKolom,
                'hapusKolom' => $urlHapusKolom,
                'simpanNilai' => route('teacher.grading.scores.update'),
            ],
            'komponen' => $komponen->values()->map(fn ($c, $i) => [
                'id' => $c->id,
                'name' => $c->name,
                'weight' => (float) $c->weight,
                'dasar' => $i === 0,
            ])->all(),
            'baris' => $baris->map(fn ($b) => [
                'registration_id' => $b['registration_id'],
                'nama' => $b['nama'],
                'kelas' => $b['kelas'],
                'nilai' => (object) $b['nilai'],
                'nilai_akhir' => $b['nilai_akhir'],
                'predikat' => $b['predikat'],
                'lulus' => $b['lulus'],
                'sudah_dinilai' => $b['sudah_dinilai'],
                'bobot_lengkap' => $b['bobot_lengkap'],
            ])->values(),
        ];
    @endphp

    <script>
        window.penilaianData = @json($dataGrid, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
    </script>

    <div class="space-y-6" x-data="penilaianGrid()" x-init="init()">
        <!-- Header -->
        <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
            <div>
                <p class="text-body-md text-secondary">Input nilai per siswa. Klik <span class="font-semibold text-primary">+</span> pada sel kosong untuk mengisi.</p>
                <h3 class="mt-1 text-headline-md font-bold text-on-surface md:text-headline-lg">Penilaian</h3>
            </div>

            @if($ekskuls->isNotEmpty())
            <div class="flex flex-wrap items-center gap-3">
                <form method="GET" action="{{ route('teacher.grading.index') }}">
                    <label class="sr-only" for="ekskul_id">Ekstrakurikuler</label>
                    <select id="ekskul_id" name="ekskul_id" onchange="this.form.submit()"
                        class="rounded-xl border border-outline-variant bg-surface py-2.5 pl-4 pr-10 text-label-lg text-on-surface shadow-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
                        @foreach($ekskuls as $e)
                            <option value="{{ $e->id }}" @selected($ekskul && $ekskul->id === $e->id)>{{ $e->name }}</option>
                        @endforeach
                    </select>
                </form>
                <button type="button" @click="tambahKolom()"
                    class="inline-flex items-center gap-2 rounded-xl bg-primary px-5 py-2.5 text-label-lg text-on-primary shadow-md transition hover:-translate-y-0.5 hover:shadow-lg">
                    <x-icon name="plus" class="size-[18px]" /> Tambah kolom
                </button>
            </div>
            @endif
        </div>

        @if($ekskuls->isEmpty())
        <div class="flex flex-col items-center gap-3 rounded-2xl border border-dashed border-outline-variant bg-surface p-10 text-center">
            <span class="flex size-14 items-center justify-center rounded-2xl bg-primary-fixed text-primary">
                <x-icon name="star" class="size-[24px]" />
            </span>
            <p class="text-title-md font-bold text-on-surface">Belum ada ekstrakurikuler</p>
            <p class="max-w-md text-body-md text-secondary">Penilaian bisa diisi setelah Anda mengajar minimal satu ekstrakurikuler.</p>
        </div>
        @else
        <!-- Ringkasan -->
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
            <div class="flex items-center gap-4 rounded-2xl border border-outline-variant bg-surface p-5 shadow-sm">
                <span class="flex size-12 items-center justify-center rounded-xl bg-primary-fixed text-primary"><x-icon name="users" class="size-[22px]" /></span>
                <div>
                    <p class="text-label-md text-secondary">Siswa aktif</p>
                    <p class="text-headline-sm font-bold text-on-surface" x-text="baris.length"></p>
                </div>
            </div>
            <div class="flex items-center gap-4 rounded-2xl border border-outline-variant bg-surface p-5 shadow-sm">
                <span class="flex size-12 items-center justify-center rounded-xl bg-accent text-on-accent"><x-icon name="list-checks" class="size-[22px]" /></span>
                <div>
                    <p class="text-label-md text-secondary">Kolom nilai</p>
                    <p class="text-headline-sm font-bold text-on-surface" x-text="komponen.length"></p>
                </div>
            </div>
            <div class="flex items-center gap-4 rounded-2xl bg-primary p-5 text-on-primary shadow-md">
                <span class="flex size-12 items-center justify-center rounded-xl bg-white/15"><x-icon name="circle-check" class="size-[22px]" /></span>
                <div>
                    <p class="text-label-md opacity-90">Sudah dinilai</p>
                    <p class="text-headline-sm font-bold"><span x-text="jumlahSudah()"></span><span class="text-title-sm opacity-80">/<span x-text="baris.length"></span></span></p>
                </div>
            </div>
        </div>

        <!-- Pencarian -->
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <label class="relative w-full sm:max-w-sm">
                <span class="sr-only">Cari siswa</span>
                <x-icon name="search" class="pointer-events-none absolute left-4 top-1/2 size-[18px] -translate-y-1/2 text-outline" />
                <input type="search" x-model="cari" placeholder="Cari nama atau kelas..."
                    class="w-full rounded-xl border border-outline-variant bg-surface py-2.5 pl-11 pr-4 text-body-md text-on-surface shadow-sm placeholder:text-outline focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
            </label>
            <p class="text-label-md text-secondary">Menampilkan <span class="font-semibold text-on-surface" x-text="rowsFiltered().length"></span> dari <span x-text="baris.length"></span> siswa</p>
        </div>

        <!-- Tabel seperti spreadsheet -->
        <div class="overflow-hidden rounded-2xl border border-outline-variant bg-surface shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full min-w-max border-collapse text-left text-body-md">
                    <thead>
                        <tr class="bg-primary-fixed/60 text-label-md text-on-primary-fixed">
                            <th class="w-14 border-b border-r border-outline-variant px-4 py-3 text-center font-semibold">No</th>
                            <th class="w-24 border-b border-r border-outline-variant px-4 py-3 font-semibold">Kelas</th>
                            <th class="sticky left-0 z-10 min-w-[7.5rem] border-b border-r border-outline-variant bg-primary-fixed px-3 py-3 font-semibold sm:min-w-[14rem] sm:px-4">Nama</th>

                            <template x-for="k in komponen" :key="k.id">
                                <th class="min-w-[10rem] border-b border-r border-outline-variant px-4 py-3">
                                    <div class="flex items-center justify-between gap-2">
                                        <button type="button" @click="ubahNama(k)" title="Klik untuk mengganti nama kolom"
                                            class="min-w-0 truncate text-left font-semibold hover:underline" x-text="k.name"></button>
                                        <div class="flex shrink-0 items-center gap-1">
                                            <button type="button" @click="ubahNama(k)" aria-label="Ganti nama kolom"
                                                class="flex size-7 items-center justify-center rounded-lg text-on-primary-fixed transition hover:bg-white/60">
                                                <x-icon name="pencil" class="size-[14px]" />
                                            </button>
                                            <button type="button" x-show="!k.dasar" @click="hapusKolom(k)" aria-label="Hapus kolom"
                                                class="flex size-7 items-center justify-center rounded-lg text-error transition hover:bg-error-container">
                                                <x-icon name="trash-2" class="size-[14px]" />
                                            </button>
                                        </div>
                                    </div>
                                    <span x-show="k.dasar" class="mt-1 inline-flex rounded-full bg-white/70 px-2 py-0.5 text-label-xs font-semibold">Kolom utama</span>
                                </th>
                            </template>

                            <th class="w-28 border-b border-r border-outline-variant px-3 py-3 text-center">
                                <button type="button" @click="tambahKolom()"
                                    class="inline-flex items-center gap-1 rounded-lg border border-dashed border-primary px-3 py-1.5 text-label-md font-semibold text-primary transition hover:bg-primary hover:text-on-primary">
                                    <x-icon name="plus" class="size-[14px]" /> Kolom
                                </button>
                            </th>
                            <th class="w-32 border-b border-r border-outline-variant px-4 py-3 text-center font-semibold">Nilai akhir</th>
                            <th class="w-36 border-b border-outline-variant px-4 py-3 text-center font-semibold">Status</th>
                        </tr>
                    </thead>

                    <tbody>
                        <template x-for="(b, idx) in rowsFiltered()" :key="b.registration_id">
                            <tr class="group border-b border-outline-variant transition-colors hover:bg-primary-fixed/25">
                                <td class="border-r border-outline-variant px-4 py-2.5 text-center text-secondary" x-text="idx + 1"></td>
                                <td class="border-r border-outline-variant px-4 py-2.5 text-secondary" x-text="b.kelas"></td>
                                <td class="sticky left-0 z-10 border-r border-outline-variant bg-surface px-3 py-2.5 font-semibold text-on-surface group-hover:bg-primary-fixed/25 sm:px-4" :title="b.nama">
                                    <span class="sm:hidden" x-text="singkat(b.nama)"></span>
                                    <span class="hidden sm:inline" x-text="b.nama"></span>
                                </td>

                                <template x-for="k in komponen" :key="k.id">
                                    <td class="border-r border-outline-variant p-1.5 text-center">
                                        <template x-if="editing && editing.reg === b.registration_id && editing.comp === k.id">
                                            <input type="number" min="0" max="100" step="0.01" inputmode="decimal"
                                                x-model="editing.value"
                                                x-init="$nextTick(() => $el.focus())"
                                                @keydown.enter.prevent="simpan(b, k, true)"
                                                @keydown.escape.prevent="batal()"
                                                @blur="simpan(b, k, false)"
                                                class="w-full min-w-[6rem] rounded-lg border-2 border-primary bg-surface px-3 py-2 text-center font-bold text-on-surface focus:outline-none focus:ring-4 focus:ring-primary/20">
                                        </template>

                                        <template x-if="!(editing && editing.reg === b.registration_id && editing.comp === k.id)">
                                            <button type="button" @click="mulai(b, k)"
                                                class="w-full rounded-lg px-3 py-2 transition"
                                                :class="sudahAda(b, k) ? 'font-bold text-on-surface hover:bg-primary-fixed' : 'border border-dashed border-primary/50 text-primary hover:border-primary hover:bg-primary-fixed'">
                                                <span x-show="!sudahAda(b, k)" class="text-title-sm">+</span>
                                                <span x-show="sudahAda(b, k)" x-text="formatNilai(b.nilai[k.id])"></span>
                                            </button>
                                        </template>
                                    </td>
                                </template>

                                <td class="border-r border-outline-variant bg-surface-container-low/60"></td>
                                <td class="border-r border-outline-variant px-4 py-2.5 text-center font-bold text-on-surface">
                                    <span x-show="b.nilai_akhir !== null" x-text="formatNilai(b.nilai_akhir)"></span>
                                    <span x-show="b.nilai_akhir === null" class="font-normal text-outline">-</span>
                                </td>
                                <td class="px-4 py-2.5 text-center">
                                    <span x-show="b.lulus === null && !b.sudah_dinilai" class="inline-flex rounded-full border border-outline-variant px-2.5 py-0.5 text-label-xs font-semibold text-secondary">Belum dinilai</span>
                                    <span x-show="b.lulus === null && b.sudah_dinilai" class="inline-flex rounded-full bg-primary-fixed px-2.5 py-0.5 text-label-xs font-semibold text-on-primary-fixed" title="Predikat dihitung setelah total bobot 100%">Bobot belum 100%</span>
                                    <span x-show="b.lulus === true" class="inline-flex items-center gap-1 rounded-full bg-tertiary-container px-2.5 py-0.5 text-label-xs font-bold text-on-tertiary-container">
                                        <span x-text="b.predikat"></span> &middot; Lulus
                                    </span>
                                    <span x-show="b.lulus === false" class="inline-flex items-center gap-1 rounded-full bg-error-container px-2.5 py-0.5 text-label-xs font-bold text-on-error-container">
                                        <span x-text="b.predikat"></span> &middot; Tidak lulus
                                    </span>
                                </td>
                            </tr>
                        </template>

                        <tr x-show="rowsFiltered().length === 0">
                            <td :colspan="komponen.length + 6" class="px-4 py-12 text-center text-body-md text-secondary">
                                <span x-show="baris.length === 0">Belum ada siswa yang disetujui di ekstrakurikuler ini.</span>
                                <span x-show="baris.length > 0">Tidak ada siswa yang cocok dengan pencarian.</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <p class="text-body-sm text-secondary">
            Tekan <span class="rounded-md border border-outline-variant bg-surface px-1.5 py-0.5 font-mono text-label-xs">Enter</span> untuk menyimpan dan pindah ke siswa berikutnya.
            Tekan <span class="rounded-md border border-outline-variant bg-surface px-1.5 py-0.5 font-mono text-label-xs">Esc</span> untuk membatalkan. Nilai kosong dihitung 0 saat menghitung nilai akhir.
        </p>
        @endif
    </div>

    @push('scripts')
    <script>
        function penilaianGrid() {
            const data = window.penilaianData;

            return {
                komponen: data.komponen,
                baris: data.baris,
                cari: '',
                editing: null,

                init() {
                    const pesan = sessionStorage.getItem('penilaian_flash');
                    if (pesan) {
                        sessionStorage.removeItem('penilaian_flash');
                        Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: pesan, showConfirmButton: false, timer: 2200 });
                    }
                },

                url(template, id) {
                    return template.replace('__ID__', id);
                },

                async kirim(method, url, body) {
                    const res = await fetch(url, {
                        method,
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': data.csrf,
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                        body: JSON.stringify(body ?? {}),
                    });
                    const json = await res.json().catch(() => ({}));
                    return { ok: res.ok, json };
                },

                gagal(json, cadangan) {
                    Swal.fire({ icon: 'error', title: 'Gagal', text: json?.message ?? cadangan, confirmButtonText: 'Mengerti' });
                },

                muatUlang(pesan) {
                    sessionStorage.setItem('penilaian_flash', pesan);
                    window.location.reload();
                },

                rowsFiltered() {
                    const q = this.cari.trim().toLowerCase();
                    if (q === '') return this.baris;
                    return this.baris.filter(b =>
                        b.nama.toLowerCase().includes(q) || String(b.kelas).toLowerCase().includes(q)
                    );
                },

                jumlahSudah() {
                    return this.baris.filter(b => b.sudah_dinilai).length;
                },

                sudahAda(b, k) {
                    const v = b.nilai[k.id];
                    return v !== null && v !== undefined;
                },

                /** Di layar kecil, nama dipotong 6 huruf. */
                singkat(nama) {
                    const huruf = [...String(nama)];
                    return huruf.length > 6 ? huruf.slice(0, 6).join('').trimEnd() + '…' : nama;
                },

                formatNilai(v) {
                    if (v === null || v === undefined || v === '') return '+';
                    return Number(v).toLocaleString('id-ID', { maximumFractionDigits: 2 });
                },

                mulai(b, k) {
                    const v = b.nilai[k.id];
                    this.editing = { reg: b.registration_id, comp: k.id, value: v ?? '' };
                },

                batal() {
                    this.editing = null;
                },

                async simpan(b, k, lanjut) {
                    if (!this.editing || this.editing.reg !== b.registration_id || this.editing.comp !== k.id) return;

                    const mentah = String(this.editing.value ?? '').trim().replace(',', '.');
                    this.editing = null;

                    let nilai = mentah === '' ? null : Number(mentah);
                    if (nilai !== null && (Number.isNaN(nilai) || nilai < 0 || nilai > 100)) {
                        Swal.fire({ icon: 'warning', title: 'Nilai tidak valid', text: 'Isi nilai antara 0 sampai 100.', confirmButtonText: 'Mengerti' });
                        return;
                    }

                    const { ok, json } = await this.kirim('PUT', data.urls.simpanNilai, {
                        registration_id: b.registration_id,
                        component_id: k.id,
                        score: nilai,
                    });

                    if (!ok) {
                        this.gagal(json, 'Nilai tidak tersimpan.');
                        return;
                    }

                    this.perbarui(json.baris);

                    if (lanjut) {
                        const daftar = this.rowsFiltered();
                        const i = daftar.findIndex(x => x.registration_id === b.registration_id);
                        if (i > -1 && daftar[i + 1]) {
                            this.mulai(daftar[i + 1], k);
                        }
                    }
                },

                perbarui(baru) {
                    if (!baru) return;
                    const i = this.baris.findIndex(x => x.registration_id === baru.registration_id);
                    if (i > -1) {
                        this.baris.splice(i, 1, { ...baru, nilai: { ...baru.nilai } });
                    }
                },

                async tambahKolom() {
                    const { value, isConfirmed } = await Swal.fire({
                        title: 'Tambah kolom nilai',
                        input: 'text',
                        inputLabel: 'Nama kolom (misal: Penilaian Harian)',
                        inputValue: 'Nilai ' + (this.komponen.length + 1),
                        showCancelButton: true,
                        confirmButtonText: 'Tambah',
                        cancelButtonText: 'Batal',
                        inputValidator: (v) => (!v || !v.trim()) ? 'Nama kolom wajib diisi.' : null,
                    });
                    if (!isConfirmed) return;

                    const { ok, json } = await this.kirim('POST', data.urls.tambahKolom, {
                        extracurricular_id: data.ekskulId,
                        name: value.trim(),
                    });
                    if (!ok) return this.gagal(json, 'Kolom tidak bisa ditambahkan.');

                    this.muatUlang(json.message);
                },

                async ubahNama(k) {
                    const { value, isConfirmed } = await Swal.fire({
                        title: 'Ganti nama kolom',
                        input: 'text',
                        inputValue: k.name,
                        showCancelButton: true,
                        confirmButtonText: 'Simpan',
                        cancelButtonText: 'Batal',
                        inputValidator: (v) => (!v || !v.trim()) ? 'Nama kolom wajib diisi.' : null,
                    });
                    if (!isConfirmed || value.trim() === k.name) return;

                    const { ok, json } = await this.kirim('PATCH', this.url(data.urls.ubahKolom, k.id), { name: value.trim() });
                    if (!ok) return this.gagal(json, 'Nama kolom tidak bisa diubah.');

                    this.muatUlang(json.message);
                },

                async hapusKolom(k) {
                    const terisi = this.baris.filter(b => this.sudahAda(b, k)).length;

                    const pertama = await Swal.fire({
                        icon: 'warning',
                        title: `Hapus kolom "${k.name}"?`,
                        text: terisi > 0 ? `Kolom ini sudah berisi ${terisi} nilai.` : 'Kolom ini belum berisi nilai.',
                        showCancelButton: true,
                        confirmButtonText: 'Lanjut',
                        cancelButtonText: 'Batal',
                    });
                    if (!pertama.isConfirmed) return;

                    let konfirmasiGanda = false;
                    if (terisi > 0) {
                        const kedua = await Swal.fire({
                            icon: 'error',
                            title: 'Yakin benar-benar menghapus?',
                            text: 'Semua nilai di kolom ini akan terhapus permanen dan tidak bisa dikembalikan.',
                            showCancelButton: true,
                            confirmButtonText: 'Ya, hapus permanen',
                            cancelButtonText: 'Batal',
                            customClass: { confirmButton: 'swal-danger' },
                        });
                        if (!kedua.isConfirmed) return;
                        konfirmasiGanda = true;
                    }

                    const { ok, json } = await this.kirim('DELETE', this.url(data.urls.hapusKolom, k.id), { konfirmasi_ganda: konfirmasiGanda });
                    if (!ok) return this.gagal(json, 'Kolom tidak bisa dihapus.');

                    this.muatUlang(json.message);
                },
            };
        }
    </script>
    @endpush
</x-app-layout>
