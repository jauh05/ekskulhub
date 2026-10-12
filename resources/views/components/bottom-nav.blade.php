<nav class="hide-scrollbar fixed inset-x-0 bottom-0 z-50 flex items-stretch overflow-x-auto border-t border-outline-variant bg-surface px-1 pb-[env(safe-area-inset-bottom)] md:hidden">
    @foreach ($navItems as $item)
        @php $active = request()->routeIs($item['match']); @endphp
        <a href="{{ route($item['route']) }}" @if ($active) aria-current="page" @endif
           class="flex min-w-[68px] flex-1 flex-col items-center justify-center gap-1 py-2.5 text-label-xs transition-colors {{ $active ? 'text-primary-container' : 'text-secondary' }}">
            <x-icon :name="$item['icon']" class="size-[22px]" />
            <span>{{ $item['short'] }}</span>
        </a>
    @endforeach

    <form method="POST" action="{{ route('logout') }}" class="flex min-w-[68px] flex-1">
        @csrf
        <button type="submit" onclick="confirmAction(event, 'Apakah Anda yakin ingin keluar?', this.closest('form'))"
                class="flex w-full flex-col items-center justify-center gap-1 py-2.5 text-label-xs text-error transition-colors">
            <x-icon name="log-out" class="size-[22px]" />
            <span>Keluar</span>
        </button>
    </form>
</nav>
