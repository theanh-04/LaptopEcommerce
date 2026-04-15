@extends('layouts.app')

@section('title', 'NEON KINETIC - Laptop Store')

@section('content')
<!-- Hero Section -->
<section class="relative min-h-[90vh] flex items-center overflow-hidden px-8 py-32">
    <div class="max-w-screen-2xl mx-auto w-full grid grid-cols-1 lg:grid-cols-12 gap-16 items-center">
        <div class="lg:col-span-6 z-10">
            <span class="text-secondary font-bold tracking-[0.4em] text-[10px] uppercase mb-6 block opacity-80">ULTIMATE PERFORMANCE SERIES</span>
            <h1 class="text-7xl md:text-[100px] font-headline font-black leading-[0.85] text-tight mb-10">
                CHINH PHỤC<br/>
                <span class="text-transparent bg-clip-text bg-gradient-to-br from-primary via-white to-tertiary">GIỚI HẠN</span>
            </h1>
            <p class="text-white/60 text-lg max-w-lg mb-12 body-relaxed font-medium">
                Trải nghiệm sức mạnh đột phá từ thế hệ vi xử lý tân tiến nhất. Ngôn ngữ thiết kế vị lai hòa quyện cùng hiệu năng không giới hạn.
            </p>
            <div class="flex flex-wrap gap-8">
                <a href="{{ route('laptops.index') }}" class="relative group">
                    <div class="absolute -inset-1 bg-secondary blur opacity-30 group-hover:opacity-60 transition duration-1000"></div>
                    <span class="relative bg-secondary text-white px-12 py-5 rounded-full font-bold text-lg block transition-transform group-active:scale-95">Mua Ngay</span>
                </a>
                <a href="{{ route('laptops.index') }}" class="glass px-12 py-5 rounded-full font-bold text-lg text-white hover:bg-white/10 transition-all active:scale-95">Khám Phá</a>
            </div>
        </div>
        <div class="lg:col-span-6 relative">
            <div class="relative w-full aspect-square flex items-center justify-center">
                <div class="absolute w-[140%] h-[140%] bg-primary/5 blur-[160px] rounded-full"></div>
                <div class="absolute w-[80%] h-[80%] bg-tertiary/5 blur-[120px] rounded-full translate-x-20"></div>
                @if($featuredLaptops->first())
                <img alt="{{ $featuredLaptops->first()->name }}" class="relative z-10 w-full h-auto object-contain drop-shadow-[0_40px_80px_rgba(0,0,0,0.8)] transform -rotate-3 hover:rotate-0 transition-transform duration-1000 ease-out" src="{{ $featuredLaptops->first()->image ?? 'https://via.placeholder.com/800x600?text=Laptop' }}"/>
                @endif
            </div>
        </div>
    </div>
</section>

<!-- Brand Section -->
<section class="py-32 border-y border-white/[0.05] bg-[#0a0a0a]/30">
    <div class="max-w-7xl mx-auto px-8">
        <div class="flex flex-wrap justify-between items-center gap-16 opacity-30 hover:opacity-100 grayscale hover:grayscale-0 transition-all duration-1000">
            @php
                $brands = $featuredLaptops->pluck('brand')->unique()->take(4);
            @endphp
            @foreach($brands as $brand)
            <span class="text-3xl font-black font-headline tracking-tighter uppercase">{{ $brand }}</span>
            @endforeach
        </div>
    </div>
</section>

