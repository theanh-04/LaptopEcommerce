@extends('layouts.admin')

@section('title', 'Quản lý Đơn hàng')

@section('content')
<div class="flex gap-8 items-start h-[calc(100vh-160px)]">
    <!-- Kanban View -->
    <div class="flex-1 flex gap-6 overflow-x-auto pb-4 custom-scrollbar">
        <!-- Status Column: Pending -->
        <div class="min-w-[320px] flex flex-col gap-4">
            <div class="flex items-center justify-between px-2">
                <div class="flex items-center gap-2">
                    <div class="w-2 h-2 rounded-full bg-secondary"></div>
                    <h3 class="font-headline font-semibold text-secondary uppercase tracking-wider text-sm">Chờ Duyệt (3)</h3>
                </div>
                <span class="text-xs text-neutral-500">Hôm nay</span>
            </div>
            <div class="space-y-4">
                @foreach($pendingOrders ?? [] as $order)
                <div class="glass-panel p-5 rounded-xl hover:bg-neutral-900/50 transition-all cursor-pointer group">
                    <div class="flex justify-between items-start mb-3">
                        <span class="text-xs font-bold font-headline text-cyan-400">#{{ $order->order_number }}</span>
                        <span class="text-[10px] bg-secondary/10 text-secondary px-2 py-0.5 rounded uppercase font-bold">Mới</span>
                    </div>
                    <h4 class="font-semibold text-white group-hover:text-cyan-400 transition-colors">{{ $order->customer_name }}</h4>
                    <p class="text-sm text-neutral-400 mt-1 mb-4">{{ $order->items_count }}x sản phẩm</p>
                    <div class="flex justify-between items-end">
                        <span class="text-lg font-bold text-white tracking-tighter">{{ number_format($order->total / 1000000, 2) }}M</span>
                        <div class="flex -space-x-2">
                            <div class="w-6 h-6 rounded-full border border-background overflow-hidden bg-neutral-800">
                                <div class="w-full h-full bg-gradient-to-br from-primary/20 to-secondary/20 flex items-center justify-center">
                                    <span class="material-symbols-outlined text-white text-xs">shopping_bag</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Status Column: Processing -->
        <div class="min-w-[320px] flex flex-col gap-4">
            <div class="flex items-center justify-between px-2">
                <div class="flex items-center gap-2">
                    <div class="w-2 h-2 rounded-full bg-cyan-400"></div>
                    <h3 class="font-headline font-semibold text-cyan-400 uppercase tracking-wider text-sm">Đang Xử Lý (2)</h3>
                </div>
            </div>
            <div class="space-y-4">
                @foreach($processingOrders ?? [] as $order)
                <div class="glass-panel p-5 rounded-xl border-l-4 border-l-cyan-400 hover:bg-neutral-900/50 transition-all cursor-pointer group">
                    <div class="flex justify-between items-start mb-3">
                        <span class="text-xs font-bold font-headline text-cyan-400">#{{ $order->order_number }}</span>
                        <span class="text-[10px] bg-cyan-400/10 text-cyan-400 px-2 py-0.5 rounded uppercase font-bold">Ưu tiên</span>
                    </div>
                    <h4 class="font-semibold text-white group-hover:text-cyan-400 transition-colors">{{ $order->customer_name }}</h4>
                    <p class="text-sm text-neutral-400 mt-1 mb-4">{{ $order->items_count }}x sản phẩm</p>
                    <div class="flex justify-between items-end">
                        <span class="text-lg font-bold text-white tracking-tighter">{{ number_format($order->total / 1000000, 2) }}M</span>
                        <span class="text-[10px] text-cyan-400/60 font-bold uppercase italic">Đã Thanh Toán</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Status Column: Shipped -->
        <div class="min-w-[320px] flex flex-col gap-4">
            <div class="flex items-center justify-between px-2">
                <div class="flex items-center gap-2">
                    <div class="w-2 h-2 rounded-full bg-green-500"></div>
                    <h3 class="font-headline font-semibold text-green-500 uppercase tracking-wider text-sm">Đã Giao (8)</h3>
                </div>
            </div>
            <div class="space-y-4 opacity-70">
                @foreach($shippedOrders ?? [] as $order)
                <div class="glass-panel p-5 rounded-xl hover:bg-neutral-900/50 transition-all cursor-pointer group">
                    <div class="flex justify-between items-start mb-3">
                        <span class="text-xs font-bold font-headline text-neutral-500">#{{ $order->order_number }}</span>
                        <span class="material-symbols-outlined text-green-500 text-sm" style="font-variation-settings: 'FILL' 1;">check_circle</span>
                    </div>
                    <h4 class="font-semibold text-white">{{ $order->customer_name }}</h4>
                    <p class="text-sm text-neutral-400 mt-1 mb-4">{{ $order->items_count }}x sản phẩm</p>
                    <div class="flex justify-between items-end">
                        <span class="text-lg font-bold text-white tracking-tighter">{{ number_format($order->total / 1000000, 2) }}M</span>
                        <span class="text-[10px] text-neutral-600 font-bold uppercase">{{ $order->completed_at }}</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Order Details Sidebar (Focused View) -->
    <div class="w-[400px] h-full flex flex-col bg-surface-container-low rounded-xl overflow-hidden shadow-2xl">
        <div class="p-6 bg-surface-container border-b border-neutral-800/30">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-headline font-bold">Chi tiết đơn hàng</h2>
                <button class="p-2 hover:bg-neutral-800 rounded-lg transition-colors">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            <div class="flex items-center gap-4 mb-6">
                <div class="w-16 h-16 rounded-xl bg-neutral-800 overflow-hidden">
                    <div class="w-full h-full bg-gradient-to-br from-primary/20 to-secondary/20 flex items-center justify-center">
                        <span class="material-symbols-outlined text-white text-2xl">shopping_bag</span>
                    </div>
                </div>
                <div>
                    <p class="text-xs text-neutral-500 uppercase tracking-widest font-bold">Mã đơn hàng</p>
                    <p class="text-xl font-headline font-bold text-cyan-400">#NK-2048-92</p>
                </div>
            </div>
            <div class="flex gap-2">
                <button class="flex-1 py-2 rounded-full bg-cyan-400 text-on-primary-container font-bold text-sm">Xử lý</button>
                <button class="px-4 py-2 rounded-full border border-neutral-800 text-white font-bold text-sm">Hủy</button>
                <button class="px-2 py-2 rounded-full border border-neutral-800 text-white font-bold text-sm">
                    <span class="material-symbols-outlined text-sm">more_vert</span>
                </button>
            </div>
        </div>
        <div class="flex-1 overflow-y-auto p-6 space-y-8">
            <!-- Customer Section -->
            <section>
                <h3 class="text-[10px] uppercase tracking-[0.2em] text-neutral-500 font-bold mb-4">Thông tin khách hàng</h3>
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 rounded-full bg-cyan-400/10 flex items-center justify-center">
                        <span class="material-symbols-outlined text-cyan-400" style="font-variation-settings: 'FILL' 1;">person</span>
                    </div>
                    <div>
                        <p class="font-bold text-white">Trần Văn C</p>
                        <p class="text-sm text-neutral-400">tranvanc@neon-kinetic.io</p>
                        <p class="text-sm text-neutral-400 mt-2">123 Đường Điện Biên Phủ, Quận 1, TP. Hồ Chí Minh</p>
                    </div>
                </div>
            </section>

            <!-- Items Section -->
            <section>
                <h3 class="text-[10px] uppercase tracking-[0.2em] text-neutral-500 font-bold mb-4">Sản phẩm</h3>
                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded bg-neutral-800 flex items-center justify-center text-xs font-bold text-cyan-400">1x</div>
                            <p class="text-sm font-medium">MacBook Pro M3 Pro (14-inch)</p>
                        </div>
                        <p class="text-sm font-bold">39.99M</p>
                    </div>
                    <div class="flex justify-between items-center">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded bg-neutral-800 flex items-center justify-center text-xs font-bold text-cyan-400">1x</div>
                            <p class="text-sm font-medium">Magic Mouse - Black</p>
                        </div>
                        <p class="text-sm font-bold">2.49M</p>
                    </div>
                </div>
            </section>

            <!-- Payment Section -->
            <section class="p-4 bg-neutral-900/50 rounded-xl space-y-4">
                <div class="flex justify-between text-sm">
                    <span class="text-neutral-500">Tạm tính</span>
                    <span class="text-white">42.48M</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-neutral-500">Giảm giá (KINETIC10)</span>
                    <span class="text-secondary">-2.49M</span>
                </div>
                <div class="h-[1px] bg-neutral-800"></div>
                <div class="flex justify-between items-end">
                    <span class="text-xs uppercase font-bold text-neutral-500">Tổng cộng</span>
                    <span class="text-2xl font-bold font-headline text-white tracking-tighter">39.99M</span>
                </div>
                <div class="pt-2">
                    <div class="flex items-center gap-2 text-[10px] font-bold uppercase px-3 py-1 bg-green-500/10 text-green-500 rounded-full w-fit">
                        <span class="material-symbols-outlined text-xs">verified</span>
                        Đã thanh toán (Chuyển khoản)
                    </div>
                </div>
            </section>
        </div>
        <div class="p-6 bg-surface-container-high">
            <p class="text-[10px] text-neutral-500 mb-2 italic">Cập nhật lần cuối: 10 phút trước bởi System</p>
        </div>
    </div>
</div>

<!-- Background Grid Effect -->
<div class="fixed inset-0 pointer-events-none -z-10 overflow-hidden opacity-10">
    <div class="absolute inset-0 bg-[linear-gradient(to_right,#80808012_1px,transparent_1px),linear-gradient(to_bottom,#80808012_1px,transparent_1px)] bg-[size:40px_40px]"></div>
    <div class="absolute top-[-10%] right-[-10%] w-[50%] h-[50%] bg-cyan-500/20 rounded-full blur-[120px]"></div>
    <div class="absolute bottom-[-10%] left-[-10%] w-[40%] h-[40%] bg-orange-500/20 rounded-full blur-[100px]"></div>
</div>

@push('styles')
<style>
.custom-scrollbar::-webkit-scrollbar {
    height: 6px;
}
.custom-scrollbar::-webkit-scrollbar-track {
    background: transparent;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background: #484847;
    border-radius: 10px;
}
</style>
@endpush
@endsection
