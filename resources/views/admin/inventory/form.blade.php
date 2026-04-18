@extends('layouts.admin')

@section('title', isset($laptop) ? 'Sửa sản phẩm' : 'Thêm sản phẩm mới')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('admin.inventory') }}" class="p-2 hover:bg-surface-container-high rounded-lg transition-colors">
            <span class="material-symbols-outlined">arrow_back</span>
        </a>
        <div>
            <h1 class="text-3xl font-bold text-white font-headline">{{ isset($laptop) ? 'Sửa sản phẩm' : 'Thêm sản phẩm mới' }}</h1>
            <p class="text-neutral-500 text-sm mt-1">Điền đầy đủ thông tin sản phẩm</p>
        </div>
    </div>

    <!-- Form -->
    <form action="{{ isset($laptop) ? route('admin.inventory.update', $laptop->id) : route('admin.inventory.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @if(isset($laptop))
            @method('PUT')
        @endif

        <!-- Basic Info Card -->
        <div class="bg-surface-container-low rounded-xl p-6 space-y-6">
            <h2 class="text-xl font-bold text-white font-headline">Thông tin cơ bản</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-neutral-400 mb-2">Tên sản phẩm *</label>
                    <input type="text" name="name" value="{{ old('name', $laptop->name ?? '') }}" required
                        class="w-full bg-white border border-outline-variant/15 rounded-lg px-4 py-3 text-gray-900 focus:ring-2 focus:ring-primary/50 transition-all">
                    @error('name')
                        <p class="text-error text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-neutral-400 mb-2">Danh mục *</label>
                    <select name="category_id" required
                        class="w-full bg-white border border-outline-variant/15 rounded-lg px-4 py-3 text-gray-900 focus:ring-2 focus:ring-primary/50">
                        <option value="">Chọn danh mục</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $laptop->category_id ?? '') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <p class="text-error text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-neutral-400 mb-2">Thương hiệu *</label>
                    <select name="brand" required
                        class="w-full bg-white border border-outline-variant/15 rounded-lg px-4 py-3 text-gray-900 focus:ring-2 focus:ring-primary/50">
                        <option value="">Chọn thương hiệu</option>
                        @foreach($brands as $brand)
                            <option value="{{ $brand->name }}" {{ old('brand', $laptop->brand ?? '') == $brand->name ? 'selected' : '' }}>
                                {{ $brand->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('brand')
                        <p class="text-error text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-neutral-400 mb-2">Giá (VNĐ) *</label>
                    <input type="number" name="price" value="{{ old('price', $laptop->price ?? '') }}" required min="0" step="1000"
                        class="w-full bg-white border border-outline-variant/15 rounded-lg px-4 py-3 text-gray-900 focus:ring-2 focus:ring-primary/50">
                    @error('price')
                        <p class="text-error text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-neutral-400 mb-2">Tồn kho *</label>
                    <input type="number" name="stock" value="{{ old('stock', $laptop->stock ?? 0) }}" required min="0"
                        class="w-full bg-white border border-outline-variant/15 rounded-lg px-4 py-3 text-gray-900 focus:ring-2 focus:ring-primary/50">
                    @error('stock')
                        <p class="text-error text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center gap-3 pt-8">
                    <input type="checkbox" name="is_featured" id="is_featured" {{ old('is_featured', $laptop->is_featured ?? false) ? 'checked' : '' }}
                        class="w-5 h-5 rounded border-gray-300 text-primary focus:ring-primary">
                    <label for="is_featured" class="text-sm font-bold text-neutral-400 cursor-pointer">
                        Hiển thị trang chủ (Featured)
                    </label>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-neutral-400 mb-2">Mô tả *</label>
                    <textarea name="description" rows="4" required
                        class="w-full bg-white border border-outline-variant/15 rounded-lg px-4 py-3 text-gray-900 focus:ring-2 focus:ring-primary/50">{{ old('description', $laptop->description ?? '') }}</textarea>
                    @error('description')
                        <p class="text-error text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Specs Card -->
        <div class="bg-surface-container-low rounded-xl p-6 space-y-6">
            <h2 class="text-xl font-bold text-white font-headline">Thông số kỹ thuật</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-bold text-neutral-400 mb-2">Processor *</label>
                    <input type="text" name="processor" value="{{ old('processor', $laptop->processor ?? '') }}" required
                        class="w-full bg-white border border-outline-variant/15 rounded-lg px-4 py-3 text-gray-900 focus:ring-2 focus:ring-primary/50"
                        placeholder="Intel Core i7-13700H">
                    @error('processor')
                        <p class="text-error text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-neutral-400 mb-2">RAM *</label>
                    <input type="text" name="ram" value="{{ old('ram', $laptop->ram ?? '') }}" required
                        class="w-full bg-white border border-outline-variant/15 rounded-lg px-4 py-3 text-gray-900 focus:ring-2 focus:ring-primary/50"
                        placeholder="16GB DDR5">
                    @error('ram')
                        <p class="text-error text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-neutral-400 mb-2">Storage *</label>
                    <input type="text" name="storage" value="{{ old('storage', $laptop->storage ?? '') }}" required
                        class="w-full bg-white border border-outline-variant/15 rounded-lg px-4 py-3 text-gray-900 focus:ring-2 focus:ring-primary/50"
                        placeholder="512GB NVMe SSD">
                    @error('storage')
                        <p class="text-error text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-neutral-400 mb-2">Display *</label>
                    <input type="text" name="display" value="{{ old('display', $laptop->display ?? '') }}" required
                        class="w-full bg-white border border-outline-variant/15 rounded-lg px-4 py-3 text-gray-900 focus:ring-2 focus:ring-primary/50"
                        placeholder="15.6 inch FHD IPS">
                    @error('display')
                        <p class="text-error text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-neutral-400 mb-2">Graphics</label>
                    <input type="text" name="graphics" value="{{ old('graphics', $laptop->graphics ?? '') }}"
                        class="w-full bg-white border border-outline-variant/15 rounded-lg px-4 py-3 text-gray-900 focus:ring-2 focus:ring-primary/50"
                        placeholder="NVIDIA RTX 4060">
                    @error('graphics')
                        <p class="text-error text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Image Card -->
        <div class="bg-surface-container-low rounded-xl p-6 space-y-4">
            <h2 class="text-xl font-bold text-white font-headline">Hình ảnh</h2>
            
            @if(isset($laptop) && $laptop->image)
                <div class="mb-4">
                    <img src="{{ str_starts_with($laptop->image, 'http') ? $laptop->image : asset('storage/' . $laptop->image) }}" alt="Current image" class="w-48 h-48 object-cover rounded-lg">
                    <p class="text-xs text-neutral-500 mt-2">Ảnh hiện tại</p>
                </div>
            @endif

            <input type="file" name="image" accept="image/*"
                class="w-full bg-white border border-outline-variant/15 rounded-lg px-4 py-3 text-gray-900 focus:ring-2 focus:ring-primary/50 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary file:text-on-primary hover:file:bg-primary-dim">
            <p class="text-xs text-neutral-500">PNG, JPG, JPEG (Max: 2MB)</p>
            @error('image')
                <p class="text-error text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Actions -->
        <div class="flex gap-4 justify-end">
            <a href="{{ route('admin.inventory') }}" 
                class="px-6 py-3 rounded-lg border border-outline-variant/30 text-neutral-400 hover:text-white transition-all font-bold">
                Hủy
            </a>
            <button type="submit" 
                class="px-6 py-3 rounded-lg bg-secondary text-on-secondary hover:shadow-[0_0_20px_rgba(255,117,36,0.3)] transition-all font-bold">
                {{ isset($laptop) ? 'Cập nhật' : 'Thêm sản phẩm' }}
            </button>
        </div>
    </form>
</div>
@endsection
