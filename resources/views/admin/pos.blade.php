<!DOCTYPE html>
<html class="dark" lang="vi">
<head>
    @include('components.admin.head')
    <style>
        body {
            overflow: hidden;
        }
    </style>
</head>
<body class="bg-background text-on-background min-h-screen flex">
    @include('components.admin.sidebar')

    <!-- TopAppBar Component -->
    <header class="flex justify-between items-center w-full px-6 h-16 fixed top-0 z-50 bg-[#0e0e0e]/80 backdrop-blur-xl shadow-[0_0_40px_rgba(0,245,255,0.05)]">
        <div class="flex items-center gap-8 pl-64">
            <h1 class="text-xl font-bold text-[#00F5FF] tracking-tighter headline">NEON KINETIC POS</h1>
            <div class="relative flex items-center group">
                <span class="material-symbols-outlined absolute left-3 text-zinc-500 group-focus-within:text-[#00F5FF] transition-colors">search</span>
                <input class="bg-surface-container-low border-none rounded-full py-2 pl-10 pr-4 w-80 text-sm focus:ring-1 focus:ring-[#00F5FF] transition-all placeholder:text-zinc-600" placeholder="Tìm kiếm thiết bị, phụ kiện..." type="text"/>
            </div>
        </div>
        <div class="flex items-center gap-6">
            <button class="text-zinc-500 hover:text-[#00F5FF] transition-colors active:scale-95 duration-200">
                <span class="material-symbols-outlined">notifications</span>
            </button>
            <button class="text-zinc-500 hover:text-[#00F5FF] transition-colors active:scale-95 duration-200">
                <span class="material-symbols-outlined">settings</span>
            </button>
            <div class="w-8 h-8 rounded-full bg-gradient-to-tr from-[#00F5FF] to-[#64b3ff] p-[1px]">
                <div class="w-full h-full rounded-full bg-gradient-to-br from-primary/20 to-secondary/20 flex items-center justify-center">
                    <span class="material-symbols-outlined text-white text-sm">person</span>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content Canvas -->
    <main class="ml-64 pt-16 flex w-full h-[calc(100vh)] bg-background">
        <!-- Left: Product Grid Section -->
        <section class="flex-1 p-8 overflow-y-auto">
            <div class="flex items-center justify-between mb-8">
                <div class="flex gap-4">
                    <button class="px-6 py-2 rounded-lg bg-primary/10 text-primary border border-primary/20 headline text-sm font-bold">Laptops</button>
                    <button class="px-6 py-2 rounded-lg bg-surface-container hover:bg-surface-container-high text-on-surface-variant transition-colors headline text-sm">Phụ kiện</button>
                    <button class="px-6 py-2 rounded-lg bg-surface-container hover:bg-surface-container-high text-on-surface-variant transition-colors headline text-sm">Linh kiện</button>
                </div>
                <div class="text-zinc-500 text-sm font-medium">
                    <span class="text-primary font-bold">{{ count($products ?? []) }}</span> Sản phẩm có sẵn
                </div>
            </div>

            <!-- Bento-style Grid -->
            <div class="grid grid-cols-1 xl:grid-cols-2 2xl:grid-cols-3 gap-6">
                @foreach($products ?? [] as $product)
                <div class="group relative bg-surface-container-low rounded-xl overflow-hidden hover:scale-[1.02] transition-all duration-300">
                    <div class="absolute inset-0 bg-gradient-to-br from-primary/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    <div class="p-6 flex flex-col h-full">
                        <div class="h-48 mb-6 relative overflow-hidden rounded-lg bg-surface-container-lowest">
                            @if($product->image)
                            <img class="w-full h-full object-contain p-4 mix-blend-screen group-hover:scale-110 transition-transform duration-500" src="{{ $product->image }}" alt="{{ $product->name }}"/>
                            @else
                            <div class="w-full h-full flex items-center justify-center">
                                <span class="material-symbols-outlined text-6xl text-neutral-700">laptop</span>
                            </div>
                            @endif
                            @if($product->is_new ?? false)
                            <div class="absolute top-2 right-2 px-3 py-1 bg-tertiary-container/20 text-tertiary rounded-sm text-[10px] font-bold headline uppercase tracking-widest">Mới về</div>
                            @endif
                        </div>
                        <h3 class="headline text-lg font-bold mb-1">{{ $product->name }}</h3>
                        <p class="text-zinc-500 text-xs mb-4 line-clamp-2 font-medium">{{ $product->description ?? 'Sản phẩm chất lượng cao' }}</p>
                        <div class="mt-auto flex items-center justify-between">
                            <span class="headline text-xl font-black text-on-background">{{ number_format($product->price) }}₫</span>
                            <button class="w-10 h-10 rounded-full bg-surface-container-highest flex items-center justify-center text-primary group-hover:bg-primary group-hover:text-on-primary transition-all shadow-xl active:scale-90" onclick="addToCart({{ $product->id }})">
                                <span class="material-symbols-outlined">add</span>
                            </button>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </section>

        <!-- Right: Transaction Checkout Sidebar -->
        <aside class="w-96 bg-surface-container flex flex-col shadow-[-40px_0_60px_rgba(0,0,0,0.4)] relative z-10">
            <div class="p-6 border-b border-outline-variant/15 flex justify-between items-center bg-surface-container-high">
                <div class="flex flex-col">
                    <h2 class="headline font-bold text-lg">Đơn hàng hiện tại</h2>
                    <span class="text-[10px] text-zinc-500 uppercase tracking-widest font-black">TXID: #NK-{{ date('ymd') }}-POS</span>
                </div>
                <button class="w-10 h-10 rounded-full bg-error-container/20 text-error hover:bg-error-container/40 transition-colors flex items-center justify-center">
                    <span class="material-symbols-outlined">delete_sweep</span>
                </button>
            </div>

            <!-- Cart Items List -->
            <div class="flex-1 overflow-y-auto p-4 space-y-4" id="cart-items">
                @if(empty($cartItems ?? []))
                <div class="flex flex-col items-center justify-center h-full text-center">
                    <span class="material-symbols-outlined text-6xl text-neutral-700 mb-4">shopping_cart</span>
                    <p class="text-neutral-500 text-sm">Chưa có sản phẩm trong giỏ hàng</p>
                </div>
                @else
                @foreach($cartItems as $item)
                <div class="bg-surface-container-highest/40 rounded-xl p-4 flex gap-4 items-center">
                    <div class="w-16 h-16 rounded-lg bg-black/40 flex-shrink-0">
                        <div class="w-full h-full flex items-center justify-center">
                            <span class="material-symbols-outlined text-white">laptop</span>
                        </div>
                    </div>
                    <div class="flex-1">
                        <h4 class="text-sm font-bold headline">{{ $item->name }}</h4>
                        <div class="flex items-center justify-between mt-1">
                            <span class="text-xs text-primary font-bold">{{ number_format($item->price) }}₫</span>
                            <div class="flex items-center gap-3 bg-surface-container-low rounded-full px-2 py-1">
                                <button class="text-zinc-500 hover:text-white transition-colors">
                                    <span class="material-symbols-outlined text-xs">remove</span>
                                </button>
                                <span class="text-xs font-bold w-4 text-center">{{ $item->quantity }}</span>
                                <button class="text-zinc-500 hover:text-white transition-colors">
                                    <span class="material-symbols-outlined text-xs">add</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
                @endif
            </div>

            <!-- Totals and Checkout -->
            <div class="p-6 bg-surface-container-high space-y-6">
                <div class="space-y-2">
                    <div class="flex justify-between text-sm">
                        <span class="text-zinc-500">Tạm tính</span>
                        <span class="text-zinc-300 font-medium">{{ number_format($subtotal ?? 0) }}₫</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-zinc-500">Thuế (8.5%)</span>
                        <span class="text-zinc-300 font-medium">{{ number_format(($subtotal ?? 0) * 0.085) }}₫</span>
                    </div>
                    <div class="pt-4 border-t border-outline-variant/15 flex justify-between items-end">
                        <span class="headline text-zinc-400 font-bold">Tổng cộng</span>
                        <span class="headline text-3xl font-black text-on-background">{{ number_format(($subtotal ?? 0) * 1.085) }}₫</span>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <button class="py-3 rounded-xl bg-surface-container-highest border border-outline-variant/30 text-zinc-400 hover:text-white transition-all flex flex-col items-center justify-center gap-1 group">
                        <span class="material-symbols-outlined group-hover:text-primary transition-colors">qr_code_2</span>
                        <span class="text-[10px] headline font-bold uppercase tracking-widest">Chuyển khoản</span>
                    </button>
                    <button class="py-3 rounded-xl bg-surface-container-highest border border-outline-variant/30 text-zinc-400 hover:text-white transition-all flex flex-col items-center justify-center gap-1 group">
                        <span class="material-symbols-outlined group-hover:text-primary transition-colors">credit_card</span>
                        <span class="text-[10px] headline font-bold uppercase tracking-widest">Thẻ</span>
                    </button>
                </div>
                <button class="w-full py-5 rounded-full bg-secondary text-on-secondary headline font-black text-lg shadow-[0_10px_30px_rgba(255,117,36,0.3)] hover:shadow-[0_15px_40px_rgba(255,117,36,0.5)] active:scale-95 transition-all uppercase tracking-tighter">
                    Xử lý thanh toán
                </button>
            </div>
        </aside>
    </main>
</body>
</html>
