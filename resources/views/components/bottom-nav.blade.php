{{-- Navbar bawah guru dan admin: 4 tab utama, sisanya lewat tombol "Lainnya" (tanpa JavaScript). --}}
@php
    $utama = array_slice($navItems, 0, 4);
    $lainnya = array_slice($navItems, 4);
    $lainnyaAktif = collect($lainnya)->contains(fn ($item) => request()->routeIs($item['match']));
@endphp

<nav aria-label="Navigasi utama" class="fixed inset-x-0 bottom-0 z-50 flex items-stretch border-t border-outline-variant bg-surface px-1 pb-[env(safe-area-inset-bottom)] md:hidden">
    @foreach ($utama as $item)
        @php $active = request()->routeIs($item['match']); @endphp
        <a href="{{ route($item['route']) }}" @if ($active) aria-current="page" @endif
           class="flex min-w-0 flex-1 flex-col items-center justify-center gap-1 py-2.5 text-label-xs transition-colors {{ $active ? 'font-bold text-primary-container' : 'text-secondary' }}">
            <x-icon :name="$item['icon']" class="size-[22px]" />
            <span class="w-full truncate text-center">{{ $item['short'] }}</span>
        </a>
    @endforeach

    <details class="nav-lainnya relative flex min-w-0 flex-1">
        <summary class="flex w-full cursor-pointer list-none flex-col items-center justify-center gap-1 py-2.5 text-label-xs transition-colors {{ $lainnyaAktif ? 'font-bold text-primary-container' : 'text-secondary' }}">
            <x-icon name="ellipsis" class="size-[22px]" />
            <span class="w-full truncate text-center">Lainnya</span>
        </summary>

        <div class="nav-lainnya-panel absolute bottom-full right-0 z-50 mb-2 w-60 rounded-2xl border border-outline-variant bg-surface p-2 shadow-lg">
            @foreach ($lainnya as $item)
                @php $active = request()->routeIs($item['match']); @endphp
                <a href="{{ route($item['route']) }}" @if ($active) aria-current="page" @endif
                   class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-body-md transition-colors {{ $active ? 'bg-primary-fixed font-semibold text-on-primary-fixed' : 'text-on-surface-variant hover:bg-surface-container' }}">
                    <x-icon :name="$item['icon']" class="size-[18px]" />
                    <span class="truncate">{{ $item['label'] }}</span>
                </a>
            @endforeach

            <form method="POST" action="{{ route('logout') }}" class="mt-1 border-t border-outline-variant pt-1">
                @csrf
                <button type="submit" onclick="confirmAction(event, 'Apakah Anda yakin ingin keluar?', this.closest('form'))"
                        class="flex w-full items-center gap-3 rounded-xl px-3 py-2.5 text-body-md text-error transition-colors hover:bg-error-container/50">
                    <x-icon name="log-out" class="size-[18px]" />
                    <span>Keluar</span>
                </button>
            </form>
        </div>
    </details>
</nav>
