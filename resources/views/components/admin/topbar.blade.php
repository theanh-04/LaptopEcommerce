<header class="fixed top-0 left-64 right-0 z-40 h-16 bg-neutral-950/60 backdrop-blur-xl flex justify-between items-center px-8 shadow-[0_0_40px_rgba(0,245,255,0.05)] border-b border-white/5">
    <div class="flex items-center flex-1 max-w-xl">
        <div class="relative w-full">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-neutral-500 text-lg">search</span>
            <input class="w-full bg-neutral-900/50 border-none rounded-full py-2 pl-10 pr-4 text-sm text-on-surface focus:ring-1 focus:ring-primary/50 transition-all placeholder:text-neutral-600" placeholder="Tìm kiếm dữ liệu, sản phẩm hoặc mã đơn hàng..." type="text"/>
        </div>
    </div>
    
    <div class="flex items-center gap-6">
        <div class="flex items-center gap-2">
            <button class="w-10 h-10 flex items-center justify-center text-neutral-400 hover:text-cyan-300 transition-colors duration-300 active:scale-95">
                <span class="material-symbols-outlined">notifications</span>
            </button>
            <button class="w-10 h-10 flex items-center justify-center text-neutral-400 hover:text-cyan-300 transition-colors duration-300 active:scale-95">
                <span class="material-symbols-outlined">settings</span>
            </button>
        </div>
        
        <div class="h-8 w-[1px] bg-neutral-800"></div>
        
        <div class="flex items-center gap-3 pl-2">
            <div class="text-right">
                <p class="text-sm font-bold text-white leading-none">Admin Profile</p>
                <p class="text-[10px] text-cyan-400 font-headline uppercase tracking-wider mt-1">Super User</p>
            </div>
            <div class="w-10 h-10 rounded-full bg-surface-container-highest overflow-hidden border border-primary/20">
                <div class="w-full h-full bg-gradient-to-br from-primary/20 to-secondary/20 flex items-center justify-center">
                    <span class="material-symbols-outlined text-white">person</span>
                </div>
            </div>
        </div>
    </div>
</header>
