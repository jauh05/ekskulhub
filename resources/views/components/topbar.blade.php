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
<img class="w-9 h-9 rounded-full object-cover border border-outline-variant" data-alt="A professional studio portrait of a friendly Southeast Asian male teacher in his late 30s wearing a clean school uniform. He has a warm smile and is set against a soft, out-of-focus background of a bright modern school hallway. The lighting is natural and high-key, matching a clean minimalist UI aesthetic." src="https://lh3.googleusercontent.com/aida-public/AB6AXuB4KUDmRrj_rqSADbSacfXcch0T1hHv-rN0tgyRbUbsYH66EuFDldcT3h-1OKfaJH_JAQRlqxBIqFOLQmZ0bCc1mD9Htjgf7I0uaDHNtjROqLS29EXnfv5GCeqAHVurIw8E2gWNyQMLRqJxG0OPLG456y2Ka8mjw8JbRFcK7AHOZ5iNILcE0WwxPqBCveANhHqbPgXcsi-3WkplLEfKcVeEwM-OWPVxG2mJsowVXa1OJ_BaL3lFtgys"/>
</div>
</div>
</header>