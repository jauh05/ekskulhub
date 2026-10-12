<aside class="fixed inset-y-0 left-0 z-30 hidden w-64 flex-col border-r border-outline-variant bg-surface transition-transform duration-300 md:flex"
       :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
    <div class="flex h-16 items-center gap-3 border-b border-outline-variant px-5">
        <span class="flex size-9 items-center justify-center rounded-lg bg-primary text-on-primary">
            <x-icon name="graduation-cap" class="size-[20px]" />
        </span>
        <div class="leading-tight">
            <p class="text-title-md font-bold text-on-surface">Ekskul</p>
            <p class="text-label-sm text-secondary">{{ $roleName }}</p>
        </div>
    </div>

    <nav class="flex-1 space-y-1 overflow-y-auto p-3">
        @foreach ($navItems as $item)
            @php $active = request()->routeIs($item['match']); @endphp
            <a href="{{ route($item['route']) }}"
               @if ($active) aria-current="page" @endif
               class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-body-md transition-colors {{ $active ? 'bg-primary-fixed font-semibold text-on-primary-fixed' : 'text-on-surface-variant hover:bg-surface-container hover:text-on-surface' }}">
                <x-icon :name="$item['icon']" class="size-[20px]" />
                <span>{{ $item['label'] }}</span>
            </a>
        @endforeach
    </nav>

    <div class="border-t border-outline-variant p-3">
        <x-logout-button class="flex w-full items-center gap-3 rounded-lg px-3 py-2.5 text-body-md text-error transition-colors hover:bg-error-container/50" iconClass="size-[20px]" :showText="true" />
    </div>
</aside>
