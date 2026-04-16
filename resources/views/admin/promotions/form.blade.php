@extends('layouts.admin')

@section('title', isset($promotion) ? 'Sửa khuyến mãi' : 'Tạo khuyến mãi')

@section('content')
<div class="max-w-5xl mx-auto">
    <div class="mb-8">
        <a href="{{ route('admin.promotions') }}" class="inline-flex items-center gap-2 text-primary hover:text-primary/80 transition-colors mb-4">
            <span class="material-symbols-outlined">arrow_back</span>
            <span>Quay lại danh sách</span>
        </a>
        <h1 class="text-4xl font-bold tracking-tight text-on-background mb-2">
            {{ isset($promotion) ? 'Sửa khuyến mãi' : 'Tạo khuyến mãi mới' }}
        </h1>
    </div>

    <form action="{{ isset($promotion) ? route('admin.promotions.update', $promotion->id) : route('admin.promotions.store') }}" method="POST" class="surface-container-low rounded-xl p-8 glass-panel">
        @csrf
        @if(isset($promotion))
            @method('PUT')
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-xs uppercase font-bold text-cyan-400 tracking-widest mb-2">Mã khuyến mãi *</label>
                <input type="text" name="code" value="{{ old('code', $promotion->code ?? '') }}" required class="w-full bg-white border border-gray-300 rounded-lg px-4 py-3 text-gray-900 focus:ring-1 focus:ring-cyan-400 uppercase">
                @error('code')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-xs uppercase font-bold text-cyan-400 tracking-widest mb-2">Tên chương trình *</label>
                <input type="text" name="name" value="{{ old('name', $promotion->name ?? '') }}" required class="w-full bg-white border border-gray-300 rounded-lg px-4 py-3 text-gray-900 focus:ring-1 focus:ring-cyan-400">
                @error('name')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-xs uppercase font-bold text-cyan-400 tracking-widest mb-2">Loại giảm giá *</label>
                <select name="type" required class="w-full bg-white border border-gray-300 rounded-lg px-4 py-3 text-gray-900 focus:ring-1 focus:ring-cyan-400">
                    <option value="percentage" {{ old('type', $promotion->type ?? '') == 'percentage' ? 'selected' : '' }}>Phần trăm (%)</option>
                    <option value="fixed" {{ old('type', $promotion->type ?? '') == 'fixed' ? 'selected' : '' }}>Số tiền cố định (₫)</option>
                </select>
                @error('type')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-xs uppercase font-bold text-cyan-400 tracking-widest mb-2">Giá trị giảm *</label>
                <input type="number" name="value" value="{{ old('value', $promotion->value ?? '') }}" required min="0" step="0.01" class="w-full bg-white border border-gray-300 rounded-lg px-4 py-3 text-gray-900 focus:ring-1 focus:ring-cyan-400">
                @error('value')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-xs uppercase font-bold text-cyan-400 tracking-widest mb-2">Đơn hàng tối thiểu *</label>
                <input type="number" name="min_order_amount" value="{{ old('min_order_amount', $promotion->min_order_amount ?? 0) }}" required min="0" step="1000" class="w-full bg-white border border-gray-300 rounded-lg px-4 py-3 text-gray-900 focus:ring-1 focus:ring-cyan-400">
                @error('min_order_amount')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-xs uppercase font-bold text-cyan-400 tracking-widest mb-2">Giảm tối đa (cho %)</label>
                <input type="number" name="max_discount" value="{{ old('max_discount', $promotion->max_discount ?? '') }}" min="0" step="1000" class="w-full bg-white border border-gray-300 rounded-lg px-4 py-3 text-gray-900 focus:ring-1 focus:ring-cyan-400">
                @error('max_discount')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-xs uppercase font-bold text-cyan-400 tracking-widest mb-2">Số lần sử dụng tối đa</label>
                <input type="number" name="usage_limit" value="{{ old('usage_limit', $promotion->usage_limit ?? '') }}" min="1" class="w-full bg-white border border-gray-300 rounded-lg px-4 py-3 text-gray-900 focus:ring-1 focus:ring-cyan-400">
                <p class="text-xs text-gray-500 mt-1">Để trống = không giới hạn</p>
                @error('usage_limit')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-xs uppercase font-bold text-cyan-400 tracking-widest mb-2">Ngày bắt đầu *</label>
                <input type="datetime-local" name="start_date" value="{{ old('start_date', isset($promotion) ? $promotion->start_date->format('Y-m-d\TH:i') : '') }}" required class="w-full bg-white border border-gray-300 rounded-lg px-4 py-3 text-gray-900 focus:ring-1 focus:ring-cyan-400">
                @error('start_date')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-xs uppercase font-bold text-cyan-400 tracking-widest mb-2">Ngày kết thúc *</label>
                <input type="datetime-local" name="end_date" value="{{ old('end_date', isset($promotion) ? $promotion->end_date->format('Y-m-d\TH:i') : '') }}" required class="w-full bg-white border border-gray-300 rounded-lg px-4 py-3 text-gray-900 focus:ring-1 focus:ring-cyan-400">
                @error('end_date')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
            </div>
        </div>

        <div class="mt-6">
            <label class="block text-xs uppercase font-bold text-cyan-400 tracking-widest mb-2">Mô tả</label>
            <textarea name="description" rows="3" class="w-full bg-white border border-gray-300 rounded-lg px-4 py-3 text-gray-900 focus:ring-1 focus:ring-cyan-400">{{ old('description', $promotion->description ?? '') }}</textarea>
            @error('description')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="mt-6">
            <label class="flex items-center gap-3 cursor-pointer">
                <input type="checkbox" name="is_active" {{ old('is_active', $promotion->is_active ?? true) ? 'checked' : '' }} class="w-5 h-5 rounded border-gray-300 text-cyan-400 focus:ring-cyan-400">
                <span class="text-sm font-medium text-on-background">Kích hoạt ngay</span>
            </label>
        </div>

        <div class="flex justify-end gap-4 mt-8">
            <a href="{{ route('admin.promotions') }}" class="px-6 py-3 bg-surface-container-highest text-on-surface rounded-lg font-medium hover:bg-surface-container-high transition-all">
                Hủy
            </a>
            <button type="submit" class="px-6 py-3 bg-primary text-on-primary rounded-lg font-bold hover:bg-primary/90 transition-all">
                {{ isset($promotion) ? 'Cập nhật' : 'Tạo mới' }}
            </button>
        </div>
    </form>
</div>

@push('styles')
<style>
.glass-panel {
    background: rgba(38, 38, 38, 0.4);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(72, 72, 71, 0.15);
}
</style>
@endpush
@endsection
