<aside class="fixed left-0 top-0 h-screen w-64 border-r border-neutral-800/30 bg-neutral-950 flex flex-col h-full py-8 gap-4 font-['Space_Grotesk'] font-medium z-50">
    <div class="px-6 mb-8">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-primary to-secondary flex items-center justify-center">
                <span class="material-symbols-outlined text-on-secondary" style="font-variation-settings: 'FILL' 1;">bolt</span>
            </div>
            <div>
                <h2 class="text-xl font-black text-white leading-none">Neon Kinetic</h2>
                <p class="text-[10px] text-neutral-500 tracking-[0.2em] uppercase mt-1">Admin Terminal</p>
            </div>
        </div>
    </div>
    
    <nav class="flex-1 flex flex-col gap-1 px-3">
        <a class="flex items-center gap-4 px-4 py-3 {{ request()->routeIs('admin.dashboard') ? 'text-orange-500 font-bold border-r-2 border-orange-500 bg-neutral-900/30' : 'text-neutral-500 hover:bg-neutral-900/50 hover:text-white' }} transition-all group" href="{{ route('admin.dashboard') }}">
            <span class="material-symbols-outlined group-hover:translate-x-1 duration-200">dashboard</span>
            <span class="text-sm">Dashboard</span>
        </a>
        <a class="flex items-center gap-4 px-4 py-3 {{ request()->routeIs('admin.laptops*') ? 'text-orange-500 font-bold border-r-2 border-orange-500 bg-neutral-900/30' : 'text-neutral-500 hover:bg-neutral-900/50 hover:text-white' }} transition-all group" href="#">
            <span class="material-symbols-outlined group-hover:translate-x-1 duration-200">inventory_2</span>
            <span class="text-sm">Inventory</span>
        </a>
        <a class="flex items-center gap-4 px-4 py-3 {{ request()->routeIs('admin.employees*') ? 'text-orange-500 font-bold border-r-2 border-orange-500 bg-neutral-900/30' : 'text-neutral-500 hover:bg-neutral-900/50 hover:text-white' }} transition-all group" href="{{ route('admin.employees') }}">
            <span class="material-symbols-outlined group-hover:translate-x-1 duration-200" style="font-variation-settings: {{ request()->routeIs('admin.employees*') ? "'FILL' 1" : "'FILL' 0" }};">badge</span>
            <span class="text-sm">Employees</span>
        </a>
        <a class="flex items-center gap-4 px-4 py-3 text-neutral-500 hover:bg-neutral-900/50 hover:text-white transition-all group" href="#">
            <span class="material-symbols-outlined group-hover:translate-x-1 duration-200">payments</span>
            <span class="text-sm">Sales</span>
        </a>
        <a class="flex items-center gap-4 px-4 py-3 {{ request()->routeIs('admin.orders*') ? 'text-orange-500 font-bold border-r-2 border-orange-500 bg-neutral-900/30' : 'text-neutral-500 hover:bg-neutral-900/50 hover:text-white' }} transition-all group" href="#">
            <span class="material-symbols-outlined group-hover:translate-x-1 duration-200">shopping_cart</span>
            <span class="text-sm">Orders</span>
        </a>
        <a class="flex items-center gap-4 px-4 py-3 text-neutral-500 hover:bg-neutral-900/50 hover:text-white transition-all group" href="#">
            <span class="material-symbols-outlined group-hover:translate-x-1 duration-200">monitoring</span>
            <span class="text-sm">Analytics</span>
        </a>
    </nav>
    
    <div class="px-3 flex flex-col gap-1 border-t border-neutral-800/30 pt-6">
        <a class="flex items-center gap-4 px-4 py-3 text-neutral-500 hover:bg-neutral-900/50 hover:text-white transition-all group" href="#">
            <span class="material-symbols-outlined group-hover:translate-x-1 duration-200">help</span>
            <span class="text-sm">Support</span>
        </a>
        <a class="flex items-center gap-4 px-4 py-3 text-neutral-500 hover:bg-neutral-900/50 hover:text-white transition-all group" href="#">
            <span class="material-symbols-outlined group-hover:translate-x-1 duration-200">settings</span>
            <span class="text-sm">Settings</span>
        </a>
        <button class="mt-4 flex items-center justify-center gap-2 w-full py-3 rounded-xl bg-surface-container-highest text-white text-sm font-bold hover:bg-error/20 hover:text-error transition-colors">
            <span class="material-symbols-outlined text-sm">logout</span>
            Log Out
        </button>
    </div>
</aside>
