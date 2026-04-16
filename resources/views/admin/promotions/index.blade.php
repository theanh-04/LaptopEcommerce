@extends('layouts.admin')

@section('title', 'Quản lý Khuyến mãi')

@section('content')
<!-- Header -->
<header class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-6">
    <div>
        <h1 class="text-4xl font-bold tracking-tight text-on-background mb-2">Quản lý Khuyến mãi</h1>
        <p class="text-on-surface-variant max-w-lg">Tạo và quản lý các chương trình khuyến mãi, mã giảm giá</p>
    </div>
    <a href="{{ route('admin.promotions.create') }}" class="px-6 py-3 bg-primary text-black rounded-xl font-bold text-sm hover:bg-primary/90 transition-all flex items-center gap-2 w-fit">
        <span class="material-symbols-outlined text-sm">add</span>
        Tạo khuyến mãi
    </a>
</header>

<!-- Stats -->
<section class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="surface-container-low rounded-xl p-6 glass-panel">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-lg bg-primary/10 flex items-center justify-center">
                <span class="material-symbols-outlined text-primary">local_offer</span>
            </div>
            <div>
                <p class="text-xs text-on-surface-variant uppercase font-bold">Đang hoạt động</p>
                <p class="text-2xl font-bold text-on-background">{{ $activeCount }}</p>
            </div>
        </div>
    </div>
    <div class="surface-container-low rounded-xl p-6 glass-panel">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-lg bg-error/10 flex items-center justify-center">
                <span class="material-symbols-outlined text-error">event_busy</span>
            </div>
            <div>
                <p class="text-xs text-on-surface-variant uppercase font-bold">Đã hết hạn</p>
                <p class="text-2xl font-bold text-on-background">{{ $expiredCount }}</p>
            </div>
        </div>
    </div>
    <div class="surface-container-low rounded-xl p-6 glass-panel">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-lg bg-secondary/10 flex items-center justify-center">
                <span class="material-symbols-outlined text-secondary">trending_up</span>
            </div>
            <div>
                <p class="text-xs text-on-surface-variant uppercase font-bold">Lượt sử dụng</p>
                <p class="text-2xl font-bold text-on-background">{{ $totalUsage }}</p>
            </div>
        </div>
    </div>
</section>

<!-- Promotions Table -->
<section class="surface-container-low rounded-xl overflow-hidden glass-panel">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-surface-container-high/50 text-on-surface-variant text-xs uppercase tracking-widest font-bold">
                    <th class="px-6 py-5">Mã & Tên</th>
                    <th class="px-6 py-5">Loại</th>
                    <th class="px-6 py-5">Giá trị</th>
                    <th class="px-6 py-5">Thời gian</th>
                    <th class="px-6 py-5">Sử dụng</th>
                    <th class="px-6 py-5">Trạng thái</th>
                    <th class="px-6 py-5 text-right">Thao tác</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-outline-variant/10">
                @foreach($promotions as $promo)
                <tr class="hover:bg-surface-container-high/40 transition-colors">
                    <td class="px-6 py-5">
                        <div>
                            <div class="font-bold text-primary font-mono">{{ $promo->code }}</div>
                            <div class="text-sm text-on-background">{{ $promo->name }}</div>
                        </div>
                    </td>
                    <td class="px-6 py-5">
                        @if($promo->type === 'percentage')
                        <span class="px-3 py-1 bg-primary/10 text-primary text-xs font-bold rounded-full">Phần trăm</span>
                        @else
                        <span class="px-3 py-1 bg-secondary/10 text-secondary text-xs font-bold rounded-full">Cố định</span>
                        @endif
                    </td>
                    <td class="px-6 py-5">
                        <div class="text-on-background font-bold">
                            @if($promo->type === 'percentage')
                                {{ $promo->value }}%
                            @else
                                {{ number_format($promo->value) }}₫
                            @endif
                        </div>
                        <div class="text-xs text-on-surface-variant">Tối thiểu: {{ number_format($promo->min_order_amount) }}₫</div>
                    </td>
                    <td class="px-6 py-5">
                        <div class="text-sm text-on-background">{{ $promo->start_date->format('d/m/Y') }}</div>
                        <div class="text-xs text-on-surface-variant">đến {{ $promo->end_date->format('d/m/Y') }}</div>
                    </td>
                    <td class="px-6 py-5">
                        <div class="text-on-background font-bold">{{ $promo->used_count }}</div>
                        @if($promo->usage_limit)
                        <div class="text-xs text-on-surface-variant">/ {{ $promo->usage_limit }}</div>
                        @else
                        <div class="text-xs text-on-surface-variant">Không giới hạn</div>
                        @endif
                    </td>
                    <td class="px-6 py-5">
                        <button onclick="toggleStatus({{ $promo->id }})" class="toggle-btn-{{ $promo->id }}">
                            @if($promo->is_active)
                            <span class="px-3 py-1 bg-green-500/10 text-green-500 text-xs font-bold rounded-full cursor-pointer hover:bg-green-500/20">Hoạt động</span>
                            @else
                            <span class="px-3 py-1 bg-neutral-500/10 text-neutral-500 text-xs font-bold rounded-full cursor-pointer hover:bg-neutral-500/20">Tắt</span>
                            @endif
                        </button>
                    </td>
                    <td class="px-6 py-5 text-right">
                        <div class="flex justify-end gap-3">
                            <a href="{{ route('admin.promotions.edit', $promo->id) }}" class="p-2 hover:bg-surface-container-highest rounded-lg transition-all text-on-surface-variant hover:text-primary">
                                <span class="material-symbols-outlined text-xl">edit</span>
                            </a>
                            <button onclick="deletePromotion({{ $promo->id }})" class="p-2 hover:bg-surface-container-highest rounded-lg transition-all text-on-surface-variant hover:text-error">
                                <span class="material-symbols-outlined text-xl">delete</span>
                            </button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</section>

@push('styles')
<style>
.glass-panel {
    background: rgba(38, 38, 38, 0.4);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(72, 72, 71, 0.15);
}
</style>
@endpush

@push('scripts')
<script>
function toggleStatus(id) {
    fetch(`/admin/promotions/${id}/toggle`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    });
}

function deletePromotion(id) {
    if (!confirm('Bạn có chắc chắn muốn xóa khuyến mãi này?')) return;
    
    fetch(`/admin/promotions/${id}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    });
}
</script>
@endpush
@endsection
