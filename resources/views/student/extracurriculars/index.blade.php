<x-student-layout>
<div class="flex w-full flex-col gap-6">
    <x-student-page-header title="Ekskul" subtitle="Gabung dan pantau kegiatanmu" />

    @if($errors->any())
        <div class="rounded-2xl bg-error-container px-4 py-3 text-body-md font-semibold text-on-error-container">
            {{ $errors->first() }}
        </div>
    @endif

    <!-- Gabung dengan kode kelas -->
    <section class="rounded-2xl bg-dark p-5 text-surface">
        <p class="text-title-sm font-bold">Punya kode kelas?</p>
        <p class="mt-1 text-body-sm text-surface/70">Masukkan kode dari pembina ekstrakurikuler untuk bergabung.</p>
        <form action="{{ route('student.extracurriculars.store') }}" method="POST" class="mt-4 flex flex-col gap-3 sm:flex-row">
            @csrf
            <label for="class_code" class="sr-only">Kode kelas</label>
            <input id="class_code" type="text" name="class_code" placeholder="Kode kelas" required
                class="min-w-0 flex-1 rounded-xl border border-surface/20 bg-surface/10 px-4 py-3 font-mono text-body-md uppercase tracking-widest text-surface placeholder:normal-case placeholder:tracking-normal placeholder:text-surface/50 focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/40">
            <button type="submit"
                class="inline-flex shrink-0 items-center justify-center gap-2 rounded-xl bg-primary px-5 py-3 text-label-lg text-on-primary transition-opacity hover:opacity-90">
                <x-icon name="user-plus" class="size-[18px]" /> Gabung
            </button>
        </form>
    </section>

    <!-- Daftar ekskul -->
    <section class="flex flex-col gap-3">
        <div class="flex items-center justify-between gap-3">
            <h3 class="text-title-md font-bold text-on-surface">Ekskul saya</h3>
            <span class="shrink-0 text-label-sm text-secondary">{{ $extracurriculars->count() }} ekskul</span>
        </div>

        @forelse($extracurriculars as $ekskul)
        @php
            $statusDaftar = $myRegistrations[$ekskul->id] ?? null;
        @endphp
        <article class="flex items-center gap-4 rounded-2xl border border-outline-variant bg-surface p-3 pr-4">
            <span class="flex size-14 shrink-0 items-center justify-center rounded-xl bg-primary-fixed text-on-primary-fixed">
                <x-icon name="trophy" class="size-[22px]" />
            </span>
            <div class="min-w-0 flex-1">
                <div class="flex items-center gap-2">
                    <h4 class="min-w-0 truncate text-title-sm font-bold text-on-surface">{{ $ekskul->name }}</h4>
                    @if($statusDaftar == 'approved')
                        <span class="inline-flex shrink-0 items-center gap-1 rounded-full bg-tertiary-container px-2 py-0.5 text-label-xs font-bold text-on-tertiary-container">
                            <x-icon name="circle-check" class="size-[12px]" /> Bergabung
                        </span>
                    @elseif($statusDaftar == 'rejected')
                        <span class="inline-flex shrink-0 items-center gap-1 rounded-full bg-error-container px-2 py-0.5 text-label-xs font-bold text-on-error-container">
                            <x-icon name="circle-x" class="size-[12px]" /> Ditolak
                        </span>
                    @elseif($statusDaftar)
                        <span class="inline-flex shrink-0 items-center gap-1 rounded-full bg-warning-container px-2 py-0.5 text-label-xs font-bold text-on-warning-container">
                            <x-icon name="clock" class="size-[12px]" /> Menunggu
                        </span>
                    @endif
                </div>
                <p class="mt-1 line-clamp-2 text-body-sm text-secondary">{{ $ekskul->description ?? 'Tidak ada deskripsi' }}</p>
                <p class="mt-2 flex items-center gap-1 text-body-xs text-on-surface-variant">
                    <x-icon name="calendar-days" class="size-[14px] shrink-0 text-primary-container" />
                    <span class="truncate">{{ $ekskul->regular_day ?: 'Jadwal rutin belum diatur' }}</span>
                </p>
            </div>
        </article>
        @empty
        <div class="rounded-2xl border border-dashed border-outline-variant bg-surface p-6">
            <p class="text-title-sm font-bold text-on-surface">Belum ada ekstrakurikuler</p>
            <p class="mt-1 text-body-sm text-secondary">Ekskul yang sudah kamu gabungi akan tampil di sini.</p>
        </div>
        @endforelse
    </section>
</div>
</x-student-layout>
