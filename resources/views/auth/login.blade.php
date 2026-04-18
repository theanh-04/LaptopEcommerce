<!DOCTYPE html>
<html class="dark" lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Neon Kinetic | Đăng Nhập</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&family=Manrope:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#a1faff",
                        "primary-dim": "#00e5ee",
                        "secondary": "#ff7524",
                        "secondary-dim": "#ffb28d",
                        "background": "#0e0e0e",
                        "surface": "#0e0e0e",
                        "surface-container-lowest": "#000000",
                        "surface-container-high": "#20201f",
                        "surface-container-highest": "#262626",
                        "on-surface": "#ffffff",
                        "on-surface-variant": "#adaaaa",
                        "outline": "#767575",
                        "outline-variant": "#484847"
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
        body { font-family: 'Manrope', sans-serif; }
        h1, h2, h3, .brand-font { font-family: 'Space Grotesk', sans-serif; }
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        .mesh-gradient {
            background-image: radial-gradient(at 0% 0%, rgba(161, 250, 255, 0.08) 0px, transparent 50%),
                            radial-gradient(at 100% 100%, rgba(255, 117, 36, 0.08) 0px, transparent 50%);
        }
        .glass-panel {
            background: rgba(38, 38, 38, 0.4);
            backdrop-filter: blur(40px);
            -webkit-backdrop-filter: blur(40px);
        }
    </style>
</head>
<body class="bg-background text-on-surface min-h-screen flex flex-col items-center justify-center overflow-hidden mesh-gradient relative">
    <div class="absolute inset-0 z-0 opacity-20 pointer-events-none">
        <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] bg-primary/20 rounded-full blur-[120px]"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[40%] h-[40%] bg-secondary/20 rounded-full blur-[120px]"></div>
    </div>

    <main class="z-10 w-full max-w-[1200px] px-6 py-12 flex flex-col items-center">
        <div class="mb-12 text-center">
            <h1 class="text-4xl md:text-5xl font-bold tracking-tighter text-primary-dim brand-font">Neon Kinetic</h1>
            <p class="text-on-surface-variant font-medium mt-2">Đăng nhập vào hệ thống quản lý thiết bị cao cấp</p>
        </div>

        <div class="glass-panel w-full max-w-[480px] p-8 md:p-12 rounded-xl border border-outline-variant/15 shadow-2xl relative group">
            <header class="mb-10">
                <h2 class="text-3xl font-bold tracking-tight text-on-surface">Chào mừng trở lại</h2>
                <p class="text-on-surface-variant text-sm mt-2">Vui lòng nhập thông tin tài khoản của bạn.</p>
            </header>

            <form action="{{ route('login.post') }}" method="POST" class="space-y-6">
                @csrf
                
                <div class="space-y-2">
                    <label class="text-xs uppercase tracking-widest font-bold text-secondary-dim px-1" for="email">Email</label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant text-lg">mail</span>
                        <input name="email" value="{{ old('email') }}" required autofocus class="w-full bg-surface-container-lowest border border-outline-variant/20 rounded-lg py-4 pl-12 pr-4 text-on-surface placeholder:text-outline focus:outline-none focus:ring-2 focus:ring-primary/40 focus:border-primary transition-all duration-300" id="email" placeholder="name@neonkinetic.com" type="email"/>
                    </div>
                    @error('email')<p class="text-red-400 text-xs mt-1 px-1">{{ $message }}</p>@enderror
                </div>

                <div class="space-y-2">
                    <div class="flex justify-between items-center px-1">
                        <label class="text-xs uppercase tracking-widest font-bold text-secondary-dim" for="password">Mật khẩu</label>
                    </div>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant text-lg">lock</span>
                        <input name="password" required class="w-full bg-surface-container-lowest border border-outline-variant/20 rounded-lg py-4 pl-12 pr-4 text-on-surface placeholder:text-outline focus:outline-none focus:ring-2 focus:ring-primary/40 focus:border-primary transition-all duration-300" id="password" placeholder="••••••••" type="password"/>
                    </div>
                    @error('password')<p class="text-red-400 text-xs mt-1 px-1">{{ $message }}</p>@enderror
                </div>

                <div class="flex items-center space-x-2 px-1">
                    <input name="remember" class="w-5 h-5 rounded border-outline-variant/20 bg-surface-container-lowest text-secondary focus:ring-secondary/40 transition-all cursor-pointer" id="remember" type="checkbox"/>
                    <label class="text-sm text-on-surface-variant cursor-pointer select-none" for="remember">Ghi nhớ đăng nhập</label>
                </div>

                <button class="w-full bg-secondary hover:bg-secondary/90 text-white font-bold py-4 rounded-full shadow-[0_0_20px_rgba(255,117,36,0.2)] hover:shadow-[0_0_30px_rgba(255,117,36,0.4)] transform active:scale-95 transition-all duration-300 flex items-center justify-center gap-2 group" type="submit">
                    <span>Đăng nhập</span>
                    <span class="material-symbols-outlined group-hover:translate-x-1 transition-transform">arrow_forward</span>
                </button>
            </form>

            <footer class="mt-10 text-center">
                <p class="text-sm text-on-surface-variant">
                    Chưa có tài khoản? 
                    <a class="text-primary-dim font-bold hover:underline decoration-primary-dim/30 underline-offset-4" href="{{ route('register') }}">Đăng ký ngay</a>
                </p>
            </footer>
        </div>

        <div class="mt-12 flex items-center gap-8 text-outline text-sm font-medium tracking-wide">
            <a class="hover:text-primary transition-colors" href="{{ route('home') }}">Trang chủ</a>
            <a class="hover:text-primary transition-colors" href="#">Hỗ trợ</a>
        </div>
    </main>

    <div class="fixed bottom-10 left-10 pointer-events-none opacity-40 mix-blend-screen hidden lg:block">
        <div class="w-64 h-64 border-[0.5px] border-primary/20 rounded-full flex items-center justify-center animate-[pulse_8s_infinite]">
            <div class="w-48 h-48 border-[0.5px] border-secondary/20 rounded-full flex items-center justify-center animate-[pulse_6s_infinite]">
                <div class="w-32 h-32 border-[0.5px] border-primary/20 rounded-full"></div>
            </div>
        </div>
    </div>
</body>
</html>
