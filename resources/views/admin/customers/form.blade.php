@extends('layouts.admin')

@section('title', isset($customer) ? 'Sửa khách hàng' : 'Thêm khách hàng')

@section('content')
<div class="max-w-5xl mx-auto">
    <div class="mb-8">
        <a href="{{ route('admin.customers') }}" class="inline-flex items-center gap-2 text-primary hover:text-primary/80 transition-colors mb-4">
            <span class="material-symbols-outlined">arrow_back</span>
            <span>Quay lại danh sách</span>
        </a>
        <h1 class="text-4xl font-bold tracking-tight text-on-background mb-2">
            {{ isset($customer) ? 'Sửa thông tin khách hàng' : 'Thêm khách hàng mới' }}
        </h1>
    </div>

    <form action="{{ isset($customer) ? route('admin.customers.update', $customer->id) : route('admin.customers.store') }}" method="POST" class="surface-container-low rounded-xl p-8 glass-panel">
        @csrf
        @if(isset($customer))
            @method('PUT')
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-xs uppercase font-bold text-cyan-400 tracking-widest mb-2">Tên khách hàng *</label>
                <input type="text" name="name" value="{{ old('name', $customer->name ?? '') }}" required class="w-full bg-white border border-gray-300 rounded-lg px-4 py-3 text-gray-900 focus:ring-1 focus:ring-cyan-400">
                @error('name')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-xs uppercase font-bold text-cyan-400 tracking-widest mb-2">Email *</label>
                <input type="email" name="email" value="{{ old('email', $customer->email ?? '') }}" required class="w-full bg-white border border-gray-300 rounded-lg px-4 py-3 text-gray-900 focus:ring-1 focus:ring-cyan-400">
                @error('email')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-xs uppercase font-bold text-cyan-400 tracking-widest mb-2">Số điện thoại</label>
                <input type="tel" name="phone" value="{{ old('phone', $customer->phone ?? '') }}" class="w-full bg-white border border-gray-300 rounded-lg px-4 py-3 text-gray-900 focus:ring-1 focus:ring-cyan-400">
                @error('phone')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-xs uppercase font-bold text-cyan-400 tracking-widest mb-2">Ngày sinh</label>
                <input type="date" name="date_of_birth" value="{{ old('date_of_birth', isset($customer) ? $customer->date_of_birth?->format('Y-m-d') : '') }}" class="w-full bg-white border border-gray-300 rounded-lg px-4 py-3 text-gray-900 focus:ring-1 focus:ring-cyan-400">
                @error('date_of_birth')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-xs uppercase font-bold text-cyan-400 tracking-widest mb-2">Giới tính</label>
                <select name="gender" class="w-full bg-white border border-gray-300 rounded-lg px-4 py-3 text-gray-900 focus:ring-1 focus:ring-cyan-400">
                    <option value="">Chọn giới tính</option>
                    <option value="male" {{ old('gender', $customer->gender ?? '') == 'male' ? 'selected' : '' }}>Nam</option>
                    <option value="female" {{ old('gender', $customer->gender ?? '') == 'female' ? 'selected' : '' }}>Nữ</option>
                    <option value="other" {{ old('gender', $customer->gender ?? '') == 'other' ? 'selected' : '' }}>Khác</option>
                </select>
                @error('gender')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            @if(isset($customer))
            <div>
                <label class="block text-xs uppercase font-bold text-cyan-400 tracking-widest mb-2">Hạng thành viên *</label>
                <select name="tier" required class="w-full bg-white border border-gray-300 rounded-lg px-4 py-3 text-gray-900 focus:ring-1 focus:ring-cyan-400">
                    <option value="bronze" {{ old('tier', $customer->tier ?? '') == 'bronze' ? 'selected' : '' }}>Đồng</option>
                    <option value="silver" {{ old('tier', $customer->tier ?? '') == 'silver' ? 'selected' : '' }}>Bạc</option>
                    <option value="gold" {{ old('tier', $customer->tier ?? '') == 'gold' ? 'selected' : '' }}>Vàng</option>
                    <option value="platinum" {{ old('tier', $customer->tier ?? '') == 'platinum' ? 'selected' : '' }}>Bạch Kim</option>
                </select>
                @error('tier')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-xs uppercase font-bold text-cyan-400 tracking-widest mb-2">Điểm tích lũy</label>
                <input type="number" name="loyalty_points" value="{{ old('loyalty_points', $customer->loyalty_points ?? 0) }}" min="0" class="w-full bg-white border border-gray-300 rounded-lg px-4 py-3 text-gray-900 focus:ring-1 focus:ring-cyan-400">
                @error('loyalty_points')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            @endif
        </div>

        <div class="mt-6">
            <label class="block text-xs uppercase font-bold text-cyan-400 tracking-widest mb-2">Địa chỉ</label>
            <textarea name="address" rows="3" class="w-full bg-white border border-gray-300 rounded-lg px-4 py-3 text-gray-900 focus:ring-1 focus:ring-cyan-400">{{ old('address', $customer->address ?? '') }}</textarea>
            @error('address')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="mt-6">
            <label class="block text-xs uppercase font-bold text-cyan-400 tracking-widest mb-2">Ghi chú</label>
            <textarea name="notes" rows="3" class="w-full bg-white border border-gray-300 rounded-lg px-4 py-3 text-gray-900 focus:ring-1 focus:ring-cyan-400">{{ old('notes', $customer->notes ?? '') }}</textarea>
            @error('notes')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        @if(isset($customer))
        <div class="mt-6 p-4 bg-neutral-800/50 rounded-lg">
            <h3 class="text-sm font-bold text-cyan-400 mb-3">Thống kê</h3>
            <div class="grid grid-cols-3 gap-4">
                <div>
                    <p class="text-xs text-neutral-400">Tổng chi tiêu</p>
                    <p class="text-lg font-bold text-white">{{ number_format($customer->total_spent) }}₫</p>
                </div>
                <div>
                    <p class="text-xs text-neutral-400">Tổng đơn hàng</p>
                    <p class="text-lg font-bold text-white">{{ $customer->total_orders }}</p>
                </div>
                <div>
                    <p class="text-xs text-neutral-400">Mua gần nhất</p>
                    <p class="text-lg font-bold text-white">{{ $customer->last_purchase_at ? $customer->last_purchase_at->format('d/m/Y') : 'Chưa mua' }}</p>
                </div>
            </div>
        </div>
        @endif

        <div class="flex justify-end gap-4 mt-8">
            <a href="{{ route('admin.customers') }}" class="px-6 py-3 bg-surface-container-highest text-on-surface rounded-lg font-medium hover:bg-surface-container-high transition-all">
                Hủy
            </a>
            <button type="submit" class="px-6 py-3 bg-primary text-black rounded-lg font-bold hover:bg-primary/90 transition-all">
                {{ isset($customer) ? 'Cập nhật' : 'Thêm mới' }}
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
