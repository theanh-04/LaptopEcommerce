@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<!-- Dashboard Title Section -->
<div class="flex justify-between items-end mb-8">
    <div>
        <h1 class="text-4xl font-extrabold tracking-tighter text-white font-headline">BẢNG ĐIỀU KHIỂN</h1>
        <p class="text-neutral-500 mt-2 flex items-center gap-2">
            <span class="w-2 h-2 rounded-full bg-primary animate-pulse"></span>
            Hệ thống đang hoạt động tối ưu. Cập nhật lần cuối: 2 phút trước.
        </p>
    </div>
    <div class="flex gap-3">
        <button class="px-6 py-2.5 rounded-full bg-surface-container-high text-on-surface font-bold text-sm border border-outline-variant/15 hover:bg-surface-bright transition-all flex items-center gap-2">
            <span class="material-symbols-outlined text-sm">download</span>
            Xuất báo cáo
        </button>
        <button class="px-6 py-2.5 rounded-full bg-secondary text-on-secondary font-bold text-sm hover:shadow-[0_0_20px_rgba(255,117,36,0.3)] transition-all flex items-center gap-2">
            <span class="material-symbols-outlined text-sm">add</span>
            Tạo đơn hàng
        </button>
    </div>
</div>

<!-- Stats Bento Grid -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <!-- Total Sales -->
    <div class="bg-surface-container-low p-8 rounded-xl relative overflow-hidden group hover:bg-surface-container-high transition-all duration-500">
        <div class="absolute -right-4 -top-4 w-24 h-24 bg-secondary/10 blur-3xl rounded-full"></div>
        <div class="flex justify-between items-start mb-4">
            <div class="p-3 bg-secondary/20 rounded-lg">
                <span class="material-symbols-outlined text-secondary" style="font-variation-settings: 'FILL' 1;">monetization_on</span>
            </div>
            <span class="text-secondary text-xs font-bold bg-secondary/10 px-2 py-1 rounded">+12.5%</span>
        </div>
        <p class="text-neutral-500 text-sm font-medium">Tổng doanh thu</p>
        <h3 class="text-3xl font-bold mt-1 text-white font-headline tracking-tight">8.4B VND</h3>
        <div class="mt-4 h-1 w-full bg-neutral-800 rounded-full overflow-hidden">
            <div class="h-full bg-secondary w-3/4 rounded-full"></div>
        </div>
    </div>

    <!-- Active Orders -->
    <div class="bg-surface-container-low p-8 rounded-xl relative overflow-hidden group hover:bg-surface-container-high transition-all duration-500">
        <div class="absolute -right-4 -top-4 w-24 h-24 bg-primary/10 blur-3xl rounded-full"></div>
        <div class="flex justify-between items-start mb-4">
            <div class="p-3 bg-primary/20 rounded-lg">
                <span class="material-symbols-outlined text-primary" style="font-variation-settings: 'FILL' 1;">shopping_bag</span>
            </div>
            <span class="text-primary text-xs font-bold bg-primary/10 px-2 py-1 rounded">Đang xử lý</span>
        </div>
        <p class="text-neutral-500 text-sm font-medium">Đơn hàng hiện tại</p>
        <h3 class="text-3xl font-bold mt-1 text-white font-headline tracking-tight">24</h3>
        <p class="text-xs text-neutral-400 mt-2">6 đơn hàng cần giao gấp trong hôm nay</p>
    </div>

    <!-- Top Seller -->
    <div class="bg-surface-container-low p-8 rounded-xl relative overflow-hidden group hover:bg-surface-container-high transition-all duration-500">
        <div class="absolute -right-4 -top-4 w-24 h-24 bg-tertiary/10 blur-3xl rounded-full"></div>
        <div class="flex justify-between items-start mb-4">
            <div class="p-3 bg-tertiary/20 rounded-lg">
                <span class="material-symbols-outlined text-tertiary" style="font-variation-settings: 'FILL' 1;">star</span>
            </div>
            <span class="text-tertiary text-xs font-bold bg-tertiary/10 px-2 py-1 rounded">Bán chạy nhất</span>
        </div>
        <p class="text-neutral-500 text-sm font-medium">Sản phẩm tiêu biểu</p>
        <h3 class="text-xl font-bold mt-1 text-white font-headline leading-tight">MacBook Pro M3 Max</h3>
        <div class="flex items-center gap-2 mt-2">
            <span class="text-[10px] bg-surface-container-highest px-2 py-0.5 rounded text-neutral-300">Space Black</span>
            <span class="text-[10px] bg-surface-container-highest px-2 py-0.5 rounded text-neutral-300">64GB RAM</span>
        </div>
    </div>
