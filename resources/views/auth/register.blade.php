<!DOCTYPE html>
<html class="dark" lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký - Neon Kinetic</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700;800;900&family=Manrope:wght@200;300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#00F5FF",
                        "secondary": "#ff7524",
                        "background": "#0e0e0e"
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
        body {
            background-color: #0e0e0e;
            font-family: 'Manrope', sans-serif;
        }
        .glass-panel {
            background: rgba(38, 38, 38, 0.45);
            backdrop-filter: blur(40px);
            -webkit-backdrop-filter: blur(40px);
        }
        .neon-glow-cyan {
            box-shadow: 0 0 20px rgba(161, 250, 255, 0.3);
        }
        .neon-glow-cyan:hover {
            box-shadow: 0 0 30px rgba(161, 250, 255, 0.5);
        }
        .input-focus-glow:focus-within {
            box-shadow: 0 0 15px rgba(161, 250, 255, 0.15);
        }
        .kinetic-bg {
            background: radial-gradient(circle at 20% 30%, rgba(0, 244, 254, 0.05) 0%, transparent 40%),
                        radial-gradient(circle at 80% 70%, rgba(255, 117, 36, 0.05) 0%, transparent 40%);
        }
    </style>
</head>
<body class="bg-background text-white min-h-screen flex items-center justify-center p-6 kinetic-bg relative overflow-x-hidden">
    <!-- Background Decor Elements -->
    <div class="absolute top-[-10%] right-[-10%] w-[500px] h-[500px] bg-cyan-500/5 blur-[120px] rounded-full"></div>
    <div class="absolute bottom-[-10%] left-[-10%] w-[400px] h-[400px] bg-orange-500/5 blur-[100px] rounded-full"></div>

    <main class="w-full max-w-6xl flex flex-col md:flex-row items-center gap-12 lg:gap-24 z-10">
        <!-- Brand Section (Left Side) -->
        <div class="hidden md:flex flex-col max-w-md">
            <div class="mb-8">
                <span class="text-secondary font-headline font-bold tracking-widest text-sm uppercase">Neon Kinetic System</span>
                <h1 class="text-white font-headline text-6xl font-extrabold tracking-tighter mt-4 leading-none">
                    JOIN THE <br/>
                    <span class="text-primary italic">EVOLUTION.</span>
                </h1>
            </div>
            <p class="text-gray-400 text-lg leading-relaxed mb-10 font-light">
                Trải nghiệm hiệu năng không giới hạn. Đăng ký ngay để nhận ưu đãi đặc quyền cho các dòng Workstation và Gaming Gear thế hệ mới.
            </p>
            
            <!-- Technical Specs Display -->
            <div class="grid grid-cols-2 gap-4">
                <div class="p-6 bg-neutral-900/50 rounded-lg">
                    <span class="material-symbols-outlined text-primary mb-2">speed</span>
                    <p class="text-xs text-gray-400 uppercase font-bold tracking-widest">Hiệu năng</p>
                    <p class="text-white font-headline font-bold text-xl">Extreme</p>
                </div>
                <div class="p-6 bg-neutral-900/50 rounded-lg">
                    <span class="material-symbols-outlined text-secondary mb-2">shield</span>
                    <p class="text-xs text-gray-400 uppercase font-bold tracking-widest">Bảo mật</p>
                    <p class="text-white font-headline font-bold text-xl">Lớp Kép</p>
                </div>
            </div>
        </div>

        <!-- Sign Up Card (Right Side) -->
        <div class="w-full max-w-md glass-panel rounded-xl p-8 md:p-12 shadow-2xl relative">
            <!-- Subtle logo for mobile -->
            <div class="md:hidden mb-8 flex items-center gap-2">
                <div class="w-8 h-8 bg-primary rounded-sm rotate-45"></div>
                <span class="font-headline font-bold text-xl tracking-tighter">NEON KINETIC</span>
            </div>

            <header class="mb-10">
                <h2 class="text-3xl font-headline font-bold text-white mb-2 tracking-tight">Tạo tài khoản</h2>
                <p class="text-gray-400 text-sm">Bắt đầu hành trình công nghệ của bạn ngay hôm nay.</p>
            </header>

            <form action="{{ route('register.post') }}" method="POST" class="space-y-6">
                @csrf
                
                <!-- Name Field -->
                <div class="space-y-2 group">
                    <label class="text-xs font-bold text-gray-400 uppercase tracking-widest ml-1" for="name">Họ và tên</label>
                    <div class="relative flex items-center bg-black/50 rounded-lg overflow-hidden transition-all duration-300 input-focus-glow border border-transparent focus-within:border-primary/30">
                        <span class="material-symbols-outlined ml-4 text-gray-400 text-xl">person</span>
                        <input name="name" value="{{ old('name') }}" required class="w-full bg-transparent border-none focus:ring-0 text-white py-4 px-4 placeholder:text-neutral-700 text-sm" id="name" placeholder="Nguyễn Văn A" type="text"/>
                    </div>
                    @error('name')<p class="text-red-400 text-xs mt-1 ml-1">{{ $message }}</p>@enderror
                </div>

                <!-- Email Field -->
                <div class="space-y-2 group">
                    <label class="text-xs font-bold text-gray-400 uppercase tracking-widest ml-1" for="email">Email</label>
                    <div class="relative flex items-center bg-black/50 rounded-lg overflow-hidden transition-all duration-300 input-focus-glow border border-transparent focus-within:border-primary/30">
                        <span class="material-symbols-outlined ml-4 text-gray-400 text-xl">alternate_email</span>
                        <input name="email" value="{{ old('email') }}" required class="w-full bg-transparent border-none focus:ring-0 text-white py-4 px-4 placeholder:text-neutral-700 text-sm" id="email" placeholder="example@kinetic.com" type="email"/>
                    </div>
                    @error('email')<p class="text-red-400 text-xs mt-1 ml-1">{{ $message }}</p>@enderror
                </div>

                <!-- Password Field -->
                <div class="space-y-2 group">
                    <label class="text-xs font-bold text-gray-400 uppercase tracking-widest ml-1" for="password">Mật khẩu</label>
                    <div class="relative flex items-center bg-black/50 rounded-lg overflow-hidden transition-all duration-300 input-focus-glow border border-transparent focus-within:border-primary/30">
                        <span class="material-symbols-outlined ml-4 text-gray-400 text-xl">lock</span>
                        <input name="password" required class="w-full bg-transparent border-none focus:ring-0 text-white py-4 px-4 placeholder:text-neutral-700 text-sm" id="password" placeholder="••••••••" type="password"/>
                    </div>
                    @error('password')<p class="text-red-400 text-xs mt-1 ml-1">{{ $message }}</p>@enderror
                </div>

                <!-- Confirm Password Field -->
                <div class="space-y-2 group">
                    <label class="text-xs font-bold text-gray-400 uppercase tracking-widest ml-1" for="password_confirmation">Xác nhận mật khẩu</label>
                    <div class="relative flex items-center bg-black/50 rounded-lg overflow-hidden transition-all duration-300 input-focus-glow border border-transparent focus-within:border-primary/30">
                        <span class="material-symbols-outlined ml-4 text-gray-400 text-xl">shield_lock</span>
                        <input name="password_confirmation" required class="w-full bg-transparent border-none focus:ring-0 text-white py-4 px-4 placeholder:text-neutral-700 text-sm" id="password_confirmation" placeholder="••••••••" type="password"/>
                    </div>
                </div>

                <!-- Submit Button -->
                <button class="w-full bg-primary text-black font-headline font-bold py-5 rounded-full neon-glow-cyan transform active:scale-95 transition-all duration-300 flex items-center justify-center gap-2 group" type="submit">
                    <span>ĐĂNG KÝ</span>
                    <span class="material-symbols-outlined transition-transform group-hover:translate-x-1">arrow_forward</span>
                </button>
            </form>

            <!-- Footer Links -->
            <footer class="mt-10 text-center">
                <p class="text-gray-400 text-sm">
                    Đã có tài khoản? 
                    <a class="text-secondary font-bold hover:text-orange-400 transition-colors ml-1" href="{{ route('login') }}">Đăng nhập ngay</a>
                </p>
            </footer>
        </div>
    </main>

    <!-- Decorative Text -->
    <div class="fixed right-0 top-0 bottom-0 w-1/4 hidden xl:block opacity-20 pointer-events-none">
        <div class="h-full w-full relative overflow-hidden">
            <div class="absolute top-1/2 -translate-y-1/2 left-0 font-headline font-black text-[20rem] text-primary/10 leading-none select-none">NEON</div>
        </div>
    </div>
</body>
</html>
