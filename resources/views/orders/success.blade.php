@extends('layouts.app')

@section('title', 'Đặt hàng thành công - NEON KINETIC')

@section('content')
<main class="max-w-screen-2xl mx-auto px-6 py-12 lg:px-12">
    <div class="min-h-[70vh] flex items-center justify-center">
        <div class="text-center max-w-2xl">
            <!-- Success Icon -->
            <div class="mb-12 relative">
                <div class="w-32 h-32 mx-auto bg-gradient-to-br from-primary/20 to-primary/5 rounded-full flex items-center justify-center border-4 border-primary/30 relative">
                    <span class="material-symbols-outlined text-primary text-6xl" style="font-variation-settings: 'FILL' 1;">check_circle</span>
                    <div class="absolute inset-0 bg-primary/20 rounded-full blur-2xl"></div>
                </div>
            </div>

            <!-- Success Message -->
            <h1 class="text-5xl md:text-7xl font-headline font-black tracking-tighter text-white mb-6">
                ĐẶT HÀNG <span class="text-primary italic">THÀNH CÔNG!</span>
            </h1>

            <p class="text-white/70 text-lg mb-4 leading-relaxed">
                Cảm ơn bạn đã tin tưởng NEON KINETIC!
            </p>
            <p class="text-white/50 text-base mb-12 leading-relaxed">
                Đơn hàng của bạn đã được tiếp nhận. Chúng tôi sẽ liên hệ với bạn trong thời gian sớm nhất để xác nhận và giao hàng.
            </p>

            <!-- Order Info -->
            <div class="bg-[#131313] rounded-2xl p-8 border border-white/5 mb-12 inline-block">
                <div class="flex items-center gap-4 text-white/70">
                    <span class="material-symbols-outlined text-primary">schedule</span>
                    <div class="text-left">
                        <div class="text-xs text-white/50 uppercase tracking-wider">Thời gian đặt hàng</div>
                        <div class="font-bold text-white">{{ now()->format('d/m/Y H:i') }}</div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-6 justify-center">
                <a href="{{ route('home') }}" class="inline-flex items-center justify-center gap-3 bg-primary text-black px-10 py-5 rounded-xl font-bold hover:scale-105 transition-transform">
                    <span class="material-symbols-outlined">home</span>
                    Về trang chủ
                </a>
                <a href="{{ route('laptops.index') }}" class="inline-flex items-center justify-center gap-3 bg-white/5 border border-white/10 text-white px-10 py-5 rounded-xl font-bold hover:bg-white/10 transition-all">
                    <span class="material-symbols-outlined">shopping_bag</span>
                    Tiếp tục mua sắm
                </a>
            </div>

            <!-- Additional Info -->
            <div class="mt-16 grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-[#131313] p-6 rounded-xl border border-white/5">
                    <span class="material-symbols-outlined text-primary text-3xl mb-3 block">call</span>
                    <h3 class="font-bold text-white mb-2">Hỗ trợ 24/7</h3>
                    <p class="text-white/50 text-sm">Liên hệ hotline để được tư vấn</p>
                </div>
                <div class="bg-[#131313] p-6 rounded-xl border border-white/5">
                    <span class="material-symbols-outlined text-primary text-3xl mb-3 block">local_shipping</span>
                    <h3 class="font-bold text-white mb-2">Giao hàng nhanh</h3>
                    <p class="text-white/50 text-sm">Nhận hàng trong 1-3 ngày</p>
                </div>
                <div class="bg-[#131313] p-6 rounded-xl border border-white/5">
                    <span class="material-symbols-outlined text-primary text-3xl mb-3 block">verified_user</span>
                    <h3 class="font-bold text-white mb-2">Bảo hành chính hãng</h3>
                    <p class="text-white/50 text-sm">Đổi trả trong 30 ngày</p>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
