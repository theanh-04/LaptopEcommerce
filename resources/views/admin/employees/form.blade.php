@extends('layouts.admin')

@section('title', isset($employee) ? 'Sửa nhân viên' : 'Thêm nhân viên')

@section('content')
<div class="max-w-5xl mx-auto">
    <div class="mb-8">
        <a href="{{ route('admin.employees') }}" class="inline-flex items-center gap-2 text-primary hover:text-primary/80 transition-colors mb-4">
            <span class="material-symbols-outlined">arrow_back</span>
            <span>Quay lại danh sách</span>
        </a>
        <h1 class="text-4xl font-bold tracking-tight text-on-background mb-2">
            {{ isset($employee) ? 'Sửa thông tin nhân viên' : 'Thêm nhân viên mới' }}
        </h1>
        <p class="text-on-surface-variant">Điền đầy đủ thông tin nhân viên</p>
    </div>

    <form action="{{ isset($employee) ? route('admin.employees.update', $employee->id) : route('admin.employees.store') }}" method="POST" enctype="multipart/form-data" class="surface-container-low rounded-xl p-8 glass-panel">
        @csrf
        @if(isset($employee))
            @method('PUT')
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Tên nhân viên -->
            <div>
                <label class="block text-xs uppercase font-bold text-cyan-400 tracking-widest mb-2">Tên nhân viên *</label>
                <input type="text" name="name" value="{{ old('name', $employee->name ?? '') }}" required class="w-full bg-white border border-gray-300 rounded-lg px-4 py-3 text-gray-900 focus:ring-1 focus:ring-cyan-400 focus:border-cyan-400">
                @error('name')
                    <p class="text-error text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div>
                <label class="block text-xs uppercase font-bold text-cyan-400 tracking-widest mb-2">Email *</label>
                <input type="email" name="email" value="{{ old('email', $employee->email ?? '') }}" required class="w-full bg-white border border-gray-300 rounded-lg px-4 py-3 text-gray-900 focus:ring-1 focus:ring-cyan-400 focus:border-cyan-400">
                @error('email')
                    <p class="text-error text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Số điện thoại -->
            <div>
                <label class="block text-xs uppercase font-bold text-cyan-400 tracking-widest mb-2">Số điện thoại</label>
                <input type="text" name="phone" value="{{ old('phone', $employee->phone ?? '') }}" class="w-full bg-white border border-gray-300 rounded-lg px-4 py-3 text-gray-900 focus:ring-1 focus:ring-cyan-400 focus:border-cyan-400">
                @error('phone')
                    <p class="text-error text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Chức vụ -->
            <div>
                <label class="block text-xs uppercase font-bold text-cyan-400 tracking-widest mb-2">Chức vụ *</label>
                <input type="text" name="position" value="{{ old('position', $employee->position ?? '') }}" required class="w-full bg-white border border-gray-300 rounded-lg px-4 py-3 text-gray-900 focus:ring-1 focus:ring-cyan-400 focus:border-cyan-400">
                @error('position')
                    <p class="text-error text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Phòng ban -->
            <div>
                <label class="block text-xs uppercase font-bold text-cyan-400 tracking-widest mb-2">Phòng ban *</label>
                <input type="text" name="department" value="{{ old('department', $employee->department ?? '') }}" required list="departments" class="w-full bg-white border border-gray-300 rounded-lg px-4 py-3 text-gray-900 focus:ring-1 focus:ring-cyan-400 focus:border-cyan-400">
                <datalist id="departments">
                    @foreach($departments ?? [] as $dept)
                    <option value="{{ $dept }}">
                    @endforeach
                </datalist>
                @error('department')
                    <p class="text-error text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Ngày vào làm -->
            <div>
                <label class="block text-xs uppercase font-bold text-cyan-400 tracking-widest mb-2">Ngày vào làm *</label>
                <input type="date" name="hire_date" value="{{ old('hire_date', isset($employee) ? $employee->hire_date->format('Y-m-d') : '') }}" required class="w-full bg-white border border-gray-300 rounded-lg px-4 py-3 text-gray-900 focus:ring-1 focus:ring-cyan-400 focus:border-cyan-400">
                @error('hire_date')
                    <p class="text-error text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Lương -->
            <div>
                <label class="block text-xs uppercase font-bold text-cyan-400 tracking-widest mb-2">Lương (VNĐ)</label>
                <input type="number" name="salary" value="{{ old('salary', $employee->salary ?? '') }}" min="0" step="1000" class="w-full bg-white border border-gray-300 rounded-lg px-4 py-3 text-gray-900 focus:ring-1 focus:ring-cyan-400 focus:border-cyan-400">
                @error('salary')
                    <p class="text-error text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Avatar -->
            <div>
                <label class="block text-xs uppercase font-bold text-cyan-400 tracking-widest mb-2">Ảnh đại diện</label>
                @if(isset($employee) && $employee->avatar)
                <div class="mb-3">
                    <img src="{{ asset('storage/' . $employee->avatar) }}" alt="{{ $employee->name }}" class="w-24 h-24 rounded-lg object-cover border-2 border-gray-300">
                    <p class="text-xs text-gray-500 mt-1">Ảnh hiện tại</p>
                </div>
                @endif
                <input type="file" name="avatar" accept="image/*" class="w-full bg-white border border-gray-300 rounded-lg px-4 py-3 text-gray-900 focus:ring-1 focus:ring-cyan-400 focus:border-cyan-400">
                @if(isset($employee) && $employee->avatar)
                <p class="text-xs text-gray-500 mt-1">Chọn ảnh mới để thay đổi</p>
                @endif
                @error('avatar')
                    <p class="text-error text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Địa chỉ -->
        <div class="mt-6">
            <label class="block text-xs uppercase font-bold text-cyan-400 tracking-widest mb-2">Địa chỉ</label>
            <textarea name="address" rows="3" class="w-full bg-white border border-gray-300 rounded-lg px-4 py-3 text-gray-900 focus:ring-1 focus:ring-cyan-400 focus:border-cyan-400">{{ old('address', $employee->address ?? '') }}</textarea>
            @error('address')
                <p class="text-error text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Buttons -->
        <div class="flex justify-end gap-4 mt-8">
            <a href="{{ route('admin.employees') }}" class="px-6 py-3 bg-surface-container-highest text-on-surface rounded-lg font-medium hover:bg-surface-container-high transition-all">
                Hủy
            </a>
            <button type="submit" class="px-6 py-3 bg-primary text-on-primary rounded-lg font-bold hover:bg-primary/90 transition-all">
                {{ isset($employee) ? 'Cập nhật' : 'Thêm mới' }}
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
