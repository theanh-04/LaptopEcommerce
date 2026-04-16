@extends('layouts.admin')

@section('title', 'Quản lý Thương hiệu')

@section('content')
<!-- Header Section -->
<div class="flex flex-col md:flex-row md:items-end justify-between mb-10 gap-6">
    <div>
        <h2 class="text-4xl font-extrabold tracking-tighter text-white mb-2 font-headline">Quản Lý Thương Hiệu</h2>
        <p class="text-neutral-400 max-w-lg font-body">Quản lý danh sách các thương hiệu laptop trong hệ thống.</p>
    </div>
    <div class="flex gap-4">
        <div class="bg-surface-container-low px-6 py-3 rounded-lg flex items-center gap-4 border border-outline-variant/10">
            <div class="w-10 h-10 rounded-full bg-cyan-400/10 flex items-center justify-center text-cyan-400">
                <span class="material-symbols-outlined">category</span>
            </div>
            <div>
                <p class="text-xs text-neutral-500 uppercase font-bold tracking-wider">Tổng thương hiệu</p>
                <p class="text-xl font-bold font-headline">{{ $brands->count() }}</p>
            </div>
        </div>
        <div class="bg-surface-container-low px-6 py-3 rounded-lg flex items-center gap-4 border border-outline-variant/10">
            <div class="w-10 h-10 rounded-full bg-green-500/10 flex items-center justify-center text-green-500">
                <span class="material-symbols-outlined">check_circle</span>
            </div>
            <div>
                <p class="text-xs text-neutral-500 uppercase font-bold tracking-wider">Đang hoạt động</p>
                <p class="text-xl font-bold font-headline text-green-500">{{ $brands->where('is_active', true)->count() }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Action Bar -->
<div class="mb-6 flex justify-end">
    <a href="{{ route('admin.brands.create') }}" class="px-6 py-3 bg-secondary text-on-secondary rounded-lg hover:shadow-[0_0_20px_rgba(255,117,36,0.3)] transition-all flex items-center gap-2 font-bold">
        <span class="material-symbols-outlined">add</span>
        Thêm Thương Hiệu
    </a>
</div>

<!-- Brands Table -->
<div class="bg-surface-container-low rounded-xl overflow-hidden border border-outline-variant/5">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-surface-container-high/50 border-b border-neutral-800/30">
                <th class="px-6 py-5 text-xs uppercase font-bold text-neutral-500 tracking-widest">Thứ tự</th>
                <th class="px-6 py-5 text-xs uppercase font-bold text-neutral-500 tracking-widest">Thương hiệu</th>
                <th class="px-6 py-5 text-xs uppercase font-bold text-neutral-500 tracking-widest">Slug</th>
                <th class="px-6 py-5 text-xs uppercase font-bold text-neutral-500 tracking-widest">Số sản phẩm</th>
                <th class="px-6 py-5 text-xs uppercase font-bold text-neutral-500 tracking-widest">Trạng thái</th>
                <th class="px-6 py-5 text-xs uppercase font-bold text-neutral-500 tracking-widest text-right">Hành động</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-neutral-800/20">
            @foreach($brands as $brand)
            <tr class="hover:bg-surface-container-high/30 transition-colors group" data-brand-id="{{ $brand->id }}">
                <td class="px-6 py-5">
                    <span class="text-white font-bold">{{ $brand->display_order }}</span>
                </td>
                <td class="px-6 py-5">
                    <div class="flex items-center gap-3">
                        @if($brand->logo)
                        <img src="{{ asset('storage/' . $brand->logo) }}" alt="{{ $brand->name }}" class="w-10 h-10 object-contain rounded-lg bg-white p-1">
                        @else
                        <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-cyan-400/20 to-secondary/20 flex items-center justify-center">
                            <span class="material-symbols-outlined text-white text-sm">business</span>
                        </div>
                        @endif
                        <span class="font-bold font-headline text-white">{{ $brand->name }}</span>
                    </div>
                </td>
                <td class="px-6 py-5">
                    <span class="text-neutral-400 text-sm font-mono">{{ $brand->slug }}</span>
                </td>
                <td class="px-6 py-5">
                    <span class="text-white font-bold">{{ $brand->laptops->count() }}</span>
                </td>
                <td class="px-6 py-5">
                    @if($brand->is_active)
                    <span class="px-3 py-1 bg-green-500/10 text-green-500 text-xs font-bold rounded-full">Hoạt động</span>
                    @else
                    <span class="px-3 py-1 bg-neutral-700 text-neutral-400 text-xs font-bold rounded-full">Tắt</span>
                    @endif
                </td>
                <td class="px-6 py-5">
                    <div class="flex justify-end gap-2">
                        <a href="{{ route('admin.brands.edit', $brand->id) }}" class="p-2 text-neutral-500 hover:text-cyan-400 hover:bg-cyan-400/10 rounded-lg transition-all">
                            <span class="material-symbols-outlined text-sm">edit</span>
                        </a>
                        <button onclick="deleteBrand({{ $brand->id }}, '{{ $brand->name }}')" class="p-2 text-neutral-500 hover:text-error hover:bg-error/10 rounded-lg transition-all">
                            <span class="material-symbols-outlined text-sm">delete</span>
                        </button>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@push('scripts')
<script>
function deleteBrand(id, name) {
    if (!confirm(`Bạn có chắc chắn muốn xóa thương hiệu "${name}"?`)) return;
    
    fetch(`/admin/brands/${id}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.querySelector(`[data-brand-id="${id}"]`).remove();
            alert(data.message);
        } else {
            alert(data.message || 'Có lỗi xảy ra');
        }
    })
    .catch(error => {
        alert('Có lỗi xảy ra khi xóa thương hiệu');
    });
}
</script>
@endpush
@endsection
