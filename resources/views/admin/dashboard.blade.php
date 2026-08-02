<x-app-layout>
    <div class="pt-24 px-8 pb-12">
<!-- Welcome Header -->
<div class="mb-8">
<h3 class="font-headline-lg text-headline-lg text-on-surface">Selamat Datang, Admin!</h3>
<p class="text-body-lg text-secondary">Pantau perkembangan siswa dan jadwal kegiatan hari ini.</p>
</div>
<!-- Ringkasan Statistik (4 Cards) -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
<!-- Total Siswa -->
<div class="bg-white p-6 rounded-xl border border-outline-variant card-shadow relative overflow-hidden group">
<div class="absolute top-0 right-0 p-3 opacity-10 group-hover:scale-110 transition-transform">
<span class="material-symbols-outlined text-6xl" data-weight="fill">group</span>
</div>
<p class="text-label-md font-semibold text-secondary mb-1">Total Siswa Aktif</p>
<div class="flex items-end gap-2">
<span class="text-headline-md font-bold text-on-surface">{{ $totalStudents ?? 0 }}</span>
<span class="text-label-sm text-tertiary mb-1.5 font-bold flex items-center">
<span class="material-symbols-outlined text-[14px]">trending_up</span> 12%
                        </span>
</div>
<p class="text-label-sm text-secondary mt-2">Siswa aktif terdaftar</p>
</div>
<!-- Total Guru -->
<div class="bg-white p-6 rounded-xl border border-outline-variant card-shadow group">
<p class="text-label-md font-semibold text-secondary mb-1">Total Guru</p>
<div class="flex items-end gap-2">
<span class="text-headline-md font-bold text-on-surface">{{ $totalTeachers ?? 0 }}</span>
<span class="text-label-sm text-secondary mb-1.5">Orang</span>
</div>
<p class="text-label-sm text-secondary mt-2">4 sesi telah selesai</p>
</div>
<!-- Total Ekskul -->
<div class="bg-white p-6 rounded-xl border border-outline-variant card-shadow group">
<p class="text-label-md font-semibold text-secondary mb-1">Total Ekskul</p>
<div class="flex items-end gap-2">
<span class="text-headline-md font-bold text-on-surface">{{ $totalEkskul ?? 0 }}</span>
<span class="text-label-sm text-secondary mb-1.5">Aktif</span>
</div>
<div class="w-full bg-surface-container-low h-1.5 rounded-full mt-3 overflow-hidden">
<div class="bg-primary h-full" style="width: 100%"></div>
</div>
<p class="text-label-sm text-secondary mt-2">Seluruh ekskul</p>
</div>
<!-- Pendaftaran Pending -->
<div class="bg-[#FFFBEB] p-6 rounded-xl border border-[#FDE68A] card-shadow group">
<p class="text-label-md font-semibold text-[#92400E] mb-1">Pendaftaran Pending</p>
<div class="flex items-end gap-2">
<span class="text-headline-md font-bold text-[#92400E]">{{ $pendingRegistrations ?? 0 }}</span>
<span class="text-label-sm text-[#92400E] mb-1.5">Siswa</span>
</div>
<div class="flex items-center gap-2 mt-2 text-[#92400E]">
<span class="material-symbols-outlined text-[18px]">info</span>
<p class="text-label-sm font-semibold">Menunggu persetujuan</p>
</div>
</div>
</div>
<!-- Layout Grid: Main Content -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
<!-- Jadwal Terdekat (Card Utama) -->
<div class="lg:col-span-2 space-y-8">
<section>
<div class="flex items-center justify-between mb-4">
<h4 class="font-title-lg text-title-lg text-on-surface">Jadwal Terdekat</h4>
<a class="text-primary font-label-md hover:underline" href="#">Lihat Semua Kalender</a>
</div>
<div class="bg-white rounded-xl border border-outline-variant card-shadow overflow-hidden">
<div class="p-8">
<div class="flex flex-col md:flex-row justify-between gap-6">
<div class="space-y-4">
<div>
<span class="px-3 py-1 bg-primary-container text-on-primary-container rounded-full text-label-sm font-bold">SESI MENDATANG</span>
<h5 class="font-headline-md text-headline-md font-bold text-on-surface mt-3">Ekskul Basket Putra</h5>
<p class="text-body-lg text-secondary">Latihan Intensif Mingguan</p>
</div>
<div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
<div class="flex items-center gap-3 text-on-surface-variant">
<div class="w-10 h-10 bg-surface-container rounded-lg flex items-center justify-center">
<span class="material-symbols-outlined text-primary">calendar_month</span>
</div>
<div>
<p class="text-label-sm text-secondary">Tanggal</p>
<p class="font-body-md font-semibold">Selasa, 24 Okt 2024</p>
</div>
</div>
<div class="flex items-center gap-3 text-on-surface-variant">
<div class="w-10 h-10 bg-surface-container rounded-lg flex items-center justify-center">
<span class="material-symbols-outlined text-primary">schedule</span>
</div>
<div>
<p class="text-label-sm text-secondary">Waktu</p>
<p class="font-body-md font-semibold">15:30 - 17:30 WIB</p>
</div>
</div>
<div class="flex items-center gap-3 text-on-surface-variant">
<div class="w-10 h-10 bg-surface-container rounded-lg flex items-center justify-center">
<span class="material-symbols-outlined text-primary">location_on</span>
</div>
<div>
<p class="text-label-sm text-secondary">Lokasi</p>
<p class="font-body-md font-semibold">GOR Utama Sekolah</p>
</div>
</div>
<div class="flex items-center gap-3 text-on-surface-variant">
<div class="w-10 h-10 bg-surface-container rounded-lg flex items-center justify-center">
<span class="material-symbols-outlined text-primary">menu_book</span>
</div>
<div>
<p class="text-label-sm text-secondary">Materi</p>
<p class="font-body-md font-semibold">Drilling Layup &amp; Free Throw</p>
</div>
</div>
</div>
</div>
<div class="w-full md:w-64 h-48 rounded-xl overflow-hidden border border-outline-variant shrink-0">
<img class="w-full h-full object-cover" data-alt="A cinematic, high-action photograph of a modern indoor basketball court with polished wooden floors and professional hoops. The atmosphere is energetic yet orderly, with warm gym lighting casting soft shadows. The scene captures the professional environment of a school sports program. High resolution, vibrant colors, minimalist aesthetic." src="https://lh3.googleusercontent.com/aida-public/AB6AXuBN7iGBQIIuIWcotnp4-4p1qZRieoLYcad3NxeHRO48lLIWlkHI90nw3Id7ItUg9r-Oo-ru9VLQD_QykeMXAfPwkWwzmQ28Q-Ul1or-W2XSbyUG7PjcPSgOvbiNyJBWWdmAENS7J5vPaSE0mmus9a7Fn84UhPsHWnQZSHD1kMbF6nKlNr38xnAYJV0mPz8z0Tm5JVfQwUZ2fWkXO0kKdD5NQt6N2Ib3loX_SiCV4yMI05Z8U4bawcNA"/>
</div>
</div>
<div class="flex flex-wrap gap-4 mt-8 pt-6 border-t border-outline-variant">
<button class="bg-primary text-white px-8 py-3 rounded-lg font-body-md font-bold shadow-lg shadow-primary/20 hover:bg-primary/90 transition-all active:scale-95 flex items-center gap-2">
<span class="material-symbols-outlined text-[20px]">assignment_turned_in</span>
                                        Buka Absensi
                                    </button>
