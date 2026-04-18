@extends('layouts.admin')

@section('title', 'Báo cáo')

@section('content')
<!-- Header -->
<div class="flex justify-between items-end mb-8">
    <div>
        <h1 class="text-4xl font-extrabold tracking-tighter text-white font-headline">BÁO CÁO & PHÂN TÍCH</h1>
        <p class="text-neutral-500 mt-2">Thống kê chi tiết về doanh thu và hoạt động kinh doanh</p>
    </div>
    <div class="flex gap-3">
        <form method="GET" action="{{ route('admin.reports') }}" class="flex gap-2">
            <select name="period" onchange="this.form.submit()" class="px-4 py-2 rounded-lg bg-white text-black border border-outline-variant/15 focus:outline-none focus:ring-2 focus:ring-primary/40 font-bold">
                <option value="week" {{ $period == 'week' ? 'selected' : '' }}>7 ngày qua</option>
                <option value="month" {{ $period == 'month' ? 'selected' : '' }}>30 ngày qua</option>
                <option value="year" {{ $period == 'year' ? 'selected' : '' }}>1 năm qua</option>
            </select>
        </form>
        <button onclick="window.print()" class="px-6 py-2.5 rounded-full bg-secondary text-black font-bold text-sm hover:shadow-[0_0_20px_rgba(255,117,36,0.3)] transition-all flex items-center gap-2">
            <span class="material-symbols-outlined text-sm">print</span>
            In báo cáo
        </button>
    </div>
</div>

<!-- Stats Overview -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-surface-container-low p-6 rounded-xl border border-white/5">
        <div class="flex items-center gap-3 mb-3">
            <div class="p-2 bg-secondary/20 rounded-lg">
                <span class="material-symbols-outlined text-secondary text-xl">monetization_on</span>
            </div>
            <span class="text-neutral-500 text-sm font-medium">Tổng doanh thu</span>
        </div>
        <h3 class="text-2xl font-bold text-white font-headline">{{ number_format($totalRevenue) }}₫</h3>
    </div>

    <div class="bg-surface-container-low p-6 rounded-xl border border-white/5">
        <div class="flex items-center gap-3 mb-3">
            <div class="p-2 bg-primary/20 rounded-lg">
                <span class="material-symbols-outlined text-primary text-xl">shopping_bag</span>
            </div>
            <span class="text-neutral-500 text-sm font-medium">Tổng đơn hàng</span>
        </div>
        <h3 class="text-2xl font-bold text-white font-headline">{{ $totalOrders }}</h3>
    </div>

    <div class="bg-surface-container-low p-6 rounded-xl border border-white/5">
        <div class="flex items-center gap-3 mb-3">
            <div class="p-2 bg-green-500/20 rounded-lg">
                <span class="material-symbols-outlined text-green-400 text-xl">check_circle</span>
            </div>
            <span class="text-neutral-500 text-sm font-medium">Hoàn thành</span>
        </div>
        <h3 class="text-2xl font-bold text-white font-headline">{{ $completedOrders }}</h3>
    </div>

    <div class="bg-surface-container-low p-6 rounded-xl border border-white/5">
        <div class="flex items-center gap-3 mb-3">
            <div class="p-2 bg-error/20 rounded-lg">
                <span class="material-symbols-outlined text-error text-xl">cancel</span>
            </div>
            <span class="text-neutral-500 text-sm font-medium">Đã hủy</span>
        </div>
        <h3 class="text-2xl font-bold text-white font-headline">{{ $cancelledOrders }}</h3>
    </div>
</div>

<!-- Charts Section -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
    <!-- Revenue Chart -->
    <div class="bg-surface-container-low p-8 rounded-xl border border-white/5">
        <h2 class="text-xl font-bold text-white font-headline mb-6">Biểu đồ doanh thu</h2>
        <div class="h-64 flex items-end gap-2">
            @php
                $maxRevenue = $revenueByDate->max('revenue') ?: 1;
            @endphp
            @foreach($revenueByDate as $data)
                <div class="flex-1 flex flex-col items-center gap-2">
                    <div class="text-xs text-neutral-500 font-bold">{{ number_format($data->revenue / 1000000, 1) }}M</div>
                    <div class="w-full bg-primary/20 rounded-t-lg hover:bg-primary/40 transition-all cursor-pointer relative group" 
                         style="height: {{ ($data->revenue / $maxRevenue) * 200 }}px">
                        <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 px-3 py-2 bg-neutral-900 rounded-lg text-xs text-white opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap">
                            {{ number_format($data->revenue) }}₫
                        </div>
                    </div>
                    <div class="text-[10px] text-neutral-600 font-bold">{{ \Carbon\Carbon::parse($data->date)->format('d/m') }}</div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Orders by Status -->
    <div class="bg-surface-container-low p-8 rounded-xl border border-white/5">
        <h2 class="text-xl font-bold text-white font-headline mb-6">Đơn hàng theo trạng thái</h2>
        <div class="space-y-4">
            @php
                $statusLabels = [
                    'pending' => ['label' => 'Chờ xử lý', 'color' => 'yellow'],
                    'processing' => ['label' => 'Đang xử lý', 'color' => 'blue'],
                    'completed' => ['label' => 'Hoàn thành', 'color' => 'green'],
                    'cancelled' => ['label' => 'Đã hủy', 'color' => 'red']
                ];
                $totalStatusOrders = $ordersByStatus->sum();
            @endphp
            @foreach($statusLabels as $status => $info)
                @php
                    $count = $ordersByStatus[$status] ?? 0;
                    $percentage = $totalStatusOrders > 0 ? ($count / $totalStatusOrders) * 100 : 0;
                @endphp
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm text-white font-medium">{{ $info['label'] }}</span>
                        <span class="text-sm text-neutral-400">{{ $count }} ({{ number_format($percentage, 1) }}%)</span>
                    </div>
                    <div class="h-3 bg-neutral-800 rounded-full overflow-hidden">
                        <div class="h-full bg-{{ $info['color'] }}-500 rounded-full transition-all duration-1000" style="width: {{ $percentage }}%"></div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Top Products -->