</div>

<!-- Hero Section: Chart and Activity -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Kinetic Performance Chart -->
    <div class="lg:col-span-2 bg-surface-container rounded-xl overflow-hidden border border-white/5 flex flex-col">
        <div class="p-8 pb-4 flex justify-between items-center">
            <div>
                <h2 class="text-xl font-bold text-white font-headline tracking-tight">KINETIC PERFORMANCE</h2>
                <p class="text-xs text-neutral-500 uppercase tracking-widest mt-1">Phân tích doanh thu thời gian thực</p>
            </div>
            <div class="flex bg-surface-container-low p-1 rounded-lg">
                <button class="px-4 py-1.5 text-xs font-bold bg-surface-container-high text-primary rounded-md">Tuần</button>
                <button class="px-4 py-1.5 text-xs font-bold text-neutral-500">Tháng</button>
                <button class="px-4 py-1.5 text-xs font-bold text-neutral-500">Năm</button>
            </div>
        </div>
        <div class="flex-1 p-8 pt-0 min-h-[400px] relative flex flex-col justify-end">
            <!-- Simulated Chart Grid -->
            <div class="absolute inset-x-8 inset-y-12 grid grid-cols-7 gap-4">
                <div class="h-full border-r border-white/5"></div>
                <div class="h-full border-r border-white/5"></div>
                <div class="h-full border-r border-white/5"></div>
                <div class="h-full border-r border-white/5"></div>
                <div class="h-full border-r border-white/5"></div>
                <div class="h-full border-r border-white/5"></div>
                <div class="h-full"></div>
            </div>
            <!-- Simulated Kinetic Wave/Chart -->
            <div class="relative h-64 w-full">
                <svg class="w-full h-full" preserveAspectRatio="none" viewBox="0 0 800 200">
                    <defs>
                        <linearGradient id="glow-grad" x1="0" x2="0" y1="0" y2="1">
                            <stop offset="0%" stop-color="#a1faff" stop-opacity="0.3"></stop>
                            <stop offset="100%" stop-color="#a1faff" stop-opacity="0"></stop>
                        </linearGradient>
                    </defs>
                    <path d="M0 150 Q 100 120 200 140 T 400 60 T 600 100 T 800 40 L 800 200 L 0 200 Z" fill="url(#glow-grad)"></path>
                    <path class="drop-shadow-[0_0_10px_rgba(161,250,255,0.8)]" d="M0 150 Q 100 120 200 140 T 400 60 T 600 100 T 800 40" fill="none" stroke="#a1faff" stroke-width="3"></path>
                    <circle cx="200" cy="140" fill="#a1faff" r="4"></circle>
                    <circle cx="400" cy="60" fill="#a1faff" r="4"></circle>
                    <circle cx="600" cy="100" fill="#a1faff" r="4"></circle>
                    <circle cx="800" cy="40" fill="#a1faff" r="4"></circle>
                </svg>
            </div>
            <!-- Chart Labels -->
            <div class="flex justify-between mt-6 px-2">
                <span class="text-[10px] font-headline text-neutral-600">THỨ 2</span>
                <span class="text-[10px] font-headline text-neutral-600">THỨ 3</span>
                <span class="text-[10px] font-headline text-neutral-600">THỨ 4</span>
                <span class="text-[10px] font-headline text-neutral-300 font-bold">THỨ 5 (HÔM NAY)</span>
                <span class="text-[10px] font-headline text-neutral-600">THỨ 6</span>
                <span class="text-[10px] font-headline text-neutral-600">THỨ 7</span>
                <span class="text-[10px] font-headline text-neutral-600">CHỦ NHẬT</span>
            </div>
        </div>
    </div>

    <!-- Recent Activity Feed -->
    <div class="flex flex-col gap-6">
        <h2 class="text-xl font-bold text-white font-headline tracking-tight px-2">Hoạt động gần đây</h2>
        <div class="space-y-4 overflow-y-auto max-h-[500px] pr-2">
            <!-- Activity Card 1 -->
            <div class="bg-surface-variant/40 backdrop-blur-md border border-white/5 p-4 rounded-xl hover:bg-surface-variant/60 transition-all cursor-pointer group">
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-primary text-xl">shopping_cart_checkout</span>
                    </div>
                    <div>
                        <p class="text-sm text-white font-bold">Đơn hàng mới #8429</p>
                        <p class="text-xs text-neutral-400 mt-1">Khách hàng: Nguyễn Văn A • 12.500.000đ</p>
                        <p class="text-[10px] text-neutral-600 mt-2 uppercase font-bold">5 phút trước</p>
                    </div>
                    <span class="material-symbols-outlined ml-auto text-neutral-600 group-hover:text-primary transition-colors">chevron_right</span>
                </div>
            </div>

            <!-- Activity Card 2 -->
            <div class="bg-surface-variant/40 backdrop-blur-md border border-white/5 p-4 rounded-xl hover:bg-surface-variant/60 transition-all cursor-pointer group">
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 rounded-full bg-secondary/10 flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-secondary text-xl">inventory</span>
                    </div>
                    <div>
                        <p class="text-sm text-white font-bold">Kho hàng cập nhật</p>
                        <p class="text-xs text-neutral-400 mt-1">iPhone 15 Pro Max (Natural) đã nhập thêm 50 máy</p>
                        <p class="text-[10px] text-neutral-600 mt-2 uppercase font-bold">45 phút trước</p>
                    </div>
                    <span class="material-symbols-outlined ml-auto text-neutral-600 group-hover:text-secondary transition-colors">chevron_right</span>
                </div>
            </div>

            <!-- Activity Card 3 -->
            <div class="bg-surface-variant/40 backdrop-blur-md border border-white/5 p-4 rounded-xl hover:bg-surface-variant/60 transition-all cursor-pointer group">
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 rounded-full bg-tertiary/10 flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-tertiary text-xl">person_add</span>
                    </div>
                    <div>
                        <p class="text-sm text-white font-bold">Người dùng mới</p>
                        <p class="text-xs text-neutral-400 mt-1">Trần Thị B vừa đăng ký tài khoản Platinum</p>
                        <p class="text-[10px] text-neutral-600 mt-2 uppercase font-bold">2 giờ trước</p>
                    </div>
                    <span class="material-symbols-outlined ml-auto text-neutral-600 group-hover:text-tertiary transition-colors">chevron_right</span>
                </div>
            </div>

            <!-- Activity Card 4 -->
            <div class="bg-surface-variant/40 backdrop-blur-md border border-white/5 p-4 rounded-xl hover:bg-surface-variant/60 transition-all cursor-pointer group">
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 rounded-full bg-error/10 flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-error text-xl">error</span>
                    </div>
                    <div>
                        <p class="text-sm text-white font-bold">Cảnh báo hệ thống</p>
                        <p class="text-xs text-neutral-400 mt-1">Thanh toán đơn #8410 không thành công</p>
                        <p class="text-[10px] text-neutral-600 mt-2 uppercase font-bold">3 giờ trước</p>
                    </div>
                    <span class="material-symbols-outlined ml-auto text-neutral-600 group-hover:text-error transition-colors">chevron_right</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Inventory Quick Look Section -->
