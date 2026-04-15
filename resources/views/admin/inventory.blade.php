@extends('layouts.admin')

@section('title', 'Quản lý Sản phẩm')

@section('content')
<!-- Header Section -->
<div class="flex flex-col md:flex-row md:items-end justify-between mb-10 gap-6">
    <div>
        <h2 class="text-4xl font-extrabold tracking-tighter text-white mb-2 font-headline">Quản Lý Sản Phẩm</h2>
        <p class="text-neutral-400 max-w-lg font-body">Điều chỉnh danh mục kho vận, kiểm tra tình trạng tồn kho và cập nhật thông số kỹ thuật phần cứng cao cấp.</p>
    </div>
    <div class="flex gap-4">
        <div class="bg-surface-container-low px-6 py-3 rounded-lg flex items-center gap-4 border border-outline-variant/10">
            <div class="w-10 h-10 rounded-full bg-cyan-400/10 flex items-center justify-center text-cyan-400">
                <span class="material-symbols-outlined">inventory</span>
            </div>
            <div>
                <p class="text-xs text-neutral-500 uppercase font-bold tracking-wider">Tổng sản phẩm</p>
                <p class="text-xl font-bold font-headline">{{ $totalProducts ?? 1284 }}</p>
            </div>
        </div>
        <div class="bg-surface-container-low px-6 py-3 rounded-lg flex items-center gap-4 border border-outline-variant/10">
            <div class="w-10 h-10 rounded-full bg-orange-500/10 flex items-center justify-center text-orange-500">
                <span class="material-symbols-outlined">warning</span>
            </div>
            <div>
                <p class="text-xs text-neutral-500 uppercase font-bold tracking-wider">Sắp hết hàng</p>
                <p class="text-xl font-bold font-headline text-orange-500">{{ $lowStockCount ?? 12 }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Filter Bar Section -->
<div class="surface-container-low p-6 rounded-xl mb-8 flex flex-wrap items-center gap-6">
    <div class="flex-1 min-w-[200px]">
        <label class="block text-[10px] uppercase font-bold text-neutral-500 tracking-widest mb-2">Thương hiệu</label>
        <div class="flex gap-2">
            <button class="px-4 py-2 bg-surface-container-highest text-cyan-400 border border-cyan-400/30 rounded-lg text-sm font-bold font-headline">Apple</button>
            <button class="px-4 py-2 bg-surface-container-high text-neutral-400 hover:text-white transition-colors rounded-lg text-sm font-medium font-headline">ASUS</button>
            <button class="px-4 py-2 bg-surface-container-high text-neutral-400 hover:text-white transition-colors rounded-lg text-sm font-medium font-headline">MSI</button>
            <button class="px-4 py-2 bg-surface-container-high text-neutral-400 hover:text-white transition-colors rounded-lg text-sm font-medium font-headline">Razer</button>
        </div>
    </div>
    <div class="w-px h-10 bg-neutral-800/50 hidden md:block"></div>
    <div class="min-w-[180px]">
        <label class="block text-[10px] uppercase font-bold text-neutral-500 tracking-widest mb-2">Trạng thái kho</label>
        <select class="bg-surface-container-high border-0 rounded-lg text-sm text-on-surface focus:ring-1 focus:ring-cyan-400/50 w-full py-2 font-body">
            <option>Còn hàng (In Stock)</option>
            <option>Sắp hết (Low Stock)</option>
            <option>Hết hàng (Out of Stock)</option>
        </select>
    </div>
    <div class="w-px h-10 bg-neutral-800/50 hidden md:block"></div>
    <div class="flex items-center gap-2">
        <button class="p-2.5 bg-surface-container-high text-neutral-400 rounded-lg hover:text-white transition-colors active:scale-95">
            <span class="material-symbols-outlined">filter_list</span>
        </button>
        <button class="p-2.5 bg-surface-container-high text-neutral-400 rounded-lg hover:text-white transition-colors active:scale-95">
            <span class="material-symbols-outlined">grid_view</span>
        </button>
    </div>
</div>

<!-- Product Table (Bento Table) -->
<div class="bg-surface-container-low rounded-xl overflow-hidden border border-outline-variant/5">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-surface-container-high/50 border-b border-neutral-800/30">
                <th class="px-6 py-5 text-xs uppercase font-bold text-neutral-500 tracking-widest">Sản phẩm</th>
                <th class="px-6 py-5 text-xs uppercase font-bold text-neutral-500 tracking-widest">Thương hiệu</th>
                <th class="px-6 py-5 text-xs uppercase font-bold text-neutral-500 tracking-widest">Tồn kho</th>
                <th class="px-6 py-5 text-xs uppercase font-bold text-neutral-500 tracking-widest">Giá bán</th>
                <th class="px-6 py-5 text-xs uppercase font-bold text-neutral-500 tracking-widest text-right">Hành động</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-neutral-800/20">
            @foreach($products ?? [] as $product)
            <tr class="hover:bg-surface-container-high/30 transition-colors group {{ $product->stock == 0 ? 'opacity-60' : '' }}">
                <td class="px-6 py-5">
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 rounded-xl overflow-hidden bg-neutral-900 border border-outline-variant/10 group-hover:border-cyan-400/20 transition-all">
                            @if($product->image)
                            <img alt="{{ $product->name }}" class="w-full h-full object-cover opacity-80 group-hover:opacity-100 transition-opacity {{ $product->stock == 0 ? 'grayscale' : '' }}" src="{{ $product->image }}"/>
                            @else
                            <div class="w-full h-full bg-gradient-to-br from-primary/20 to-secondary/20 flex items-center justify-center">
                                <span class="material-symbols-outlined text-white">laptop</span>
                            </div>
                            @endif
                        </div>
                        <div>
                            <p class="font-bold font-headline text-white">{{ $product->name }}</p>
                            <p class="text-xs text-neutral-500">SKU: {{ $product->sku ?? 'N/A' }}</p>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-5">
                    <span class="px-3 py-1 bg-surface-container-highest text-white text-[10px] font-black rounded uppercase tracking-widest">{{ $product->brand }}</span>
                </td>
                <td class="px-6 py-5">
                    <div class="w-48">
                        <div class="flex justify-between items-center mb-1">
                            <span class="text-xs font-bold {{ $product->stock == 0 ? 'text-error' : ($product->stock < 10 ? 'text-orange-500' : 'text-neutral-400') }}">
                                {{ str_pad($product->stock, 2, '0', STR_PAD_LEFT) }}/100
                            </span>
                            <span class="text-[10px] font-bold uppercase {{ $product->stock == 0 ? 'text-error' : ($product->stock < 10 ? 'text-orange-500' : 'text-cyan-400') }}">
                                {{ $product->stock == 0 ? 'Hết hàng' : ($product->stock < 10 ? 'Sắp hết' : 'An toàn') }}
                            </span>
                        </div>
                        <div class="h-1.5 w-full bg-neutral-800 rounded-full overflow-hidden">
                            <div class="h-full {{ $product->stock == 0 ? 'bg-error shadow-[0_0_8px_#ff716c]' : ($product->stock < 10 ? 'bg-orange-500 shadow-[0_0_8px_#ff7524]' : 'bg-cyan-400 shadow-[0_0_8px_#a1faff]') }} rounded-full" style="width: {{ min($product->stock, 100) }}%"></div>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-5">
                    <p class="font-bold font-headline text-white">{{ number_format($product->price) }}₫</p>
                </td>
                <td class="px-6 py-5">
                    <div class="flex justify-end gap-2">
                        <button class="p-2 text-neutral-500 hover:text-cyan-400 hover:bg-cyan-400/10 rounded-lg transition-all">
                            <span class="material-symbols-outlined text-sm">edit</span>
                        </button>
                        <button class="p-2 text-neutral-500 hover:text-error hover:bg-error/10 rounded-lg transition-all">
                            <span class="material-symbols-outlined text-sm">delete</span>
                        </button>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <!-- Table Footer -->
    <div class="px-6 py-4 bg-surface-container-high/20 flex items-center justify-between">
        <p class="text-xs text-neutral-500 font-body">
            Hiển thị <span class="text-white font-bold">1 - 4</span> của <span class="text-white font-bold">{{ $totalProducts ?? 1284 }}</span> sản phẩm
        </p>
        <div class="flex gap-2">
            <button class="px-3 py-1.5 bg-surface-container-high text-neutral-400 rounded-lg hover:text-white transition-colors text-xs font-bold">Trước</button>
            <button class="px-3 py-1.5 bg-cyan-400 text-on-primary-container rounded-lg font-bold text-xs">1</button>
            <button class="px-3 py-1.5 bg-surface-container-high text-neutral-400 rounded-lg hover:text-white transition-colors text-xs font-bold">2</button>
            <button class="px-3 py-1.5 bg-surface-container-high text-neutral-400 rounded-lg hover:text-white transition-colors text-xs font-bold">3</button>
            <button class="px-3 py-1.5 bg-surface-container-high text-neutral-400 rounded-lg hover:text-white transition-colors text-xs font-bold">Tiếp</button>
        </div>
    </div>
</div>

<!-- Floating Action Button (FAB) -->
<button class="fixed bottom-10 right-10 bg-secondary text-on-secondary w-16 h-16 rounded-full flex items-center justify-center shadow-[0_0_30px_rgba(255,117,36,0.4)] hover:shadow-[0_0_50px_rgba(255,117,36,0.6)] hover:scale-110 active:scale-95 transition-all group z-50">
    <span class="material-symbols-outlined text-3xl font-bold">add</span>
    <!-- Tooltip -->
    <span class="absolute right-20 bg-secondary-container text-white px-4 py-2 rounded-lg text-sm font-bold font-headline whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none">
        Thêm Sản Phẩm Mới
    </span>
</button>
@endsection
