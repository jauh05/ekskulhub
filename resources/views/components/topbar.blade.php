<header class="fixed right-0 top-0 z-20 {{ $role === 'student' ? 'hidden md:flex' : 'flex' }} h-16 items-center justify-between gap-4 border-b border-outline-variant bg-surface px-4 transition-all duration-300 md:px-8"
        :style="sidebarOpen && window.innerWidth >= 768 ? 'width: calc(100% - 16rem);' : 'width: 100%;'">
    <div class="flex min-w-0 items-center gap-3">
        <button type="button" @click="sidebarOpen = !sidebarOpen" aria-label="Tampilkan atau sembunyikan menu"
                class="hidden size-10 items-center justify-center rounded-lg text-on-surface-variant transition-colors hover:bg-surface-container md:flex">
            <x-icon name="menu" class="size-[20px]" />
        </button>

        <span class="flex size-8 items-center justify-center rounded-lg bg-primary text-on-primary md:hidden">
            <x-icon name="graduation-cap" class="size-[18px]" />
        </span>

        <h2 class="hidden shrink-0 text-title-md font-semibold text-on-surface sm:block">{{ $roleTitle }}</h2>

        <div class="relative hidden w-full max-w-xs xl:block">
            <x-icon name="search" class="absolute left-3 top-1/2 size-[18px] -translate-y-1/2 text-outline" />
            <input type="text" placeholder="Cari data..."
                   class="w-full rounded-lg border border-outline-variant bg-surface-container-low py-2 pl-10 pr-3 text-body-sm placeholder:text-outline focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
        </div>
    </div>

    <div class="flex items-center gap-2">
        <button type="button" aria-label="Notifikasi"
                class="flex size-9 items-center justify-center rounded-lg text-on-surface-variant transition-colors hover:bg-surface-container">
            <x-icon name="bell" class="size-[20px]" />
        </button>
        <button type="button" aria-label="Pengaturan"
                class="hidden size-9 items-center justify-center rounded-lg text-on-surface-variant transition-colors hover:bg-surface-container sm:flex">
            <x-icon name="settings" class="size-[20px]" />
        </button>
        <div class="flex items-center gap-3 border-l border-outline-variant pl-3">
            <div class="hidden text-right sm:block">
                <p class="text-label-md font-semibold text-on-surface">{{ Auth::user()->name }}</p>
                <p class="text-label-sm text-secondary">{{ $roleName }}</p>
            </div>
            <div class="flex size-9 items-center justify-center rounded-full bg-primary-fixed text-title-sm font-bold text-on-primary-fixed">
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
            </div>
        </div>
    </div>
</header>
