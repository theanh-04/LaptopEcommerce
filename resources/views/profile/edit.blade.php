@extends('layouts.app')

@section('title', 'Chỉnh sửa thông tin - NEON KINETIC')

@section('content')
<main class="max-w-screen-2xl mx-auto px-6 py-12 lg:px-12">
    <!-- Header -->
    <header class="mb-16">
        <h1 class="text-5xl md:text-7xl font-headline font-black tracking-tighter text-white mb-6">
            CHỈNH SỬA <span class="text-primary italic">THÔNG TIN</span>
        </h1>
        <p class="text-white/50 font-body text-lg max-w-2xl leading-relaxed">
            Cập nhật thông tin cá nhân của bạn.
        </p>
    </header>

    <div class="max-w-3xl mx-auto">
        <div class="bg-[#131313] rounded-2xl p-8 border border-white/5">
            <form action="{{ route('profile.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="space-y-6">
                    <!-- Name -->
                    <div>
                        <label class="text-sm text-cyan-400 font-bold uppercase tracking-wider block mb-3">Họ và tên *</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required 
                               class="w-full bg-white text-black border border-white/10 rounded-xl px-6 py-4 focus:ring-2 focus:ring-primary/30 focus:border-primary/50 transition-all">
                        @error('name')
                            <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="text-sm text-cyan-400 font-bold uppercase tracking-wider block mb-3">Email *</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" required 
                               class="w-full bg-white text-black border border-white/10 rounded-xl px-6 py-4 focus:ring-2 focus:ring-primary/30 focus:border-primary/50 transition-all">
                        @error('email')
                            <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Phone -->
                    <div>
                        <label class="text-sm text-cyan-400 font-bold uppercase tracking-wider block mb-3">Số điện thoại</label>
                        <input type="tel" name="phone" value="{{ old('phone', $user->phone) }}" 
                               class="w-full bg-white text-black border border-white/10 rounded-xl px-6 py-4 focus:ring-2 focus:ring-primary/30 focus:border-primary/50 transition-all"
                               placeholder="0912345678">
                        @error('phone')
                            <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Address -->
                    <div>
                        <label class="text-sm text-cyan-400 font-bold uppercase tracking-wider block mb-3">Địa chỉ</label>
                        <textarea name="address" rows="4" 
                                  class="w-full bg-white text-black border border-white/10 rounded-xl px-6 py-4 focus:ring-2 focus:ring-primary/30 focus:border-primary/50 transition-all"
                                  placeholder="Số nhà, tên đường, phường/xã, quận/huyện, tỉnh/thành phố">{{ old('address', $user->address) }}</textarea>
                        @error('address')
                            <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Buttons -->
                <div class="flex gap-4 mt-8">
                    <a href="{{ route('profile.index') }}" 
                       class="flex-1 px-6 py-4 bg-white/5 text-white rounded-xl font-bold hover:bg-white/10 transition-all text-center">
                        Hủy
                    </a>
                    <button type="submit" 
                            class="flex-1 px-6 py-4 bg-primary text-black rounded-xl font-bold hover:shadow-[0_0_20px_rgba(0,245,255,0.3)] transition-all">
                        Lưu thay đổi
                    </button>
                </div>
            </form>
        </div>
    </div>
</main>
@endsection
