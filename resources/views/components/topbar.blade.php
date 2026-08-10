<header class="sticky top-0 z-10 glass-nav border-b border-outline-variant flex justify-between items-center px-4 md:px-8 h-16 transition-all duration-300 w-full">
<div class="flex items-center gap-2 md:gap-4">
<button @click="sidebarOpen = !sidebarOpen" class="md:hidden w-10 h-10 flex items-center justify-center text-on-surface-variant hover:bg-surface-container-high rounded-full transition-colors">
<span class="material-symbols-outlined">menu</span>
</button>
<h2 class="font-title-md md:font-title-lg text-title-md md:text-title-lg text-primary hidden sm:block">
@if(Auth::user()->role === 'admin')
Dashboard Admin
@elseif(Auth::user()->role === 'teacher')
Dashboard Guru
@else
Dashboard
@endif
</h2>
<div class="h-6 w-px bg-outline-variant mx-1 md:mx-2 hidden sm:block"></div>
<div class="relative w-full max-w-[200px] md:max-w-none">
<span class="material-symbols-outlined absolute left-2 md:left-3 top-1/2 -translate-y-1/2 text-outline text-[20px] md:text-[24px]">search</span>
<input class="pl-8 md:pl-10 pr-3 md:pr-4 py-1.5 bg-surface-container-low border border-outline-variant rounded-full text-body-sm md:text-body-md focus:outline-none focus:ring-2 focus:ring-primary/20 w-full md:w-64 transition-all" placeholder="Cari data..." type="text"/>
</div>
</div>
<div class="flex items-center gap-2 md:gap-4">
<button class="w-8 h-8 md:w-10 md:h-10 flex items-center justify-center text-on-surface-variant hover:bg-surface-container-high rounded-full transition-colors relative">
<span class="material-symbols-outlined text-[20px] md:text-[24px]">notifications</span>
<span class="absolute top-1.5 md:top-2 right-1.5 md:right-2.5 w-2 h-2 bg-error rounded-full border-2 border-white"></span>
</button>
<button class="w-8 h-8 md:w-10 md:h-10 flex items-center justify-center text-on-surface-variant hover:bg-surface-container-high rounded-full transition-colors hidden sm:flex">
<span class="material-symbols-outlined text-[20px] md:text-[24px]">settings</span>
</button>
<div class="flex items-center gap-2 md:gap-3 pl-2 md:pl-4 border-l border-outline-variant">
<div class="text-right hidden sm:block">
<p class="text-label-md font-bold text-on-surface leading-tight">{{ Auth::user()->name }}</p>
<p class="text-label-sm text-secondary leading-tight">{{ Auth::user()->role }}</p>
</div>
<div class="w-8 h-8 md:w-9 md:h-9 rounded-full bg-primary text-white flex items-center justify-center font-bold font-title-sm border border-primary/20">
{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
</div>
</div>
</div>
</header>