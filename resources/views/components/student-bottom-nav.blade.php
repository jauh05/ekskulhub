{{-- Navbar mengambang untuk siswa. Lebarnya mengikuti layar (maks 26rem), ikon dan label ikut menyesuaikan. --}}
<nav aria-label="Navigasi utama" class="fixed bottom-4 left-4 right-4 z-50 mx-auto flex max-w-[26rem] items-center justify-between gap-1 rounded-full bg-dark px-2 py-2 shadow-lg md:hidden">
    @foreach ($navItems as $item)
        @php $active = request()->routeIs($item['match']); @endphp
        <a href="{{ route($item['route']) }}" @if ($active) aria-current="page" @endif
           class="flex min-w-0 flex-1 flex-col items-center gap-0.5 rounded-full py-0.5 transition-colors">
            <span class="flex size-9 items-center justify-center rounded-full transition-colors min-[400px]:size-10 {{ $active ? 'bg-primary text-on-primary' : 'text-surface/70' }}">
                <x-icon :name="$item['icon']" class="size-[18px] min-[400px]:size-[20px]" />
            </span>
            <span class="w-full truncate text-center text-label-xs {{ $active ? 'font-semibold text-surface' : 'text-surface/70' }}">{{ $item['short'] }}</span>
        </a>
    @endforeach
</nav>
