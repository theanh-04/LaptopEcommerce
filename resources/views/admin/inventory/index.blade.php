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
<div class="bg-surface-container-low rounded-xl mb-8 overflow-hidden border border-outline-variant/5">
    <!-- Search & Actions Row (Top) -->
    <div class="p-6 border-b border-neutral-800/20 flex items-end gap-4">
        <div class="w-64">
            <label class="block text-[10px] uppercase font-bold text-neutral-500 tracking-widest mb-2">Trạng thái kho</label>
            <select id="stockStatusFilter" onchange="applyFilters()" class="bg-surface-container-high border border-outline-variant/10 rounded-lg text-sm text-white focus:ring-1 focus:ring-cyan-400/50 w-full py-2.5 px-3 font-body cursor-pointer" style="color-scheme: dark;">
                <option value="all">Tất cả</option>
                <option value="in_stock">Còn hàng (In Stock)</option>
                <option value="low_stock">Sắp hết (Low Stock)</option>
                <option value="out_of_stock">Hết hàng (Out of Stock)</option>
            </select>
        </div>
        
        <div class="flex-1 flex gap-2">
            <div class="relative flex-1">
                <label class="block text-[10px] uppercase font-bold text-neutral-500 tracking-widest mb-2 invisible">Search</label>
                <input id="searchInput" type="text" placeholder="Tìm kiếm sản phẩm, SKU, thương hiệu..." class="bg-white border border-outline-variant/10 rounded-lg text-sm text-gray-900 placeholder-neutral-400 focus:ring-1 focus:ring-cyan-400/50 py-2.5 pl-10 pr-4 font-body w-full" onkeypress="if(event.key === 'Enter') applyFilters()">
                <span class="material-symbols-outlined absolute left-3 bottom-3 text-neutral-400 text-sm">search</span>
            </div>
            <div>
                <label class="block text-[10px] uppercase font-bold text-neutral-500 tracking-widest mb-2 invisible">Action</label>
                <button onclick="applyFilters()" class="px-6 py-2.5 bg-cyan-400 text-on-primary-container rounded-lg hover:shadow-[0_0_20px_rgba(161,250,255,0.3)] transition-all flex items-center gap-2 font-bold text-sm whitespace-nowrap">
                    <span class="material-symbols-outlined text-sm">search</span>
                    Tìm kiếm
                </button>
            </div>
        </div>
        
        <div>
            <label class="block text-[10px] uppercase font-bold text-neutral-500 tracking-widest mb-2 invisible">Add</label>
            <a href="{{ route('admin.inventory.create') }}" class="px-6 py-2.5 bg-secondary text-on-secondary rounded-lg hover:shadow-[0_0_20px_rgba(255,117,36,0.3)] transition-all flex items-center gap-2 font-bold text-sm whitespace-nowrap">
                <span class="material-symbols-outlined text-sm">add</span>
                Thêm mới
            </a>
        </div>
    </div>
    
    <!-- Brand Filter Row (Bottom) -->
    <div class="p-6">
        <label class="block text-[10px] uppercase font-bold text-neutral-500 tracking-widest mb-3">Thương hiệu</label>
        <div class="flex gap-2 flex-wrap">
            <button onclick="filterByBrand('all')" class="px-4 py-2 bg-surface-container-highest text-cyan-400 border border-cyan-400/30 rounded-lg text-sm font-bold font-headline brand-filter active" data-brand="all">Tất cả</button>
            @foreach($brands as $brand)
            <button onclick="filterByBrand('{{ $brand->name }}')" class="px-4 py-2 bg-surface-container-high text-neutral-400 hover:text-white transition-colors rounded-lg text-sm font-medium font-headline brand-filter" data-brand="{{ $brand->name }}">{{ $brand->name }}</button>
            @endforeach
        </div>
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
        <tbody id="productTableBody" class="divide-y divide-neutral-800/20">
            @foreach($products ?? [] as $product)
            <tr data-product-id="{{ $product->id }}" class="hover:bg-surface-container-high/30 transition-colors group {{ $product->stock == 0 ? 'opacity-60' : '' }}">
                <td class="px-6 py-5">
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 rounded-xl overflow-hidden bg-neutral-900 border border-outline-variant/10 group-hover:border-cyan-400/20 transition-all">
                            @if($product->image)
                            <img alt="{{ $product->name }}" class="w-full h-full object-cover opacity-80 group-hover:opacity-100 transition-opacity {{ $product->stock == 0 ? 'grayscale' : '' }}" src="{{ str_starts_with($product->image, 'http') ? $product->image : asset('storage/' . $product->image) }}"/>
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
                        <a href="{{ route('admin.inventory.edit', $product->id) }}" class="p-2 text-neutral-500 hover:text-cyan-400 hover:bg-cyan-400/10 rounded-lg transition-all">
                            <span class="material-symbols-outlined text-sm">edit</span>
                        </a>
                        <button onclick="deleteProduct({{ $product->id }})" class="p-2 text-neutral-500 hover:text-error hover:bg-error/10 rounded-lg transition-all">
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

@push('scripts')
<style>
/* Dark theme for select dropdown */
#stockStatusFilter option {
    background-color: #1e1e1e;
    color: white;
}

