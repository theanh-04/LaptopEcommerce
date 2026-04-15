@extends('layouts.app')

@section('title', 'Giỏ hàng - NEON KINETIC')

@section('styles')
<style>
    .neon-glow-secondary {
        box-shadow: 0 0 30px rgba(255, 117, 36, 0.4);
    }
</style>
@endsection

@section('content')
<main class="max-w-screen-2xl mx-auto px-6 py-12 lg:px-12">
    <!-- Header Section -->
    <header class="mb-16">
        <h1 class="text-5xl md:text-7xl font-headline font-black tracking-tighter text-white mb-6">
            GIỎ <span class="text-primary italic">HÀNG</span>
        </h1>
        <p class="text-white/50 font-body text-lg max-w-2xl leading-relaxed">
            Kiểm tra lại cấu hình và các thiết bị bạn đã chọn trước khi tiến hành tối ưu hóa trải nghiệm kỹ thuật số của mình.
        </p>
    </header>

    @if(empty($cart))
        <div class="text-center py-20">
            <span class="material-symbols-outlined text-white/20 text-8xl mb-6">shopping_cart</span>
            <h2 class="text-3xl font-headline font-bold text-white/50 mb-4">Giỏ hàng trống</h2>
            <p class="text-white/40 mb-8">Hãy khám phá những sản phẩm tuyệt vời của chúng tôi</p>
            <a href="{{ route('laptops.index') }}" class="inline-flex items-center gap-3 bg-primary text-black px-8 py-4 rounded-xl font-bold hover:scale-105 transition-transform">
                <span class="material-symbols-outlined">arrow_back</span>
                Tiếp tục mua hàng
            </a>
        </div>
    @else
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-16 items-start">
        <!-- Product List Area -->
        <div class="lg:col-span-8 flex flex-col gap-10">
            @php $total = 0; @endphp
            @foreach($cart as $id => $item)
                @php $total += $item['price'] * $item['quantity']; @endphp
                <!-- Cart Item -->
                <div class="glass-panel p-8 rounded-2xl flex flex-col md:flex-row gap-8 items-center group transition-all duration-500 hover:bg-[#1a1a1a]/50">
                    <div class="w-32 h-32 md:w-44 md:h-44 shrink-0 bg-[#131313] rounded-xl overflow-hidden flex items-center justify-center p-6 border border-white/5">
                        <img alt="{{ $item['name'] }}" class="w-full h-full object-contain" src="{{ $item['image'] ?? 'https://via.placeholder.com/300x300?text=Laptop' }}"/>
                    </div>
                    
                    <div class="flex-grow space-y-4 text-center md:text-left">
                        <div class="flex flex-col md:flex-row md:items-start justify-between gap-4">
                            <div>
                                <h3 class="text-2xl font-headline font-bold text-white group-hover:text-primary transition-colors">{{ $item['name'] }}</h3>
                                <p class="text-sm text-white/50 font-body mt-1">Số lượng: {{ $item['quantity'] }}</p>
                            </div>
                            <span class="text-secondary font-headline font-black text-2xl">{{ number_format($item['price'], 0, ',', '.') }}₫</span>
                        </div>
                        
                        <div class="flex flex-wrap items-center justify-center md:justify-start gap-8 mt-6">
                            <div class="flex items-center bg-[#1a1a1a]/50 rounded-full p-1.5 border border-white/5">
                                <span class="w-10 h-10 flex items-center justify-center text-white/40">
                                    <span class="material-symbols-outlined text-base">remove</span>
                                </span>
                                <span class="w-12 text-center font-headline font-bold text-lg">{{ $item['quantity'] }}</span>
                                <span class="w-10 h-10 flex items-center justify-center text-white/40">
                                    <span class="material-symbols-outlined text-base">add</span>
                                </span>
                            </div>
                            
                            <form action="{{ route('cart.remove', $id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-white/40 hover:text-error flex items-center gap-2 text-sm font-label transition-colors">
                                    <span class="material-symbols-outlined text-lg">delete</span>
                                    Xóa khỏi giỏ
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach

            <div class="mt-4 flex justify-between items-center px-4">
                <a class="text-primary flex items-center gap-3 font-headline font-bold hover:gap-5 transition-all duration-300" href="{{ route('laptops.index') }}">
                    <span class="material-symbols-outlined">west</span>
                    Tiếp tục mua hàng
                </a>
            </div>
        </div>

        <!-- Summary Sidebar -->
        <aside class="lg:col-span-4 sticky top-32">
            <div class="bg-[#131313] rounded-2xl p-10 border border-white/5 shadow-2xl relative overflow-hidden">
                <!-- Background Glow Decoration -->
                <div class="absolute -top-24 -right-24 w-48 h-48 bg-secondary/10 blur-[100px] rounded-full"></div>
                
                <h2 class="text-2xl font-headline font-black tracking-tight text-white mb-10 flex items-center gap-3">
                    TỔNG KẾT <span class="text-secondary italic">ĐƠN HÀNG</span>
                </h2>

                <!-- Summary Details -->
                <div class="space-y-6 mb-10">
                    <div class="flex justify-between items-center group">
                        <span class="text-white/50 font-label uppercase tracking-[0.2em] text-[10px] group-hover:text-white transition-colors">Tạm tính</span>
                        <span class="text-white/90 font-headline font-medium text-lg">{{ number_format($total, 0, ',', '.') }}₫</span>
                    </div>
                    
                    <div class="flex justify-between items-center group">
                        <span class="text-white/50 font-label uppercase tracking-[0.2em] text-[10px] group-hover:text-white transition-colors">Vận chuyển</span>
                        <div class="flex items-center gap-2">
                            <span class="text-primary font-headline font-bold text-sm bg-primary/10 px-3 py-1 rounded-full">MIỄN PHÍ</span>
                        </div>
                    </div>
                </div>

                <!-- Final Total -->
                <div class="space-y-6 pt-10 border-t-2 border-dashed border-white/5 mb-10">
                    <div class="flex justify-between items-center">
                        <div>
                            <span class="text-white font-headline font-bold text-lg block">Tổng cộng</span>
                            <span class="text-[10px] text-white/50 font-body uppercase tracking-wider">(Đã bao gồm VAT)</span>
                        </div>
                        <span class="text-4xl font-headline font-black text-secondary tracking-tighter drop-shadow-[0_0_15px_rgba(255,117,36,0.3)]">
                            {{ number_format($total, 0, ',', '.') }}₫
                        </span>
                    </div>
                </div>

                <!-- Enhanced Checkout Button -->
                <a href="{{ route('order.checkout') }}" class="group w-full bg-secondary text-white font-headline font-black py-6 rounded-2xl flex items-center justify-center gap-4 transition-all duration-500 hover:scale-[1.03] hover:brightness-110 active:scale-95 neon-glow-secondary relative overflow-hidden">
                    <span class="absolute inset-0 bg-white/10 translate-y-full group-hover:translate-y-0 transition-transform duration-500"></span>
                    <span class="relative z-10 text-lg">TIẾN HÀNH THANH TOÁN</span>
                    <span class="material-symbols-outlined relative z-10 group-hover:translate-x-2 transition-transform">arrow_forward</span>
                </a>

                <!-- Trust Badges -->
                <div class="mt-10 grid grid-cols-2 gap-4">
                    <div class="flex flex-col items-center gap-2 p-4 rounded-xl bg-white/5 border border-white/5 group transition-colors hover:bg-white/10">
                        <span class="material-symbols-outlined text-primary text-xl">verified_user</span>
                        <span class="text-[9px] text-white/50 font-label uppercase text-center tracking-widest">Bảo mật 100%</span>
                    </div>
                    <div class="flex flex-col items-center gap-2 p-4 rounded-xl bg-white/5 border border-white/5 group transition-colors hover:bg-white/10">
                        <span class="material-symbols-outlined text-primary text-xl">local_shipping</span>
                        <span class="text-[9px] text-white/50 font-label uppercase text-center tracking-widest">Giao hỏa tốc</span>
                    </div>
                </div>
            </div>
        </aside>
    </div>
    @endif
</main>
@endsection
