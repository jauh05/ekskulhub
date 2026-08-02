@props(['class' => 'w-full flex items-center justify-center gap-2 text-error font-label-md bg-error-container/20 hover:bg-error-container/40 py-3 rounded-full transition-colors', 'iconClass' => 'text-[20px]', 'showText' => true])

<div x-data="{ showLogoutModal: false }" class="w-full">
    <button @click="showLogoutModal = true" class="{{ $class }}">
        <span class="material-symbols-outlined {{ $iconClass }}">logout</span>
        @if($showText)
            <span>Keluar</span>
        @endif
    </button>

    <!-- Modal Verifikasi Logout -->
    <div x-show="showLogoutModal" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center bg-black/40 backdrop-blur-sm" style="display: none;">
        <div @click.away="showLogoutModal = false" class="bg-surface rounded-xl p-6 max-w-sm w-[90%] mx-4 soft-shadow shadow-xl transform transition-all text-left" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95">
            <h3 class="font-title-lg text-on-surface mb-2">Konfirmasi Keluar</h3>
            <p class="font-body-md text-on-surface-variant mb-6 text-left whitespace-normal">Apakah Anda yakin ingin keluar dari sistem?</p>
            <div class="flex justify-end gap-3">
                <button @click="showLogoutModal = false" class="px-4 py-2 font-label-md text-on-surface-variant hover:bg-surface-variant rounded-lg transition-colors">Batal</button>
                <form method="POST" action="{{ route('logout') }}" class="m-0">
                    @csrf
                    <button type="submit" class="px-4 py-2 font-label-md bg-error text-on-error hover:bg-error/90 rounded-lg transition-colors">Ya, Keluar</button>
                </form>
            </div>
        </div>
    </div>
</div>
