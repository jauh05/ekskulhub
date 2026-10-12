@props(['class' => 'flex w-full items-center justify-center gap-2 rounded-lg py-2.5 text-body-md text-error transition-colors hover:bg-error-container/50', 'iconClass' => 'size-[20px]', 'showText' => true])

<div x-data="{ showLogoutModal: false }" class="w-full">
    <button type="button" @click="showLogoutModal = true" class="{{ $class }}">
        <x-icon name="log-out" class="{{ $iconClass }}" />
        @if($showText)
            <span>Keluar</span>
        @endif
    </button>

    <!-- Modal Verifikasi Logout. Di-teleport ke body karena sidebar memakai transform, yang memotong position: fixed. -->
    <template x-teleport="body">
    <div x-show="showLogoutModal" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center bg-on-surface/40 p-4" style="display: none;">
        <div @click.away="showLogoutModal = false" class="w-full max-w-sm rounded-xl border border-outline-variant bg-surface p-6 shadow-lg text-left"
             x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95">
            <h3 class="text-title-lg text-on-surface">Konfirmasi Keluar</h3>
            <p class="mt-1 text-body-md text-on-surface-variant">Apakah Anda yakin ingin keluar dari sistem?</p>
            <div class="mt-6 flex justify-end gap-2">
                <button type="button" @click="showLogoutModal = false" class="rounded-lg px-4 py-2 text-label-lg text-on-surface-variant transition-colors hover:bg-surface-container">Batal</button>
                <form method="POST" action="{{ route('logout') }}" class="m-0">
                    @csrf
                    <button type="submit" class="rounded-lg bg-error px-4 py-2 text-label-lg text-on-error transition-opacity hover:opacity-90">Ya, Keluar</button>
                </form>
            </div>
        </div>
    </div>
    </template>
</div>
