<header class="fixed top-0 right-0 w-[calc(100%-16rem)] z-10 glass-nav border-b border-outline-variant flex justify-between items-center px-8 h-16">
<div class="flex items-center gap-4">
<h2 class="font-title-lg text-title-lg text-primary">Dashboard Guru</h2>
<div class="h-6 w-px bg-outline-variant mx-2"></div>
<div class="relative">
<span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline">search</span>
<input class="pl-10 pr-4 py-1.5 bg-surface-container-low border border-outline-variant rounded-full text-body-md focus:outline-none focus:ring-2 focus:ring-primary/20 w-64 transition-all" placeholder="Cari data siswa..." type="text"/>
</div>
</div>
<div class="flex items-center gap-4">
<button class="w-10 h-10 flex items-center justify-center text-on-surface-variant hover:bg-surface-container-high rounded-full transition-colors relative">
<span class="material-symbols-outlined">notifications</span>
<span class="absolute top-2 right-2.5 w-2 h-2 bg-error rounded-full border-2 border-white"></span>
</button>
<button class="w-10 h-10 flex items-center justify-center text-on-surface-variant hover:bg-surface-container-high rounded-full transition-colors">
<span class="material-symbols-outlined">settings</span>
</button>
<div class="flex items-center gap-3 pl-4 border-l border-outline-variant">
<div class="text-right">
<p class="text-label-md font-bold text-on-surface leading-tight">{{ Auth::user()->name }}</p>
<p class="text-label-sm text-secondary leading-tight">{{ Auth::user()->role }}</p>
</div>
<div class="w-9 h-9 rounded-full bg-primary text-white flex items-center justify-center font-bold font-title-sm border border-primary/20">
{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
</div>
</div>
</div>
</header>