<div class="bg-surface-container-low p-8 rounded-xl border border-white/5 mb-8">
    <h2 class="text-xl font-bold text-white font-headline mb-6">Top 10 sản phẩm bán chạy</h2>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b border-white/5">
                    <th class="text-left py-4 px-4 text-xs font-bold text-neutral-500 uppercase tracking-wider">#</th>
                    <th class="text-left py-4 px-4 text-xs font-bold text-neutral-500 uppercase tracking-wider">Sản phẩm</th>
                    <th class="text-right py-4 px-4 text-xs font-bold text-neutral-500 uppercase tracking-wider">Đã bán</th>
                    <th class="text-right py-4 px-4 text-xs font-bold text-neutral-500 uppercase tracking-wider">Doanh thu</th>
                </tr>
            </thead>
            <tbody>
                @foreach($topProducts as $index => $item)
                <tr class="border-b border-white/5 hover:bg-white/5 transition-colors">
                    <td class="py-4 px-4">
                        <span class="text-primary font-bold">{{ $index + 1 }}</span>
                    </td>
                    <td class="py-4 px-4">
                        <div class="flex items-center gap-4">
                            <img src="{{ $item->laptop->image ?? 'https://via.placeholder.com/60' }}" alt="{{ $item->laptop->name }}" class="w-12 h-12 object-cover rounded-lg">
                            <div>
                                <div class="text-white font-bold text-sm">{{ $item->laptop->name }}</div>
                                <div class="text-neutral-500 text-xs">{{ $item->laptop->brand->name ?? 'N/A' }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="py-4 px-4 text-right">
                        <span class="text-white font-bold">{{ $item->total_sold }}</span>
                    </td>
                    <td class="py-4 px-4 text-right">
                        <span class="text-secondary font-bold">{{ number_format($item->total_revenue) }}₫</span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Top Customers -->
<div class="bg-surface-container-low p-8 rounded-xl border border-white/5 mb-8">
    <h2 class="text-xl font-bold text-white font-headline mb-6">Top 10 khách hàng</h2>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b border-white/5">
                    <th class="text-left py-4 px-4 text-xs font-bold text-neutral-500 uppercase tracking-wider">#</th>
                    <th class="text-left py-4 px-4 text-xs font-bold text-neutral-500 uppercase tracking-wider">Khách hàng</th>
                    <th class="text-right py-4 px-4 text-xs font-bold text-neutral-500 uppercase tracking-wider">Số đơn</th>
                    <th class="text-right py-4 px-4 text-xs font-bold text-neutral-500 uppercase tracking-wider">Tổng chi tiêu</th>
                </tr>
            </thead>
            <tbody>
                @foreach($topCustomers as $index => $customer)
                <tr class="border-b border-white/5 hover:bg-white/5 transition-colors">
                    <td class="py-4 px-4">
                        <span class="text-primary font-bold">{{ $index + 1 }}</span>
                    </td>
                    <td class="py-4 px-4">
                        <div>
                            <div class="text-white font-bold text-sm">{{ $customer->customer_name }}</div>
                            <div class="text-neutral-500 text-xs">{{ $customer->customer_email }}</div>
                        </div>
                    </td>
                    <td class="py-4 px-4 text-right">
                        <span class="text-white font-bold">{{ $customer->order_count }}</span>
                    </td>
                    <td class="py-4 px-4 text-right">
                        <span class="text-secondary font-bold">{{ number_format($customer->total_spent) }}₫</span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Revenue by Category -->
<div class="bg-surface-container-low p-8 rounded-xl border border-white/5">
    <h2 class="text-xl font-bold text-white font-headline mb-6">Doanh thu theo danh mục</h2>
    <div class="space-y-4">
        @php
            $maxCategoryRevenue = $revenueByCategory->max('revenue') ?: 1;
        @endphp
        @foreach($revenueByCategory as $category)
            @php
                $percentage = ($category->revenue / $maxCategoryRevenue) * 100;
            @endphp
            <div>
                <div class="flex justify-between items-center mb-2">
                    <span class="text-sm text-white font-medium">{{ $category->category }}</span>
                    <span class="text-sm text-secondary font-bold">{{ number_format($category->revenue) }}₫</span>
                </div>
                <div class="h-3 bg-neutral-800 rounded-full overflow-hidden">
                    <div class="h-full bg-gradient-to-r from-primary to-secondary rounded-full transition-all duration-1000" style="width: {{ $percentage }}%"></div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
