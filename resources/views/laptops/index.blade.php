@extends('layouts.app')

@section('title', 'Danh sách Laptop - NEON KINETIC')

@section('content')
<main class="max-w-screen-2xl mx-auto flex flex-col lg:flex-row min-h-screen">
    <!-- SideNavBar (Filters) -->
    <aside class="bg-[#0e0e0e] h-[calc(100vh-80px)] w-72 border-r border-white/5 hidden lg:flex flex-col gap-8 p-8 sticky top-20">
        <div class="space-y-1">
            <h2 class="text-white/90 font-bold uppercase tracking-widest font-headline text-xs">Phân loại</h2>
            <p class="text-white/30 text-[10px] font-medium uppercase tracking-tighter">Refine your machine</p>
        </div>
        
        <nav class="flex flex-col gap-1.5">
            <a href="{{ route('laptops.index') }}" class="flex items-center gap-3 p-3 {{ !request('category') ? 'text-[#ff7524] bg-[#ff7524]/5 border border-[#ff7524]/20' : 'text-white/50 hover:text-white/90 hover:bg-white/5' }} rounded-lg cursor-pointer transition-all text-sm">
                <span class="material-symbols-outlined text-[18px]">laptop_mac</span>
                <span>Tất cả</span>
            </a>
            
            @foreach($categories as $category)
            <a href="{{ route('laptops.index', ['category' => $category->slug]) }}" class="flex items-center gap-3 p-3 {{ request('category') == $category->slug ? 'text-[#ff7524] bg-[#ff7524]/5 border border-[#ff7524]/20' : 'text-white/50 hover:text-white/90 hover:bg-white/5' }} rounded-lg cursor-pointer transition-all text-sm group">
                <span class="material-symbols-outlined text-[18px] group-hover:text-primary transition-colors">branding_watermark</span>
                <span>{{ $category->name }}</span>
            </a>
            @endforeach
        </nav>

        <div class="mt-auto p-5 glass-panel rounded-xl ultra-thin-border">
            <span class="text-[10px] uppercase tracking-widest text-primary block mb-2 font-bold">Hỗ trợ kỹ thuật</span>
            <p class="text-xs text-white/50 leading-relaxed font-medium">Đội ngũ chuyên gia luôn sẵn sàng tư vấn cấu hình phù hợp nhất cho bạn.</p>
        </div>
    </aside>

    <!-- Content Area -->
    <section class="flex-1 p-8 lg:p-12">
        <!-- Hero Banner -->
        <div class="relative w-full h-[450px] rounded-2xl overflow-hidden mb-16 shadow-2xl group ultra-thin-border">
            <img class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-105" src="https://images.unsplash.com/photo-1593640408182-31c70c8268f5?w=1600&h=900&fit=crop" alt="Gaming Laptop Banner"/>
            <div class="absolute inset-0 bg-gradient-to-r from-black/80 via-black/30 to-transparent flex flex-col justify-center px-12">
                <div class="flex items-center gap-3 mb-6">
                    <span class="h-[1px] w-12 bg-secondary"></span>
                    <span class="text-secondary font-bold text-[10px] tracking-[0.4em] uppercase">Power Unleashed</span>
                </div>
                <h1 class="text-5xl md:text-7xl font-black font-headline tracking-tighter leading-[0.9] mb-8">
                    LAPTOP GAMING<br/>
                    <span class="text-[#00F5FF]">CHIẾN GAME CỰC ĐỈNH</span>
                </h1>
                <a href="{{ route('laptops.index', ['category' => 'gaming']) }}" class="bg-secondary text-white w-fit px-10 py-4 rounded-full font-bold hover:shadow-[0_0_30px_rgba(255,117,36,0.3)] transition-all flex items-center gap-3 group">
                    <span class="text-sm">Khám Phá Ngay</span>
                    <span class="material-symbols-outlined group-hover:translate-x-1 transition-transform text-[20px]">arrow_forward</span>
                </a>
            </div>
        </div>

        <!-- Product Grid Header -->
        <div class="flex justify-between items-end mb-12">
            <div>
                <h2 class="text-4xl font-headline font-bold mb-3 tracking-tight">Sản Phẩm Nổi Bật</h2>
                <p class="text-white/40 text-sm">Tìm kiếm cỗ máy chiến tranh hoàn hảo của bạn</p>
            </div>
            <div class="flex gap-4">
                <form action="{{ route('laptops.index') }}" method="GET" class="hidden md:block">
                    @if(request('category'))
                        <input type="hidden" name="category" value="{{ request('category') }}">
                    @endif
                    <div class="flex gap-2">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Tìm kiếm..." class="bg-[#131313] border border-white/10 rounded-full px-4 py-2 text-sm text-white placeholder:text-white/30 focus:outline-none focus:border-primary/50">
                        <button type="submit" class="bg-primary text-black px-6 py-2 rounded-full text-xs font-bold hover:shadow-[0_0_20px_rgba(0,245,255,0.3)] transition-all">
                            Tìm
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Product Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8">
            @forelse($laptops as $laptop)
            <div class="bg-[#131313] rounded-2xl p-6 transition-all duration-500 ultra-thin-border product-card-hover group cursor-pointer flex flex-col">
                <a href="{{ route('laptops.show', $laptop->slug) }}" class="aspect-video rounded-xl overflow-hidden mb-8 relative bg-black/40">
                    <img class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" src="{{ $laptop->image ?? 'https://via.placeholder.com/600x400?text=Laptop' }}" alt="{{ $laptop->name }}"/>
                    @if($laptop->stock > 0)
                        <div class="absolute top-4 left-4">
                            <span class="bg-gradient-to-r from-tertiary-dim to-tertiary px-3 py-1 rounded-full text-[10px] font-black text-white uppercase tracking-widest shadow-lg">Còn hàng</span>
                        </div>
                    @else
                        <div class="absolute top-4 left-4">
                            <span class="bg-error px-3 py-1 rounded-full text-[10px] font-black text-white uppercase tracking-widest shadow-lg">Hết hàng</span>
                        </div>
                    @endif
                </a>
                
                <a href="{{ route('laptops.show', $laptop->slug) }}">
                    <h3 class="text-xl font-bold mb-2 group-hover:text-primary transition-colors font-headline tracking-tight">{{ $laptop->name }}</h3>
                </a>
                
                <div class="text-[#ff7524] text-2xl font-black mb-6 tracking-tighter">{{ number_format($laptop->price, 0, ',', '.') }}₫</div>
                
                <div class="space-y-3 mb-8 flex-1">
                    <div class="flex items-center gap-3 text-white/50 group-hover:text-white/70 transition-colors">
                        <span class="material-symbols-outlined text-[16px]">memory</span>
                        <span class="text-xs font-medium">CPU: {{ $laptop->processor }}</span>
                    </div>
                    <div class="flex items-center gap-3 text-white/50 group-hover:text-white/70 transition-colors">
                        <span class="material-symbols-outlined text-[16px]">developer_board</span>
                        <span class="text-xs font-medium">RAM: {{ $laptop->ram }}</span>
                    </div>
                    <div class="flex items-center gap-3 text-white/50 group-hover:text-white/70 transition-colors">
                        <span class="material-symbols-outlined text-[16px]">storage</span>
                        <span class="text-xs font-medium">SSD: {{ $laptop->storage }}</span>
                    </div>
                </div>
                
                <div class="flex items-center gap-2 mb-4">
                    <div class="bg-white/5 px-3 py-1.5 rounded-lg text-[9px] font-bold text-white/60 uppercase tracking-widest border border-white/5">{{ $laptop->brand }}</div>
                    @if($laptop->graphics)
                        <div class="bg-white/5 px-3 py-1.5 rounded-lg text-[9px] font-bold text-white/60 uppercase tracking-widest border border-white/5">{{ Str::limit($laptop->graphics, 15) }}</div>
                    @endif
                </div>

                @if($laptop->stock > 0)
                <form action="{{ route('cart.add', $laptop->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full bg-primary/10 hover:bg-primary text-primary hover:text-black border border-primary/20 hover:border-primary py-3 rounded-xl font-bold text-sm transition-all">
                        Thêm vào giỏ hàng
                    </button>
                </form>
                @else
                <button disabled class="w-full bg-white/5 text-white/30 border border-white/5 py-3 rounded-xl font-bold text-sm cursor-not-allowed">
                    Hết hàng
                </button>
                @endif
            </div>
            @empty
            <div class="col-span-full text-center py-20">
                <span class="material-symbols-outlined text-white/20 text-6xl mb-4">search_off</span>
                <p class="text-white/40 text-lg">Không tìm thấy sản phẩm nào</p>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($laptops->hasPages())
        <div class="mt-20 flex justify-center items-center gap-2">
            @if($laptops->onFirstPage())
                <button disabled class="w-10 h-10 rounded-full flex items-center justify-center text-white/10 cursor-not-allowed">
                    <span class="material-symbols-outlined text-[20px]">chevron_left</span>
                </button>
            @else
                <a href="{{ $laptops->previousPageUrl() }}" class="w-10 h-10 rounded-full flex items-center justify-center text-white/30 hover:text-white hover:bg-white/5 transition-all">
                    <span class="material-symbols-outlined text-[20px]">chevron_left</span>
                </a>
            @endif

            @foreach($laptops->getUrlRange(1, $laptops->lastPage()) as $page => $url)
                @if($page == $laptops->currentPage())
                    <button class="w-10 h-10 rounded-full bg-[#00F5FF] text-black font-bold text-sm">{{ $page }}</button>
                @else
                    <a href="{{ $url }}" class="w-10 h-10 rounded-full text-white/50 hover:text-white hover:bg-white/5 transition-all font-bold text-sm flex items-center justify-center">{{ $page }}</a>
                @endif
            @endforeach

            @if($laptops->hasMorePages())
                <a href="{{ $laptops->nextPageUrl() }}" class="w-10 h-10 rounded-full flex items-center justify-center text-white/30 hover:text-white hover:bg-white/5 transition-all">
                    <span class="material-symbols-outlined text-[20px]">chevron_right</span>
                </a>
            @else
                <button disabled class="w-10 h-10 rounded-full flex items-center justify-center text-white/10 cursor-not-allowed">
                    <span class="material-symbols-outlined text-[20px]">chevron_right</span>
                </button>
            @endif
        </div>
        @endif
    </section>
</main>

<style>
    .glass-panel {
        background: rgba(38, 38, 38, 0.4);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.05);
    }
    .ultra-thin-border {
        border: 0.5px solid rgba(255, 255, 255, 0.08);
    }
    .product-card-hover:hover {
        box-shadow: 0 0 40px -10px rgba(0, 245, 255, 0.15);
        border-color: rgba(0, 245, 255, 0.3);
    }
</style>
@endsection
