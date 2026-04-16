@extends('layouts.admin')

@section('title', isset($brand) ? 'Sửa thương hiệu' : 'Thêm thương hiệu mới')

@section('content')
<div class="max-w-3xl mx-auto">
    <!-- Header -->
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('admin.brands.index') }}" class="p-2 hover:bg-surface-container-high rounded-lg transition-colors">
            <span class="material-symbols-outlined">arrow_back</span>
        </a>
        <div>
            <h1 class="text-3xl font-bold text-white font-headline">{{ isset($brand) ? 'Sửa thương hiệu' : 'Thêm thương hiệu mới' }}</h1>
            <p class="text-neutral-500 text-sm mt-1">Điền đầy đủ thông tin thương hiệu</p>
        </div>
    </div>

    <!-- Form -->
    <form action="{{ isset($brand) ? route('admin.brands.update', $brand->id) : route('admin.brands.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @if(isset($brand))
            @method('PUT')
        @endif

        <!-- Basic Info Card -->
        <div class="bg-surface-container-low rounded-xl p-6 space-y-6">
            <h2 class="text-xl font-bold text-white font-headline">Thông tin cơ bản</h2>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-bold text-neutral-400 mb-2">Tên thương hiệu *</label>
                    <input type="text" name="name" value="{{ old('name', $brand->name ?? '') }}" required
                        class="w-full bg-white border border-outline-variant/15 rounded-lg px-4 py-3 text-gray-900 focus:ring-2 focus:ring-primary/50 transition-all">
                    @error('name')
                        <p class="text-error text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-neutral-400 mb-2">Slug (URL-friendly)</label>
                    <input type="text" name="slug" value="{{ old('slug', $brand->slug ?? '') }}"
                        class="w-full bg-white border border-outline-variant/15 rounded-lg px-4 py-3 text-gray-900 focus:ring-2 focus:ring-primary/50 font-mono text-sm">
                    <p class="text-xs text-neutral-500 mt-1">Để trống để tự động tạo từ tên thương hiệu</p>
                    @error('slug')
                        <p class="text-error text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-neutral-400 mb-2">Thứ tự hiển thị</label>
                    <input type="number" name="display_order" value="{{ old('display_order', $brand->display_order ?? 0) }}" min="0"
                        class="w-full bg-white border border-outline-variant/15 rounded-lg px-4 py-3 text-gray-900 focus:ring-2 focus:ring-primary/50">
                    <p class="text-xs text-neutral-500 mt-1">Số nhỏ hơn sẽ hiển thị trước</p>
                    @error('display_order')
                        <p class="text-error text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center gap-3">
                    <input type="checkbox" name="is_active" id="is_active" value="1" 
                        {{ old('is_active', $brand->is_active ?? true) ? 'checked' : '' }}
                        class="w-5 h-5 rounded border-outline-variant/15 text-cyan-400 focus:ring-cyan-400/50">
                    <label for="is_active" class="text-sm font-bold text-neutral-400 cursor-pointer">Kích hoạt thương hiệu</label>
                </div>
            </div>
        </div>

        <!-- Logo Card -->
        <div class="bg-surface-container-low rounded-xl p-6 space-y-4">
            <h2 class="text-xl font-bold text-white font-headline">Logo thương hiệu</h2>
            
            @if(isset($brand) && $brand->logo)
                <div class="mb-4">
                    <img src="{{ asset('storage/' . $brand->logo) }}" alt="Current logo" class="w-32 h-32 object-contain bg-white rounded-lg p-2">
                    <p class="text-xs text-neutral-500 mt-2">Logo hiện tại</p>
                </div>
            @endif

            <input type="file" name="logo" accept="image/*"
                class="w-full bg-white border border-outline-variant/15 rounded-lg px-4 py-3 text-gray-900 focus:ring-2 focus:ring-primary/50 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary file:text-on-primary hover:file:bg-primary-dim">
            <p class="text-xs text-neutral-500">PNG, JPG, SVG (Max: 2MB) - Nên dùng ảnh nền trong suốt</p>
            @error('logo')
                <p class="text-error text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Actions -->
        <div class="flex gap-4 justify-end">
            <a href="{{ route('admin.brands.index') }}" 
                class="px-6 py-3 rounded-lg border border-outline-variant/30 text-neutral-400 hover:text-white transition-all font-bold">
                Hủy
            </a>
            <button type="submit" 
                class="px-6 py-3 rounded-lg bg-secondary text-on-secondary hover:shadow-[0_0_20px_rgba(255,117,36,0.3)] transition-all font-bold">
                {{ isset($brand) ? 'Cập nhật' : 'Thêm thương hiệu' }}
            </button>
        </div>
    </form>
</div>
@endsection