<button class="bg-surface-container-low text-primary px-8 py-3 rounded-lg font-body-md font-bold hover:bg-surface-container-high transition-all active:scale-95 flex items-center gap-2 border border-primary/10">
<span class="material-symbols-outlined text-[20px]">visibility</span>
                                        Lihat Peserta
                                    </button>
</div>
</div>
</div>
</section>
<!-- Peserta Terbaru (Table Layout) -->
<section>
<div class="flex items-center justify-between mb-4">
<h4 class="font-title-lg text-title-lg text-on-surface">Peserta Terdaftar Terbaru</h4>
<button class="text-on-surface-variant hover:text-primary p-1 rounded-full transition-colors">
<span class="material-symbols-outlined">more_horiz</span>
</button>
</div>
<div class="bg-white rounded-xl border border-outline-variant card-shadow overflow-hidden">
<table class="w-full text-left border-collapse">
<thead>
<tr class="bg-surface-container-low border-b border-outline-variant">
<th class="px-6 py-4 text-label-sm font-bold text-secondary uppercase tracking-wider">Nama Siswa</th>
<th class="px-6 py-4 text-label-sm font-bold text-secondary uppercase tracking-wider">Kelas</th>
<th class="px-6 py-4 text-label-sm font-bold text-secondary uppercase tracking-wider">Kehadiran</th>
<th class="px-6 py-4 text-label-sm font-bold text-secondary uppercase tracking-wider text-right">Aksi</th>
</tr>
</thead>
<tbody class="divide-y divide-outline-variant">
<tr class="hover:bg-surface-container-low transition-colors group">
<td class="px-6 py-4">
<div class="flex items-center gap-3">
<div class="w-10 h-10 rounded-full bg-primary-fixed flex items-center justify-center font-bold text-on-primary-fixed">RF</div>
<div>
<p class="font-body-md font-bold text-on-surface">Rizky Febrian</p>
<p class="text-label-sm text-secondary">NIS: 20210451</p>
</div>
</div>
</td>
<td class="px-6 py-4 font-body-md text-on-surface-variant">XI IPA 3</td>
<td class="px-6 py-4">
<div class="flex items-center gap-2">
<div class="w-16 bg-surface-container rounded-full h-1.5 overflow-hidden">
<div class="bg-tertiary h-full" style="width: 98%"></div>
</div>
<span class="text-label-md font-bold text-tertiary">98%</span>
</div>
</td>
<td class="px-6 py-4 text-right">
<button class="text-primary p-2 hover:bg-primary-container/10 rounded-full transition-colors">
<span class="material-symbols-outlined">chevron_right</span>
</button>
</td>
</tr>
<tr class="hover:bg-surface-container-low transition-colors">
<td class="px-6 py-4">
<div class="flex items-center gap-3">
<div class="w-10 h-10 rounded-full bg-secondary-fixed flex items-center justify-center font-bold text-on-secondary-fixed">AM</div>
<div>
<p class="font-body-md font-bold text-on-surface">Amanda Manopo</p>
<p class="text-label-sm text-secondary">NIS: 20210452</p>
</div>
</div>
</td>
<td class="px-6 py-4 font-body-md text-on-surface-variant">X IPS 1</td>
<td class="px-6 py-4">
<div class="flex items-center gap-2">
<div class="w-16 bg-surface-container rounded-full h-1.5 overflow-hidden">
<div class="bg-tertiary h-full" style="width: 100%"></div>
</div>
<span class="text-label-md font-bold text-tertiary">100%</span>
</div>
</td>
<td class="px-6 py-4 text-right">
<button class="text-primary p-2 hover:bg-primary-container/10 rounded-full transition-colors">
<span class="material-symbols-outlined">chevron_right</span>
</button>
</td>
</tr>
<tr class="hover:bg-surface-container-low transition-colors">
<td class="px-6 py-4">
<div class="flex items-center gap-3">
<div class="w-10 h-10 rounded-full bg-tertiary-fixed flex items-center justify-center font-bold text-on-tertiary-fixed">DN</div>
<div>
<p class="font-body-md font-bold text-on-surface">Dian Nugraha</p>
<p class="text-label-sm text-secondary">NIS: 20210453</p>
</div>
</div>
</td>
<td class="px-6 py-4 font-body-md text-on-surface-variant">XII IPA 1</td>
<td class="px-6 py-4">
<div class="flex items-center gap-2">
<div class="w-16 bg-surface-container rounded-full h-1.5 overflow-hidden">
<div class="bg-tertiary h-full" style="width: 92%"></div>
</div>
<span class="text-label-md font-bold text-tertiary">92%</span>
</div>
</td>
<td class="px-6 py-4 text-right">
<button class="text-primary p-2 hover:bg-primary-container/10 rounded-full transition-colors">
<span class="material-symbols-outlined">chevron_right</span>
</button>
</td>
</tr>
</tbody>
</table>
<div class="p-4 text-center border-t border-outline-variant">
<button class="text-primary font-label-md font-bold hover:underline">Lihat Semua Peserta (124)</button>
</div>
</div>
</section>
</div>
<!-- Right Column: Quick Actions & Updates -->
<div class="space-y-8">
<!-- Aksi Cepat (Grid of interactive cards) -->
<section>
<h4 class="font-title-lg text-title-lg text-on-surface mb-4">Aksi Cepat</h4>
<div class="grid grid-cols-2 gap-4">
<button class="bg-white p-4 rounded-xl border border-outline-variant card-shadow flex flex-col items-center justify-center gap-3 text-center group hover:border-primary transition-all active:scale-95">
<div class="w-12 h-12 bg-primary-container text-on-primary-container rounded-full flex items-center justify-center group-hover:scale-110 transition-transform">
<span class="material-symbols-outlined">add_circle</span>
</div>
<span class="text-label-md font-bold text-on-surface">Buat Jadwal</span>
</button>
<button class="bg-white p-4 rounded-xl border border-outline-variant card-shadow flex flex-col items-center justify-center gap-3 text-center group hover:border-primary transition-all active:scale-95">
<div class="w-12 h-12 bg-tertiary-container text-on-tertiary-container rounded-full flex items-center justify-center group-hover:scale-110 transition-transform">
<span class="material-symbols-outlined">checklist</span>
</div>
<span class="text-label-md font-bold text-on-surface">Buka Absensi</span>
</button>
<button class="bg-white p-4 rounded-xl border border-outline-variant card-shadow flex flex-col items-center justify-center gap-3 text-center group hover:border-primary transition-all active:scale-95">
<div class="w-12 h-12 bg-surface-container-highest text-primary rounded-full flex items-center justify-center group-hover:scale-110 transition-transform">
<span class="material-symbols-outlined">edit_note</span>
</div>
<span class="text-label-md font-bold text-on-surface">Input Nilai</span>
</button>
<button class="bg-white p-4 rounded-xl border border-outline-variant card-shadow flex flex-col items-center justify-center gap-3 text-center group hover:border-primary transition-all active:scale-95">
<div class="w-12 h-12 bg-error-container text-on-error-container rounded-full flex items-center justify-center group-hover:scale-110 transition-transform">
<span class="material-symbols-outlined">notification_add</span>
</div>
<span class="text-label-md font-bold text-on-surface">Pengumuman</span>
</button>
</div>
</section>
<!-- Pengumuman & Berita -->
<section>
<h4 class="font-title-lg text-title-lg text-on-surface mb-4">Update Terbaru</h4>
<div class="bg-white rounded-xl border border-outline-variant card-shadow p-6 space-y-6">
<div class="space-y-4">
<div class="flex gap-4">
<div class="w-10 h-10 rounded-lg bg-surface-container flex-shrink-0 flex items-center justify-center">
<span class="material-symbols-outlined text-primary">info</span>
</div>
<div>
<p class="text-body-md font-bold text-on-surface">Lomba Antar Sekolah</p>
<p class="text-label-sm text-secondary line-clamp-2">Pendaftaran tim basket putra untuk turnamen bulan depan sudah dibuka...</p>
<p class="text-[10px] text-outline mt-1 font-bold">2 JAM YANG LALU</p>
</div>
</div>
<div class="flex gap-4">
<div class="w-10 h-10 rounded-lg bg-surface-container flex-shrink-0 flex items-center justify-center">
<span class="material-symbols-outlined text-primary">campaign</span>
</div>
<div>
<p class="text-body-md font-bold text-on-surface">Perubahan Jadwal</p>
<p class="text-label-sm text-secondary line-clamp-2">Sesi Jumat dialihkan ke GOR B karena ada perbaikan lampu...</p>
<p class="text-[10px] text-outline mt-1 font-bold">KEMARIN</p>
</div>
</div>
</div>
<button class="w-full py-2.5 rounded-lg border border-outline-variant text-label-md font-bold text-secondary hover:bg-surface-container-low transition-colors">
                                Lihat Semua Berita
                            </button>
</div>
</section>
<!-- Profile Quick View -->
<section>
<div class="bg-primary rounded-xl p-6 text-white relative overflow-hidden shadow-xl shadow-primary/30">
<div class="relative z-10">
<p class="text-label-sm font-bold opacity-80 uppercase">Status Akun</p>
<h5 class="text-title-lg font-bold mt-1">Pembina Senior</h5>
<div class="flex items-center gap-2 mt-4">
<span class="material-symbols-outlined text-[18px]">verified</span>
<p class="text-label-md font-semibold">Tervalidasi Kemdikbud</p>
</div>
</div>
<!-- Atmospheric Background Shape -->
<div class="absolute -bottom-6 -right-6 w-32 h-32 bg-white opacity-10 rounded-full"></div>
<div class="absolute -top-6 -right-2 w-20 h-20 bg-white opacity-5 rounded-full"></div>
</div>
</section>
</div>
</div>
</x-app-layout>
