@extends('layouts.app')

@section('title', 'Đơn hàng của tôi - NEON KINETIC')

@section('content')
<main class="max-w-screen-2xl mx-auto px-6 py-12 lg:px-12">
    <!-- Header Section -->
    <header class="mb-16">
        <h1 class="text-5xl md:text-7xl font-headline font-black tracking-tighter text-white mb-6">
            ĐƠN HÀNG <span class="text-primary italic">CỦA TÔI</span>
        </h1>
        <p class="text-white/50 font-body text-lg max-w-2xl leading-relaxed">
            Theo dõi và quản lý tất cả đơn hàng của bạn tại đây.
        </p>
    </header>

    @if($orders->isEmpty())
        <!-- Empty State -->
        <div class="min-h-[50vh] flex items-center justify-center">
            <div class="text-center max-w-md">
                <div class="w-32 h-32 mx-auto bg-white/5 rounded-full flex items-center justify-center mb-8">
                    <span class="material-symbols-outlined text-white/30 text-6xl">receipt_long</span>
                </div>
                <h2 class="text-3xl font-headline font-bold text-white mb-4">Chưa có đơn hàng nào</h2>
                <p class="text-white/50 mb-8">Bạn chưa đặt đơn hàng nào. Hãy khám phá các sản phẩm của chúng tôi!</p>
                <a href="{{ route('laptops.index') }}" class="inline-flex items-center gap-3 bg-primary text-black px-8 py-4 rounded-xl font-bold hover:scale-105 transition-transform">
                    <span class="material-symbols-outlined">shopping_bag</span>
                    Mua sắm ngay
                </a>
            </div>
        </div>
    @else
        <!-- Orders List -->
        <div class="space-y-6">
            @foreach($orders as $order)
                <div class="bg-[#131313] rounded-2xl border border-white/5 overflow-hidden hover:border-primary/20 transition-all">
                    <!-- Order Header -->
                    <div class="p-8 border-b border-white/5">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                            <div class="flex-1">
                                <div class="flex items-center gap-4 mb-3">
                                    <h3 class="text-2xl font-headline font-bold text-white">{{ $order->order_number }}</h3>
                                    @php
                                        $statusColors = [
                                            'pending' => 'bg-yellow-500/20 text-yellow-400 border-yellow-500/30',
                                            'processing' => 'bg-blue-500/20 text-blue-400 border-blue-500/30',
                                            'completed' => 'bg-green-500/20 text-green-400 border-green-500/30',
                                            'cancelled' => 'bg-red-500/20 text-red-400 border-red-500/30'
                                        ];
                                        $statusLabels = [
                                            'pending' => 'Chờ xử lý',
                                            'processing' => 'Đang xử lý',
                                            'completed' => 'Hoàn thành',
                                            'cancelled' => 'Đã hủy'
                                        ];
                                    @endphp
                                    <span class="px-4 py-1.5 rounded-full text-xs font-bold border {{ $statusColors[$order->status] ?? 'bg-gray-500/20 text-gray-400 border-gray-500/30' }}">
                                        {{ $statusLabels[$order->status] ?? $order->status }}
                                    </span>
                                </div>
                                <div class="flex flex-wrap gap-6 text-sm text-white/50">
                                    <div class="flex items-center gap-2">
                                        <span class="material-symbols-outlined text-lg">schedule</span>
                                        <span>{{ $order->created_at->format('d/m/Y H:i') }}</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="material-symbols-outlined text-lg">inventory_2</span>
                                        <span>{{ $order->orderItems->count() }} sản phẩm</span>
                                    </div>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-xs text-white/50 uppercase tracking-wider mb-2">Tổng tiền</div>
                                <div class="text-3xl font-headline font-black text-secondary">{{ number_format($order->total_amount) }}₫</div>
                            </div>
                        </div>
                    </div>

                    <!-- Order Items -->
                    <div class="p-8">
                        <h4 class="text-sm font-bold text-white/70 uppercase tracking-wider mb-6">Chi tiết đơn hàng</h4>
                        <div class="space-y-4">
                            @foreach($order->orderItems as $item)
                                <div class="flex gap-6 p-4 bg-[#0e0e0e] rounded-xl border border-white/5">
                                    <img src="{{ $item->laptop->image ?? 'https://via.placeholder.com/100x100?text=Laptop' }}" 
                                         alt="{{ $item->laptop->name }}" 
                                         class="w-24 h-24 object-cover rounded-lg">
                                    <div class="flex-1">
                                        <h5 class="font-bold text-white mb-2">{{ $item->laptop->name }}</h5>
                                        <div class="flex items-center gap-6 text-sm text-white/50">
                                            <span>Số lượng: {{ $item->quantity }}</span>
                                            <span>Đơn giá: {{ number_format($item->price) }}₫</span>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-lg font-bold text-secondary">{{ number_format($item->price * $item->quantity) }}₫</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Order Summary -->
                        <div class="mt-8 pt-6 border-t border-white/5">
                            <div class="max-w-md ml-auto space-y-3">
                                @if($order->discount_amount > 0)
                                    <div class="flex justify-between text-white/70">
                                        <span>Giảm giá ({{ $order->promo_code }})</span>
                                        <span class="text-green-400 font-bold">-{{ number_format($order->discount_amount) }}₫</span>
                                    </div>
                                @endif
                                @if($order->shipping_fee > 0)
                                    <div class="flex justify-between text-white/70">
                                        <span>Phí vận chuyển</span>
                                        <span class="font-bold">{{ number_format($order->shipping_fee) }}₫</span>
                                    </div>
                                @else
                                    <div class="flex justify-between text-white/70">
                                        <span>Phí vận chuyển</span>
                                        <span class="text-green-400 font-bold">MIỄN PHÍ</span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Customer Info -->
                        <div class="mt-8 pt-6 border-t border-white/5">
                            <h4 class="text-sm font-bold text-white/70 uppercase tracking-wider mb-4">Thông tin giao hàng</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">
                                <div>
                                    <div class="text-white/50 mb-1">Người nhận</div>
                                    <div class="text-white font-medium">{{ $order->customer_name }}</div>
                                </div>
                                <div>
                                    <div class="text-white/50 mb-1">Số điện thoại</div>
                                    <div class="text-white font-medium">{{ $order->customer_phone }}</div>
                                </div>
                                <div class="md:col-span-2">
                                    <div class="text-white/50 mb-1">Địa chỉ</div>
                                    <div class="text-white font-medium">{{ $order->customer_address }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</main>
@endsection
