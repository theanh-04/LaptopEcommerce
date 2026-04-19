@extends('layouts.app')

@section('title', 'Tài khoản của tôi - NEON KINETIC')

@section('content')
<main class="max-w-screen-2xl mx-auto px-6 py-12 lg:px-12">
    <!-- Header -->
    <header class="mb-16">
        <h1 class="text-5xl md:text-7xl font-headline font-black tracking-tighter text-white mb-6">
            TÀI KHOẢN <span class="text-primary italic">CỦA TÔI</span>
        </h1>
        <p class="text-white/50 font-body text-lg max-w-2xl leading-relaxed">
            Quản lý thông tin cá nhân và theo dõi đơn hàng của bạn.
        </p>
    </header>

    @if(session('success'))
        <div class="mb-8 p-4 bg-green-500/20 border border-green-500/30 rounded-xl text-green-400">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <div class="bg-[#131313] rounded-2xl p-8 border border-white/5 sticky top-32">
                <!-- Avatar -->
                <div class="flex flex-col items-center mb-8">
                    <div class="w-24 h-24 rounded-full bg-gradient-to-br from-primary to-secondary flex items-center justify-center mb-4">
                        <span class="text-4xl font-bold text-white">{{ substr($user->name, 0, 1) }}</span>
                    </div>
                    <h2 class="text-2xl font-bold text-white mb-1">{{ $user->name }}</h2>
                    <p class="text-white/50 text-sm">{{ $user->email }}</p>
                </div>

                <!-- Menu -->
                <nav class="space-y-2">
                    <a href="{{ route('profile.index') }}" class="flex items-center gap-3 px-4 py-3 bg-primary/10 text-primary rounded-lg font-medium">
                        <span class="material-symbols-outlined">person</span>
                        <span>Thông tin cá nhân</span>
                    </a>
                    <a href="{{ route('order.myOrders') }}" class="flex items-center gap-3 px-4 py-3 text-white/70 hover:bg-white/5 rounded-lg font-medium transition-all">
                        <span class="material-symbols-outlined">receipt_long</span>
                        <span>Đơn hàng của tôi</span>
                    </a>
                    <a href="{{ route('profile.changePassword') }}" class="flex items-center gap-3 px-4 py-3 text-white/70 hover:bg-white/5 rounded-lg font-medium transition-all">
                        <span class="material-symbols-outlined">lock</span>
                        <span>Đổi mật khẩu</span>
                    </a>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 text-red-400 hover:bg-red-500/10 rounded-lg font-medium transition-all">
                            <span class="material-symbols-outlined">logout</span>
                            <span>Đăng xuất</span>
                        </button>
                    </form>
                </nav>
            </div>
        </div>

        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Personal Info -->
            <div class="bg-[#131313] rounded-2xl p-8 border border-white/5">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-2xl font-headline font-bold text-white">Thông tin cá nhân</h3>
                    <a href="{{ route('profile.edit') }}" class="px-6 py-2 bg-primary text-black rounded-lg font-bold hover:shadow-[0_0_20px_rgba(0,245,255,0.3)] transition-all">
                        Chỉnh sửa
                    </a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="text-xs text-white/50 uppercase tracking-wider block mb-2">Họ và tên</label>
                        <p class="text-white font-medium text-lg">{{ $user->name }}</p>
                    </div>
                    <div>
                        <label class="text-xs text-white/50 uppercase tracking-wider block mb-2">Email</label>
                        <p class="text-white font-medium text-lg">{{ $user->email }}</p>
                    </div>
                    <div>
                        <label class="text-xs text-white/50 uppercase tracking-wider block mb-2">Số điện thoại</label>
                        <p class="text-white font-medium text-lg">{{ $user->phone ?? 'Chưa cập nhật' }}</p>
                    </div>
                    <div>
                        <label class="text-xs text-white/50 uppercase tracking-wider block mb-2">Vai trò</label>
                        <p class="text-white font-medium text-lg">
                            @if($user->isAdmin())
                                <span class="px-3 py-1 bg-secondary/20 text-secondary rounded-full text-sm">Quản trị viên</span>
                            @else
                                <span class="px-3 py-1 bg-primary/20 text-primary rounded-full text-sm">Khách hàng</span>
                            @endif
                        </p>
                    </div>
                    <div class="md:col-span-2">
                        <label class="text-xs text-white/50 uppercase tracking-wider block mb-2">Địa chỉ</label>
                        <p class="text-white font-medium">{{ $user->address ?? 'Chưa cập nhật' }}</p>
                    </div>
                </div>
            </div>

            <!-- Recent Orders -->
            <div class="bg-[#131313] rounded-2xl p-8 border border-white/5">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-2xl font-headline font-bold text-white">Đơn hàng gần đây</h3>
                    <a href="{{ route('order.myOrders') }}" class="text-primary hover:underline font-medium">
                        Xem tất cả →
                    </a>
                </div>

                @if($orders->isEmpty())
                    <div class="text-center py-12">
                        <span class="material-symbols-outlined text-white/30 text-6xl mb-4 block">receipt_long</span>
                        <p class="text-white/50">Bạn chưa có đơn hàng nào</p>
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach($orders as $order)
                            <div class="p-6 bg-[#0e0e0e] rounded-xl border border-white/5 hover:border-primary/20 transition-all">
                                <div class="flex justify-between items-start mb-4">
                                    <div>
                                        <h4 class="text-white font-bold text-lg mb-1">{{ $order->order_number }}</h4>
                                        <p class="text-white/50 text-sm">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                                    </div>
                                    @php
                                        $statusColors = [
                                            'pending' => 'bg-yellow-500/20 text-yellow-400',
                                            'processing' => 'bg-blue-500/20 text-blue-400',
                                            'completed' => 'bg-green-500/20 text-green-400',
                                            'cancelled' => 'bg-red-500/20 text-red-400'
                                        ];
                                        $statusLabels = [
                                            'pending' => 'Chờ xử lý',
                                            'processing' => 'Đang xử lý',
                                            'completed' => 'Hoàn thành',
                                            'cancelled' => 'Đã hủy'
                                        ];
                                    @endphp
                                    <span class="px-3 py-1 rounded-full text-xs font-bold {{ $statusColors[$order->status] ?? 'bg-gray-500/20 text-gray-400' }}">
                                        {{ $statusLabels[$order->status] ?? $order->status }}
                                    </span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <p class="text-white/70 text-sm">{{ $order->orderItems->count() }} sản phẩm</p>
                                    <p class="text-secondary font-bold text-xl">{{ number_format($order->total_amount) }}₫</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</main>
@endsection