<!-- Product Gallery -->
<section class="py-48 px-8">
    <div class="max-w-screen-2xl mx-auto">
        <div class="flex justify-between items-end mb-24">
            <div class="space-y-4">
                <h2 class="text-5xl md:text-6xl font-headline font-bold text-tight italic">BỘ SƯU TẬP NỔI BẬT</h2>
                <p class="text-white/60 max-w-md body-relaxed font-medium">Tuyển tập những kiệt tác công nghệ dành cho kỷ nguyên số hóa chuyên nghiệp.</p>
            </div>
            <a class="text-primary font-bold flex items-center gap-3 group text-sm uppercase tracking-widest" href="{{ route('laptops.index') }}">
                Xem tất cả <span class="material-symbols-outlined group-hover:translate-x-2 transition-transform duration-300">arrow_forward</span>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-10">
            @foreach($featuredLaptops->take(4) as $index => $laptop)
                @if($index === 0)
                <!-- Big Card -->
                <div class="md:col-span-2 md:row-span-2 glass p-12 rounded-3xl flex flex-col justify-between hover:bg-white/[0.05] transition-all duration-700 group relative overflow-hidden card-glow shadow-inner">
                    <div class="relative z-10">
                        <span class="bg-primary/10 text-primary text-[10px] px-4 py-1.5 rounded-full font-black tracking-widest mb-8 inline-block border border-primary/20 uppercase">Premium New</span>
                        <h3 class="text-5xl font-headline font-bold mb-6 text-tight">{{ $laptop->name }}</h3>
                        <p class="text-white/60 mb-10 max-w-xs body-relaxed">{{ Str::limit($laptop->description, 80) }}</p>
                        <div class="text-4xl font-headline font-bold text-secondary mb-12">{{ number_format($laptop->price, 0, ',', '.') }}đ</div>
                        <form action="{{ route('cart.add', $laptop->id) }}" method="POST" class="inline-block">
                            @csrf
                            <button type="submit" class="w-16 h-16 flex items-center justify-center rounded-full glass group-hover:bg-primary group-hover:text-black group-hover:border-primary transition-all duration-500">
                                <span class="material-symbols-outlined">add_shopping_cart</span>
                            </button>
                        </form>
                    </div>
                    <img alt="{{ $laptop->name }}" class="absolute -right-24 -bottom-16 w-4/5 transform group-hover:scale-105 group-hover:-rotate-3 transition-transform duration-1000 ease-out drop-shadow-[0_20px_40px_rgba(0,0,0,0.5)]" src="{{ $laptop->image ?? 'https://via.placeholder.com/600x400?text=Laptop' }}"/>
                </div>
                @elseif($index === 1)
                <!-- Medium Card -->
                <div class="md:col-span-2 glass p-10 rounded-3xl flex items-center justify-between hover:bg-white/[0.05] transition-all duration-700 group overflow-hidden card-glow">
                    <div class="max-w-[55%] z-10">
                        <h3 class="text-3xl font-headline font-bold mb-4 text-tight">{{ $laptop->name }}</h3>
                        <div class="text-2xl font-headline font-bold text-secondary mb-8">{{ number_format($laptop->price, 0, ',', '.') }}đ</div>
                        <div class="flex gap-3">
                            <span class="text-[10px] glass px-3 py-1.5 rounded-md text-white font-bold tracking-widest">{{ $laptop->processor }}</span>
                            <span class="text-[10px] glass px-3 py-1.5 rounded-md text-white font-bold tracking-widest">{{ $laptop->ram }}</span>
                        </div>
                    </div>
                    <img alt="{{ $laptop->name }}" class="w-1/2 transform translate-x-12 group-hover:translate-x-4 transition-transform duration-700 ease-out" src="{{ $laptop->image ?? 'https://via.placeholder.com/400x300?text=Laptop' }}"/>
                </div>
                @else
                <!-- Small Card -->
                <div class="glass p-8 rounded-3xl hover:bg-white/[0.05] transition-all duration-700 group card-glow">
                    <div class="overflow-hidden rounded-2xl mb-8 border border-white/5">
                        <img alt="{{ $laptop->name }}" class="w-full h-48 object-cover group-hover:scale-110 transition-transform duration-1000 ease-out" src="{{ $laptop->image ?? 'https://via.placeholder.com/400x300?text=Laptop' }}"/>
                    </div>
                    <h3 class="text-xl font-headline font-bold mb-2">{{ $laptop->name }}</h3>
                    <div class="text-secondary font-bold mb-6 text-lg">{{ number_format($laptop->price, 0, ',', '.') }}đ</div>
                    <a href="{{ route('laptops.show', $laptop->slug) }}" class="w-full py-4 rounded-xl glass text-xs font-black tracking-widest uppercase hover:bg-primary hover:text-black hover:border-primary transition-all block text-center">Chi tiết</a>
                </div>
                @endif
            @endforeach
        </div>
    </div>
</section>

<!-- Categories Section -->
<section class="py-32 px-8 border-t border-white/[0.05]">
    <div class="max-w-7xl mx-auto">
        <h2 class="text-4xl md:text-5xl font-headline font-bold text-center mb-16">Danh Mục Sản Phẩm</h2>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            @foreach($categories as $category)
            <a href="{{ route('laptops.index', ['category' => $category->slug]) }}" class="glass p-8 rounded-2xl hover:bg-white/[0.05] transition-all duration-500 group card-glow text-center">
                <div class="text-5xl mb-6 opacity-50 group-hover:opacity-100 transition-opacity">
                    <span class="material-symbols-outlined text-primary" style="font-size: 3rem;">laptop_mac</span>
                </div>
                <h3 class="text-2xl font-headline font-bold mb-3">{{ $category->name }}</h3>
                <p class="text-white/50 text-sm">{{ $category->description }}</p>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endsection
