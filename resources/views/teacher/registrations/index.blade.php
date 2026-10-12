<x-app-layout>
    <x-slot name="title">Pendaftaran Siswa</x-slot>
    <div class="space-y-8">
        <!-- Header -->
        <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
            <div>
                <p class="text-body-md text-secondary">Daftar siswa yang menunggu persetujuan Anda</p>
                <h3 class="mt-1 text-headline-md font-bold text-on-surface md:text-headline-lg">Pendaftaran Siswa</h3>
            </div>

            <form action="{{ route('teacher.registrations.index') }}" method="GET" class="flex flex-col gap-2 sm:flex-row sm:flex-wrap sm:items-center">
                <select name="ekskul_id" class="w-full rounded-lg border border-outline-variant bg-surface px-3 py-2.5 text-body-md text-on-surface focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20 sm:w-auto sm:min-w-[200px]">
                    <option value="">Semua Ekstrakurikuler</option>
                    @foreach($ekskuls as $ek)
                        <option value="{{ $ek->id }}" {{ request('ekskul_id') == $ek->id ? 'selected' : '' }}>
                            {{ $ek->name }}
                        </option>
                    @endforeach
                </select>
                <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-lg bg-primary-container px-4 py-2.5 text-label-lg text-on-primary-container transition-opacity hover:opacity-90 sm:w-auto">
                    <x-icon name="list-filter" class="size-[18px]" /> Terapkan Filter
                </button>
            </form>
        </div>

        <!-- Statistik -->
        <div class="grid grid-cols-2 gap-4 lg:grid-cols-3">
            <div class="flex flex-col justify-between gap-8 rounded-xl bg-primary-container p-5 text-on-primary-container">
                <div class="flex items-center justify-between gap-3">
                    <span class="text-label-md opacity-90">Total siswa</span>
                    <span class="flex size-9 items-center justify-center rounded-lg bg-on-primary-container/15">
                        <x-icon name="users" class="size-[18px]" />
                    </span>
                </div>
                <p class="text-headline-md font-bold">{{ $totalSiswa }}</p>
            </div>

            <div class="flex flex-col justify-between gap-8 rounded-xl border border-outline-variant bg-surface p-5">
                <div class="flex items-center justify-between gap-3">
                    <span class="text-label-md text-secondary">Baru bulan ini</span>
                    <span class="flex size-9 items-center justify-center rounded-lg bg-tertiary-container text-on-tertiary-container">
                        <x-icon name="user-plus" class="size-[18px]" />
                    </span>
                </div>
                <p class="text-headline-md font-bold text-on-surface">{{ $siswaBaruBulanIni }}</p>
            </div>

            <div class="col-span-2 flex flex-col justify-between gap-8 rounded-xl border border-outline-variant bg-surface p-5 lg:col-span-1">
                <div class="flex items-center justify-between gap-3">
                    <span class="text-label-md text-secondary">Jumlah ekskul</span>
                    <span class="flex size-9 items-center justify-center rounded-lg bg-primary-fixed text-on-primary-fixed">
                        <x-icon name="shapes" class="size-[18px]" />
                    </span>
                </div>
                <p class="text-headline-md font-bold text-on-surface">{{ $totalEkskul }}</p>
            </div>
        </div>

        <!-- Daftar pendaftar (baris kartu) -->
        <section class="overflow-hidden rounded-xl border border-outline-variant bg-surface">
            <div class="flex items-center justify-between gap-3 border-b border-outline-variant px-5 py-4">
                <h4 class="text-title-md font-bold text-on-surface">Menunggu Persetujuan</h4>
            </div>
            <ul class="divide-y divide-outline-variant">
                @forelse($participants as $part)
                @php
                    $gender = $part->student->studentProfile->gender ?? '-';
                    $genderLabel = $gender === 'male' ? 'L' : ($gender === 'female' ? 'P' : '-');
                @endphp
                <li class="flex items-center gap-4 px-5 py-4">
                    <div class="flex size-10 shrink-0 items-center justify-center rounded-full bg-warning-container text-label-md font-bold text-on-warning-container">
                        {{ strtoupper(substr($part->student->name ?? 'A', 0, 1)) }}
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="truncate text-body-md font-semibold text-on-surface">{{ $part->student->name ?? '-' }}</p>
                        <p class="truncate text-body-xs text-secondary">{{ $part->student->studentProfile->class_name ?? '-' }} &middot; {{ $part->extracurricular->name ?? '-' }} &middot; {{ $genderLabel }}</p>
                        <p class="mt-0.5 truncate text-body-xs text-secondary">Mendaftar {{ $part->created_at->format('d M Y') }}</p>
                    </div>
                    <div class="flex shrink-0 items-center gap-2">
                        @if($part->status === 'approved')
                            <span class="rounded-full bg-tertiary-container px-2.5 py-0.5 text-label-xs font-bold text-on-tertiary-container">Aktif</span>
                        @elseif($part->status === 'pending')
                            <span class="rounded-full bg-warning-container px-2.5 py-0.5 text-label-xs font-bold text-on-warning-container">Pending</span>
                        @else
                            <span class="rounded-full bg-error-container px-2.5 py-0.5 text-label-xs font-bold text-on-error-container">Ditolak</span>
                        @endif

                        @if($part->status === 'pending')
                            <form action="{{ route('teacher.registrations.update', $part->id) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="approved">
                                <button type="submit" class="flex size-9 items-center justify-center rounded-full bg-tertiary-container text-on-tertiary-container transition-opacity hover:opacity-90" title="Terima Siswa" aria-label="Terima Siswa">
                                    <x-icon name="check" class="size-[18px]" />
                                </button>
                            </form>
                            <form action="{{ route('teacher.registrations.update', $part->id) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="rejected">
                                <button type="submit" class="flex size-9 items-center justify-center rounded-full bg-error-container text-on-error-container transition-opacity hover:opacity-90" title="Tolak Siswa" aria-label="Tolak Siswa">
                                    <x-icon name="x" class="size-[18px]" />
                                </button>
                            </form>
                        @endif
                        <button type="button"
                            onclick="openDetailModal(
                                '{{ $part->id }}',
                                '{{ addslashes($part->student->name ?? '-') }}',
                                '{{ addslashes($part->student->studentProfile->class_name ?? '-') }}',
                                '{{ addslashes($part->student->email ?? '-') }}',
                                '{{ addslashes($part->student->phone ?? '-') }}',
                                '{{ $genderLabel }}',
                                '{{ addslashes($part->extracurricular->name ?? '-') }}',
                                '{{ $part->status }}',
                                '0 / 0'
                            )"
                            class="flex size-9 items-center justify-center rounded-full bg-primary-fixed text-on-primary-fixed transition-opacity hover:opacity-90" title="Detail Siswa" aria-label="Detail Siswa">
                            <x-icon name="square-pen" class="size-[18px]" />
                        </button>
                    </div>
                </li>
                @empty
                <li class="px-5 py-8 text-center text-body-md text-on-surface-variant">Belum ada peserta</li>
                @endforelse
            </ul>
        </section>
    </div>

    <!-- Modal Detail Siswa -->
    <div id="detailModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-on-surface/40 p-4 opacity-0 transition-opacity duration-300">
        <div class="relative w-80 max-w-full transform overflow-hidden rounded-xl border border-outline-variant bg-surface shadow-lg transition-transform duration-300 scale-95">
            <!-- Tombol tutup -->
            <button type="button" onclick="closeDetailModal()" aria-label="Tutup" class="absolute right-3 top-3 z-10 flex size-9 items-center justify-center rounded-full bg-surface-container-low text-on-surface-variant transition-colors hover:bg-error-container hover:text-on-error-container">
                <x-icon name="x" class="size-[18px]" />
            </button>

            <!-- Header profil -->
            <div class="flex flex-col items-center border-b border-outline-variant bg-surface-container-low px-5 pb-5 pt-6 text-center">
                <div class="mb-3 flex size-14 items-center justify-center rounded-full bg-primary-fixed text-title-lg font-bold text-on-primary-fixed" id="modalInitial">-</div>
                <h4 class="text-title-md leading-tight text-on-surface" id="modalName">-</h4>
                <p class="mt-0.5 text-label-md text-on-surface-variant" id="modalClass">-</p>
                <div id="modalStatus" class="mt-2 rounded-full bg-warning-container px-2.5 py-0.5 text-label-xs uppercase text-on-warning-container">PENDING</div>
            </div>

            <!-- Daftar detail -->
            <div class="space-y-4 bg-surface p-5">
                <div class="flex items-center gap-3">
                    <div class="flex size-8 shrink-0 items-center justify-center rounded-lg bg-surface-container-low text-on-surface-variant">
                        <x-icon name="mail" class="size-[16px]" />
                    </div>
                    <div class="min-w-0">
                        <p class="text-label-xs uppercase tracking-wider text-secondary">Email</p>
                        <p class="max-w-[200px] truncate text-body-sm font-medium text-on-surface" id="modalEmail">-</p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <div class="flex size-8 shrink-0 items-center justify-center rounded-lg bg-surface-container-low text-on-surface-variant">
                        <x-icon name="phone" class="size-[16px]" />
                    </div>
                    <div class="min-w-0">
                        <p class="text-label-xs uppercase tracking-wider text-secondary">WhatsApp</p>
                        <p class="text-body-sm font-medium text-on-surface" id="modalPhone">-</p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <div class="flex size-8 shrink-0 items-center justify-center rounded-lg bg-surface-container-low text-on-surface-variant">
                        <x-icon name="venus-and-mars" class="size-[16px]" />
                    </div>
                    <div class="min-w-0">
                        <p class="text-label-xs uppercase tracking-wider text-secondary">Jenis Kelamin</p>
                        <p class="text-body-sm font-medium text-on-surface" id="modalGender">-</p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <div class="flex size-8 shrink-0 items-center justify-center rounded-lg bg-surface-container-low text-on-surface-variant">
                        <x-icon name="trophy" class="size-[16px]" />
                    </div>
                    <div class="flex min-w-0 flex-1 items-center justify-between gap-3">
                        <div class="min-w-0">
                            <p class="text-label-xs uppercase tracking-wider text-secondary">Ekskul</p>
                            <p class="text-body-sm font-medium text-on-surface" id="modalEkskul">-</p>
                        </div>
                        <div class="shrink-0 text-right">
                            <p class="text-label-xs uppercase tracking-wider text-secondary">Absensi</p>
                            <p class="mt-0.5 text-title-sm font-bold text-primary-container" id="modalAbsen">-</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Aksi -->
            <div class="border-t border-outline-variant bg-surface-container-lowest p-4">
                <form id="kickForm" method="POST" class="w-full">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="status" value="rejected">
                    <button type="submit" class="flex w-full items-center justify-center gap-2 rounded-lg bg-error px-4 py-2.5 text-label-lg text-on-error transition-opacity hover:opacity-90">
                        <x-icon name="user-minus" class="size-[16px]" /> Tolak Pendaftaran
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openDetailModal(id, name, className, email, phone, gender, ekskul, status, absen) {
            document.getElementById('modalName').textContent = name;
            document.getElementById('modalInitial').textContent = name.charAt(0).toUpperCase();
            document.getElementById('modalClass').textContent = 'Kelas ' + className;
            document.getElementById('modalEmail').textContent = email;
            document.getElementById('modalPhone').textContent = phone;
            document.getElementById('modalGender').textContent = gender === 'L' ? 'Laki-Laki' : (gender === 'P' ? 'Perempuan' : '-');
            document.getElementById('modalEkskul').textContent = ekskul;
            document.getElementById('modalAbsen').textContent = absen;

            const statusEl = document.getElementById('modalStatus');
            if(status === 'approved') {
                statusEl.textContent = 'AKTIF';
                statusEl.className = 'mt-2 rounded-full px-2.5 py-0.5 text-label-xs uppercase bg-tertiary-container text-on-tertiary-container';
            } else if(status === 'pending') {
                statusEl.textContent = 'PENDING';
                statusEl.className = 'mt-2 rounded-full px-2.5 py-0.5 text-label-xs uppercase bg-warning-container text-on-warning-container';
            } else {
                statusEl.textContent = 'DITOLAK';
                statusEl.className = 'mt-2 rounded-full px-2.5 py-0.5 text-label-xs uppercase bg-error-container text-on-error-container';
            }

            const kickForm = document.getElementById('kickForm');
            if(status === 'rejected') {
                kickForm.style.display = 'none';
            } else {
                kickForm.style.display = 'inline-block';
                // Set the action URL correctly
                const baseUrl = "{{ route('teacher.registrations.update', ':id') }}";
                kickForm.action = baseUrl.replace(':id', id);
            }

            const modal = document.getElementById('detailModal');
            const modalInner = modal.querySelector('div');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            // Trigger reflow
            void modal.offsetWidth;
            modal.classList.remove('opacity-0');
            modalInner.classList.remove('scale-95');
        }

        function closeDetailModal() {
            const modal = document.getElementById('detailModal');
            const modalInner = modal.querySelector('div');
            modal.classList.add('opacity-0');
            modalInner.classList.add('scale-95');
            setTimeout(() => {
                modal.classList.remove('flex');
                modal.classList.add('hidden');
            }, 300);
        }
    </script>
</x-app-layout>