#stockStatusFilter {
    background-color: #2a2a2a;
    color: white;
}
</style>
<script>
let currentBrand = 'all';

function filterByBrand(brand) {
    currentBrand = brand;
    
    // Update button styles
    document.querySelectorAll('.brand-filter').forEach(btn => {
        btn.classList.remove('bg-surface-container-highest', 'text-cyan-400', 'border-cyan-400/30', 'active');
        btn.classList.add('bg-surface-container-high', 'text-neutral-400');
    });
    
    const activeBtn = document.querySelector(`[data-brand="${brand}"]`);
    activeBtn.classList.remove('bg-surface-container-high', 'text-neutral-400');
    activeBtn.classList.add('bg-surface-container-highest', 'text-cyan-400', 'border-cyan-400/30', 'active');
    
    applyFilters();
}

function applyFilters() {
    const stockStatus = document.getElementById('stockStatusFilter').value;
    const search = document.getElementById('searchInput').value;
    
    fetch(`{{ route('admin.inventory.filter') }}?brand=${currentBrand}&stock_status=${stockStatus}&search=${search}`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(products => {
        updateProductTable(products);
    });
}

function updateProductTable(products) {
    const tbody = document.getElementById('productTableBody');
    
    if (products.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="5" class="px-6 py-12 text-center">
                    <span class="material-symbols-outlined text-6xl text-neutral-700 mb-4">inventory_2</span>
                    <p class="text-neutral-500">Không tìm thấy sản phẩm</p>
                </td>
            </tr>
        `;
        return;
    }
    
    tbody.innerHTML = products.map(product => {
        const stockStatus = product.stock == 0 ? 'Hết hàng' : (product.stock < 10 ? 'Sắp hết' : 'An toàn');
        const stockColor = product.stock == 0 ? 'text-error' : (product.stock < 10 ? 'text-orange-500' : 'text-cyan-400');
        const stockBgColor = product.stock == 0 ? 'bg-error shadow-[0_0_8px_#ff716c]' : (product.stock < 10 ? 'bg-orange-500 shadow-[0_0_8px_#ff7524]' : 'bg-cyan-400 shadow-[0_0_8px_#a1faff]');
        
        return `
            <tr class="hover:bg-surface-container-high/30 transition-colors group ${product.stock == 0 ? 'opacity-60' : ''}" data-product-id="${product.id}">
                <td class="px-6 py-5">
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 rounded-xl overflow-hidden bg-neutral-900 border border-outline-variant/10 group-hover:border-cyan-400/20 transition-all">
                            ${product.image ? 
                                `<img alt="${product.name}" class="w-full h-full object-cover opacity-80 group-hover:opacity-100 transition-opacity ${product.stock == 0 ? 'grayscale' : ''}" src="${product.image.startsWith('http') ? product.image : '/storage/' + product.image}"/>` :
                                `<div class="w-full h-full bg-gradient-to-br from-primary/20 to-secondary/20 flex items-center justify-center">
                                    <span class="material-symbols-outlined text-white">laptop</span>
                                </div>`
                            }
                        </div>
                        <div>
                            <p class="font-bold font-headline text-white">${product.name}</p>
                            <p class="text-xs text-neutral-500">SKU: ${product.sku}</p>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-5">
                    <span class="px-3 py-1 bg-surface-container-highest text-white text-[10px] font-black rounded uppercase tracking-widest">${product.brand}</span>
                </td>
                <td class="px-6 py-5">
                    <div class="w-48">
                        <div class="flex justify-between items-center mb-1">
                            <span class="text-xs font-bold ${stockColor}">${String(product.stock).padStart(2, '0')}/100</span>
                            <span class="text-[10px] font-bold uppercase ${stockColor}">${stockStatus}</span>
                        </div>
                        <div class="h-1.5 w-full bg-neutral-800 rounded-full overflow-hidden">
                            <div class="h-full ${stockBgColor} rounded-full" style="width: ${Math.min(product.stock, 100)}%"></div>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-5">
                    <p class="font-bold font-headline text-white">${new Intl.NumberFormat('vi-VN').format(product.price)}₫</p>
                </td>
                <td class="px-6 py-5">
                    <div class="flex justify-end gap-2">
                        <a href="/admin/inventory/${product.id}/edit" class="p-2 text-neutral-500 hover:text-cyan-400 hover:bg-cyan-400/10 rounded-lg transition-all">
                            <span class="material-symbols-outlined text-sm">edit</span>
                        </a>
                        <button onclick="deleteProduct(${product.id})" class="p-2 text-neutral-500 hover:text-error hover:bg-error/10 rounded-lg transition-all">
                            <span class="material-symbols-outlined text-sm">delete</span>
                        </button>
                    </div>
                </td>
            </tr>
        `;
    }).join('');
}

function deleteProduct(id) {
    if (!confirm('Bạn có chắc chắn muốn xóa sản phẩm này?')) return;
    
    fetch(`/admin/inventory/${id}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Remove row from table
            document.querySelector(`[data-product-id="${id}"]`).remove();
            alert(data.message);
        }
    });
}
</script>
@endpush
@endsection
