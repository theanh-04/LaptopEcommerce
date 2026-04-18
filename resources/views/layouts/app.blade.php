<!DOCTYPE html>
<html lang="vi" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'NEON KINETIC - Laptop Store')</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700;800;900&family=Manrope:wght@200;300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "surface": "#080808",
                        "primary": "#00F5FF",
                        "secondary": "#ff7524",
                        "tertiary": "#64b3ff"
                    },
                    borderRadius: {
                        "DEFAULT": "0.75rem",
                        "lg": "1.25rem",
                        "xl": "2.5rem"
                    },
                    fontFamily: {
                        "headline": ["Space Grotesk"],
                        "body": ["Manrope"]
                    }
                }
            }
        }
    </script>
    <style>
        .mesh-gradient {
            background-color: #080808;
            background-image: radial-gradient(at 0% 0%, hsla(182,100%,15%,0.15) 0px, transparent 50%),
                              radial-gradient(at 100% 0%, hsla(24,100%,15%,0.1) 0px, transparent 50%),
                              radial-gradient(at 100% 100%, hsla(208,100%,15%,0.15) 0px, transparent 50%),
                              radial-gradient(at 0% 100%, hsla(182,100%,15%,0.1) 0px, transparent 50%);
        }
        .glass {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }
        .card-glow:hover {
            box-shadow: 0 0 40px -10px rgba(0, 245, 255, 0.15);
            border-color: rgba(0, 245, 255, 0.2);
        }
        .text-tight { letter-spacing: -0.04em; }
        .body-relaxed { line-height: 1.8; }
    </style>
    @yield('styles')
</head>
<body class="dark bg-surface text-white font-body mesh-gradient">
    <!-- Header -->
    <header class="bg-[#080808]/60 backdrop-blur-xl sticky top-0 z-50 border-b border-white/[0.05]">
        <div class="flex justify-between items-center w-full px-8 py-5 max-w-screen-2xl mx-auto">
            <a href="{{ route('home') }}" class="text-2xl font-black tracking-tighter text-primary font-headline">NEON KINETIC</a>
            <nav class="hidden md:flex gap-10 font-headline font-semibold tracking-tight text-sm uppercase">
                <a class="text-primary relative after:absolute after:bottom-[-22px] after:left-0 after:w-full after:h-[2px] after:bg-primary" href="{{ route('home') }}">Trang chủ</a>
                <a class="text-white/50 hover:text-white transition-all duration-300" href="{{ route('laptops.index') }}">Sản phẩm</a>
                @foreach($categories ?? [] as $category)
                <a class="text-white/50 hover:text-white transition-all duration-300" href="{{ route('laptops.index', ['category' => $category->slug]) }}">{{ $category->name }}</a>
                @endforeach
            </nav>
            <div class="flex items-center gap-6">
                <div class="relative hidden lg:block">
                    <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-white/30 text-lg">search</span>
                    <form action="{{ route('laptops.index') }}" method="GET">
                        <input name="search" class="bg-white/[0.03] border border-white/[0.08] rounded-full py-2.5 pl-11 pr-6 text-sm focus:outline-none focus:border-primary/50 transition-all w-72 placeholder:text-white/20" placeholder="Tìm kiếm laptop..." type="text"/>
                    </form>
                </div>
                <div class="flex gap-2 items-center">
                    <a href="{{ route('cart.index') }}" class="w-10 h-10 flex items-center justify-center rounded-full hover:bg-white/5 transition-all text-primary/80 hover:text-primary relative">
                        <span class="material-symbols-outlined">shopping_cart</span>
                        @if(session('cart'))
                            <span class="absolute -top-1 -right-1 bg-secondary text-white text-xs w-5 h-5 rounded-full flex items-center justify-center font-bold">{{ count(session('cart')) }}</span>
                        @endif
                    </a>
                    @auth
                        <div class="relative group">
                            <button class="flex items-center gap-2 px-4 py-2 rounded-full bg-white/5 hover:bg-white/10 transition-all">
                                <span class="material-symbols-outlined text-primary">account_circle</span>
                                <span class="text-white font-medium text-sm">{{ auth()->user()->name }}</span>
                                <span class="material-symbols-outlined text-white/50 text-sm">expand_more</span>
                            </button>
                            <div class="absolute right-0 mt-2 w-48 bg-neutral-900 rounded-lg shadow-xl border border-white/10 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                                @if(auth()->user()->isAdmin())
                                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 hover:bg-white/5 transition-all text-white/80 hover:text-white">
                                        <span class="material-symbols-outlined text-lg">dashboard</span>
                                        <span class="text-sm">Dashboard</span>
                                    </a>
                                @endif
                                <a href="#" class="flex items-center gap-3 px-4 py-3 hover:bg-white/5 transition-all text-white/80 hover:text-white">
                                    <span class="material-symbols-outlined text-lg">person</span>
                                    <span class="text-sm">Tài khoản</span>
                                </a>
                                <a href="{{ route('order.myOrders') }}" class="flex items-center gap-3 px-4 py-3 hover:bg-white/5 transition-all text-white/80 hover:text-white">
                                    <span class="material-symbols-outlined text-lg">receipt_long</span>
                                    <span class="text-sm">Đơn hàng</span>
                                </a>
                                <div class="border-t border-white/10 my-1"></div>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 hover:bg-red-500/10 transition-all text-red-400 hover:text-red-300">
                                        <span class="material-symbols-outlined text-lg">logout</span>
                                        <span class="text-sm">Đăng xuất</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="px-6 py-2 rounded-full bg-white/5 hover:bg-white/10 transition-all text-white font-bold text-sm">
                            Đăng nhập
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    @if(session('success'))
        <div class="fixed top-24 right-8 z-50 glass p-4 rounded-xl border-l-4 border-primary animate-pulse">
            <p class="text-sm font-bold">{{ session('success') }}</p>
        </div>
    @endif

    @yield('content')

    <!-- Footer -->
    <footer class="bg-surface pt-40 pb-16 border-t border-white/[0.05]">
        <div class="px-8 max-w-7xl mx-auto">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-20 mb-24">
                <div class="md:col-span-5">
                    <div class="text-primary font-black text-4xl mb-8 font-headline tracking-tighter">NEON KINETIC</div>
                    <p class="text-white/50 font-medium text-sm max-w-sm mb-12 body-relaxed">Kiến tạo tiêu chuẩn mới cho máy trạm chuyên nghiệp. Nơi hiệu năng đỉnh cao giao thoa cùng thẩm mỹ tinh tế của tương lai.</p>
                </div>
                <div class="md:col-span-3 space-y-8">
                    <h4 class="font-headline font-bold text-white uppercase tracking-[0.2em] text-xs">Hỗ trợ</h4>
                    <nav class="flex flex-col gap-6">
                        <a class="text-white/50 text-sm font-medium hover:text-primary transition-colors" href="#">Trung tâm bảo hành</a>
                        <a class="text-white/50 text-sm font-medium hover:text-primary transition-colors" href="#">Chính sách đổi trả</a>
                        <a class="text-white/50 text-sm font-medium hover:text-primary transition-colors" href="#">Hướng dẫn mua hàng</a>
                    </nav>
                </div>
            </div>
            <div class="pt-12 border-t border-white/[0.05] text-center">
                <p class="text-white/30 text-[10px] font-bold uppercase tracking-[0.3em]">© 2026 NEON KINETIC. Engineered for Performance.</p>
            </div>
        </div>
    </footer>

    @yield('scripts')
</body>
</html>
