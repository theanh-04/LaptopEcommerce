@extends('layouts.app')

@section('title', 'Đổi mật khẩu - NEON KINETIC')

@section('content')
<main class="max-w-screen-2xl mx-auto px-6 py-12 lg:px-12">
    <!-- Header -->
    <header class="mb-16">
        <h1 class="text-5xl md:text-7xl font-headline font-black tracking-tighter text-white mb-6">
            ĐỔI <span class="text-primary italic">MẬT KHẨU</span>
        </h1>
        <p class="text-white/50 font-body text-lg max-w-2xl leading-relaxed">
            Cập nhật mật khẩu để bảo mật tài khoản của bạn.
        </p>
    </header>

    <div class="max-w-2xl mx-auto">
        <div class="bg-[#131313] rounded-2xl p-8 border border-white/5">
            <form action="{{ route('profile.changePassword.post') }}" method="POST">
                @csrf

                <div class="space-y-6">
                    <!-- Current Password -->
                    <div>
                        <label class="text-sm text-cyan-400 font-bold uppercase tracking-wider block mb-3">Mật khẩu hiện tại *</label>
                        <input type="password" name="current_password" required 
                               class="w-full bg-white text-black border border-white/10 rounded-xl px-6 py-4 focus:ring-2 focus:ring-primary/30 focus:border-primary/50 transition-all"
                               placeholder="Nhập mật khẩu hiện tại">
                        @error('current_password')
                            <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- New Password -->
                    <div>
                        <label class="text-sm text-cyan-400 font-bold uppercase tracking-wider block mb-3">Mật khẩu mới *</label>
                        <input type="password" name="new_password" required 
                               class="w-full bg-white text-black border border-white/10 rounded-xl px-6 py-4 focus:ring-2 focus:ring-primary/30 focus:border-primary/50 transition-all"
                               placeholder="Nhập mật khẩu mới (tối thiểu 6 ký tự)">
                        @error('new_password')
                            <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirm New Password -->
                    <div>
                        <label class="text-sm text-cyan-400 font-bold uppercase tracking-wider block mb-3">Xác nhận mật khẩu mới *</label>
                        <input type="password" name="new_password_confirmation" required 
                               class="w-full bg-white text-black border border-white/10 rounded-xl px-6 py-4 focus:ring-2 focus:ring-primary/30 focus:border-primary/50 transition-all"
                               placeholder="Nhập lại mật khẩu mới">
                    </div>
                </div>

                <!-- Security Tips -->
                <div class="mt-6 p-4 bg-primary/10 border border-primary/20 rounded-xl">
                    <h4 class="text-primary font-bold mb-2 flex items-center gap-2">
                        <span class="material-symbols-outlined text-sm">info</span>
                        Lưu ý bảo mật
                    </h4>
                    <ul class="text-white/70 text-sm space-y-1 ml-6 list-disc">
                        <li>Mật khẩu phải có ít nhất 6 ký tự</li>
                        <li>Nên sử dụng kết hợp chữ hoa, chữ thường, số và ký tự đặc biệt</li>
                        <li>Không sử dụng mật khẩu dễ đoán như ngày sinh, tên...</li>
                    </ul>
                </div>

                <!-- Buttons -->
                <div class="flex gap-4 mt-8">
                    <a href="{{ route('profile.index') }}" 
                       class="flex-1 px-6 py-4 bg-white/5 text-white rounded-xl font-bold hover:bg-white/10 transition-all text-center">
                        Hủy
                    </a>
                    <button type="submit" 
                            class="flex-1 px-6 py-4 bg-primary text-black rounded-xl font-bold hover:shadow-[0_0_20px_rgba(0,245,255,0.3)] transition-all">
                        Đổi mật khẩu
                    </button>
                </div>
            </form>
        </div>
    </div>
</main>
@endsection
