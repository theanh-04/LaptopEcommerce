@extends('layouts.app')

@section('title', $laptop->name . ' - NEON KINETIC')

@section('styles')
<style>
    .image-reflection {
        position: relative;
    }
    .image-reflection::after {
        content: '';
        position: absolute;
        bottom: -20px;
        left: 5%;
        right: 5%;
        height: 40px;
        background: radial-gradient(ellipse at center, rgba(0, 245, 255, 0.15) 0%, transparent 70%);
        filter: blur(10px);
        z-index: -1;
    }
    .glow-secondary {
        box-shadow: 0 0 20px rgba(255, 117, 36, 0.3), 0 0 40px rgba(255, 117, 36, 0.1);
    }
    .glow-text-primary {
        text-shadow: 0 0 15px rgba(0, 245, 255, 0.4);
    }
    .spec-row:nth-child(even) {
        background-color: rgba(255, 255, 255, 0.02);
    }
</style>
@endsection

@section('content')
<main class="max-w-screen-2xl mx-auto px-8 py-12">
    <!-- Hero Product Section -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-16 mb-32">
        <!-- Left: Gallery -->
        <div class="lg:col-span-7 flex flex-col gap-8">
            <div class="bg-[#131313] rounded-2xl overflow-hidden relative group border border-white/5 image-reflection shadow-2xl" style="height: 600px;">
                <img alt="{{ $laptop->name }}" class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-105" src="{{ $laptop->image ?? 'https://via.placeholder.com/1200x800?text=Laptop' }}"/>
                @if($laptop->stock > 0)
                <div class="absolute top-8 left-8">
                    <span class="bg-primary/20 text-primary px-5 py-1.5 rounded-full text-[10px] font-black tracking-[0.2em] uppercase backdrop-blur-xl border border-primary/30">Còn hàng</span>
                </div>
                @else
                <div class="absolute top-8 left-8">
                    <span class="bg-error/20 text-error px-5 py-1.5 rounded-full text-[10px] font-black tracking-[0.2em] uppercase backdrop-blur-xl border border-error/30">Hết hàng</span>
                </div>
                @endif
                <div class="absolute inset-0 bg-gradient-to-t from-[#0e0e0e]/40 to-transparent pointer-events-none"></div>
            </div>
        </div>

        <!-- Right: Product Info -->
        <div class="lg:col-span-5 flex flex-col gap-8">
            <div class="flex flex-col gap-2">
                <h1 class="text-6xl font-extrabold tracking-tight leading-tight">{{ $laptop->name }}</h1>
                <div class="flex items-center gap-4">
                    <div class="flex text-secondary">
                        <span class="material-symbols-outlined text-[20px]" style="font-variation-settings: 'FILL' 1;">star</span>
                        <span class="material-symbols-outlined text-[20px]" style="font-variation-settings: 'FILL' 1;">star</span>
                        <span class="material-symbols-outlined text-[20px]" style="font-variation-settings: 'FILL' 1;">star</span>
                        <span class="material-symbols-outlined text-[20px]" style="font-variation-settings: 'FILL' 1;">star</span>
                        <span class="material-symbols-outlined text-[20px]" style="font-variation-settings: 'FILL' 1;">star_half</span>
                    </div>
                    <span class="text-white/50 text-sm font-semibold tracking-wide">{{ $laptop->category->name }}</span>
                </div>
            </div>

            <div class="text-5xl font-black text-primary tracking-tighter glow-text-primary">{{ number_format($laptop->price, 0, ',', '.') }}₫</div>

            <!-- Glassmorphism Specs -->
            <div class="glass-panel p-8 rounded-2xl flex flex-col gap-6 relative overflow-hidden border border-white/10">
                <div class="absolute -top-12 -right-12 w-48 h-48 bg-primary/10 blur-[80px]"></div>
                
                <div class="flex items-center gap-5">
                    <div class="bg-primary/10 p-3.5 rounded-xl border border-primary/20">
                        <span class="material-symbols-outlined text-primary text-[28px]">memory</span>
                    </div>
                    <div>
                        <div class="text-[10px] text-primary/60 font-black uppercase tracking-[0.2em] mb-0.5">Processor</div>
                        <div class="text-xl font-bold tracking-tight">{{ $laptop->processor }}</div>
                        <div class="text-xs text-white/50 font-medium">{{ $laptop->brand }}</div>
                    </div>
                </div>

                <div class="h-[1px] bg-white/5"></div>

                <div class="flex items-center gap-5">
                    <div class="bg-primary/10 p-3.5 rounded-xl border border-primary/20">
                        <span class="material-symbols-outlined text-primary text-[28px]">developer_board</span>
                    </div>
                    <div>
                        <div class="text-[10px] text-primary/60 font-black uppercase tracking-[0.2em] mb-0.5">Memory</div>
                        <div class="text-xl font-bold tracking-tight">{{ $laptop->ram }}</div>
                        <div class="text-xs text-white/50 font-medium">High-speed memory</div>
                    </div>
                </div>

                <div class="h-[1px] bg-white/5"></div>

                <div class="flex items-center gap-5">
                    <div class="bg-primary/10 p-3.5 rounded-xl border border-primary/20">
                        <span class="material-symbols-outlined text-primary text-[28px]">database</span>
                    </div>
                    <div>
                        <div class="text-[10px] text-primary/60 font-black uppercase tracking-[0.2em] mb-0.5">Storage</div>
                        <div class="text-xl font-bold tracking-tight">{{ $laptop->storage }}</div>
                        <div class="text-xs text-white/50 font-medium">Superfast NVMe performance</div>
                    </div>
                </div>
            </div>

            <div class="flex flex-col gap-4">
                @if($laptop->stock > 0)
                <form action="{{ route('cart.add', $laptop->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full bg-secondary text-white font-extrabold py-5 px-8 rounded-xl flex items-center justify-center gap-3 transition-all active:scale-95 group glow-secondary hover:brightness-110">
                        <span class="tracking-widest text-sm">MUA NGAY</span>
                        <span class="material-symbols-outlined transition-transform group-hover:translate-x-1">arrow_forward</span>
                    </button>
                </form>
                
                <form action="{{ route('cart.add', $laptop->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full bg-white/5 backdrop-blur-md border border-white/10 text-white font-bold py-5 px-8 rounded-xl flex items-center justify-center gap-3 hover:bg-white/10 transition-all active:scale-95">
                        <span class="material-symbols-outlined">add_shopping_cart</span>
                        <span class="tracking-widest text-sm">THÊM VÀO GIỎ HÀNG</span>
                    </button>
                </form>
                @else
                <button disabled class="w-full bg-white/5 text-white/30 border border-white/5 py-5 px-8 rounded-xl font-bold cursor-not-allowed">
                    <span class="tracking-widest text-sm">HẾT HÀNG</span>
                </button>
                @endif
            </div>

            <div class="grid grid-cols-3 gap-3">
                <div class="p-4 bg-[#131313] rounded-xl border border-white/5 flex flex-col items-center text-center group hover:bg-[#1a1a1a] transition-colors">
                    <span class="material-symbols-outlined text-primary/70 mb-2 text-xl">verified_user</span>
                    <div class="text-[10px] font-black uppercase tracking-widest text-white">12 Tháng</div>
                    <div class="text-[9px] text-white/50 font-bold">BẢO HÀNH</div>
                </div>
                <div class="p-4 bg-[#131313] rounded-xl border border-white/5 flex flex-col items-center text-center group hover:bg-[#1a1a1a] transition-colors">
                    <span class="material-symbols-outlined text-primary/70 mb-2 text-xl">local_shipping</span>
                    <div class="text-[10px] font-black uppercase tracking-widest text-white">Miễn phí</div>
                    <div class="text-[9px] text-white/50 font-bold">GIAO HÀNG</div>
                </div>
                <div class="p-4 bg-[#131313] rounded-xl border border-white/5 flex flex-col items-center text-center group hover:bg-[#1a1a1a] transition-colors">
                    <span class="material-symbols-outlined text-primary/70 mb-2 text-xl">published_with_changes</span>
                    <div class="text-[10px] font-black uppercase tracking-widest text-white">30 Ngày</div>
                    <div class="text-[9px] text-white/50 font-bold">ĐỔI TRẢ</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Description -->
    <div class="mb-32">
        <div class="flex items-center gap-6 mb-12">
            <h2 class="text-4xl font-black tracking-tight uppercase">Mô tả sản phẩm</h2>
            <div class="h-[2px] flex-1 bg-gradient-to-r from-primary/40 to-transparent"></div>
        </div>
        <div class="bg-[#131313] p-8 rounded-2xl border border-white/5">
            <p class="text-white/70 text-lg leading-relaxed">{{ $laptop->description }}</p>
        </div>
    </div>

    <!-- Detailed Specifications -->
    <div class="mb-32">
        <div class="flex items-center gap-6 mb-12">
            <h2 class="text-4xl font-black tracking-tight uppercase">Thông số chi tiết</h2>
            <div class="h-[2px] flex-1 bg-gradient-to-r from-primary/40 to-transparent"></div>
        </div>
        <div class="rounded-2xl border border-white/5 overflow-hidden">
            <div class="grid grid-cols-1 md:grid-cols-2">
                <div class="spec-row p-6 flex justify-between items-center border-b md:border-r border-white/5">
                    <span class="text-white/50 text-sm font-bold uppercase tracking-wider">Thương hiệu</span>
                    <span class="font-bold text-white">{{ $laptop->brand }}</span>
                </div>
                <div class="spec-row p-6 flex justify-between items-center border-b border-white/5">
                    <span class="text-white/50 text-sm font-bold uppercase tracking-wider">Bộ xử lý</span>
                    <span class="font-bold text-white">{{ $laptop->processor }}</span>
                </div>
                <div class="spec-row p-6 flex justify-between items-center border-b md:border-r border-white/5">
                    <span class="text-white/50 text-sm font-bold uppercase tracking-wider">RAM</span>
                    <span class="font-bold text-white">{{ $laptop->ram }}</span>
                </div>
                <div class="spec-row p-6 flex justify-between items-center border-b border-white/5">
                    <span class="text-white/50 text-sm font-bold uppercase tracking-wider">Ổ cứng</span>
                    <span class="font-bold text-white">{{ $laptop->storage }}</span>
                </div>
                <div class="spec-row p-6 flex justify-between items-center border-b md:border-r border-white/5">
                    <span class="text-white/50 text-sm font-bold uppercase tracking-wider">Màn hình</span>
                    <span class="font-bold text-white">{{ $laptop->display }}</span>
                </div>
                @if($laptop->graphics)
                <div class="spec-row p-6 flex justify-between items-center border-b border-white/5">
                    <span class="text-white/50 text-sm font-bold uppercase tracking-wider">Card đồ họa</span>
                    <span class="font-bold text-white">{{ $laptop->graphics }}</span>
                </div>
                @endif
                <div class="spec-row p-6 flex justify-between items-center md:border-r border-white/5">
                    <span class="text-white/50 text-sm font-bold uppercase tracking-wider">Tình trạng</span>
                    <span class="font-bold {{ $laptop->stock > 0 ? 'text-green-400' : 'text-error' }}">
                        {{ $laptop->stock > 0 ? 'Còn ' . $laptop->stock . ' sản phẩm' : 'Hết hàng' }}
                    </span>
                </div>
                <div class="spec-row p-6 flex justify-between items-center">
                    <span class="text-white/50 text-sm font-bold uppercase tracking-wider">Danh mục</span>
                    <span class="font-bold text-white">{{ $laptop->category->name }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Back to Products -->
    <div class="text-center">
        <a href="{{ route('laptops.index') }}" class="inline-flex items-center gap-3 text-primary font-bold hover:translate-x-[-4px] transition-transform">
            <span class="material-symbols-outlined">arrow_back</span>
            <span class="tracking-widest text-sm uppercase">Quay lại danh sách sản phẩm</span>
        </a>
    </div>
</main>
@endsection