<div class="mt-12">
    <div class="flex justify-between items-center mb-6 px-2">
        <h2 class="text-2xl font-bold text-white font-headline tracking-tighter">Sản phẩm nổi bật</h2>
        <a class="text-primary text-sm font-bold flex items-center gap-2 hover:underline" href="#">
            Xem tất cả kho hàng
            <span class="material-symbols-outlined text-sm">arrow_forward</span>
        </a>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        @foreach($featuredLaptops ?? [] as $laptop)
        <div class="bg-surface-container-low rounded-xl p-4 group">
            <div class="aspect-square bg-surface-container-highest rounded-lg mb-4 overflow-hidden relative">
                <img alt="{{ $laptop->name }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" src="{{ $laptop->image ?? 'https://via.placeholder.com/400' }}"/>
                @if($laptop->is_featured)
                <div class="absolute top-2 right-2 bg-secondary text-on-secondary text-[10px] font-bold px-2 py-1 rounded-full shadow-lg">Hot</div>
                @endif
            </div>
            <h4 class="text-white font-bold text-sm">{{ $laptop->name }}</h4>
            <div class="flex justify-between items-center mt-2">
                <span class="text-neutral-500 text-xs">Còn {{ $laptop->stock }} sản phẩm</span>
                <span class="text-primary font-headline font-bold">{{ number_format($laptop->price / 1000000, 1) }}M</span>
            </div>
        </div>
        @endforeach
    </div>
</div>

<!-- Contextual FAB for Transactional Screens -->
<button class="fixed bottom-8 right-8 w-14 h-14 rounded-full bg-secondary text-on-secondary shadow-[0_8px_32px_rgba(255,117,36,0.4)] flex items-center justify-center hover:scale-110 transition-transform z-50">
    <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">add</span>
</button>
@endsection
