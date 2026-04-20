@extends('layouts.admin')

@section('title', 'Reports - Admin')

@section('content')
<div class="p-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-white mb-2">Báo cáo</h1>
            <p class="text-neutral-400">Thống kê và phân tích dữ liệu</p>
        </div>
        
        <!-- Period Filter -->
        <form action="{{ route('admin.reports') }}" method="GET" class="flex gap-2">
            <select name="period" onchange="this.form.submit()" class="px-4 py-2 bg-surface-container-low text-white rounded-lg border border-neutral-800">
                <option value="today" {{ $period == 'today' ? 'selected' : '' }}>Hôm nay</option>
                <option value="week" {{ $period == 'week' ? 'selected' : '' }}>Tuần này</option>
                <option value="month" {{ $period == 'month' ? 'selected' : '' }}>Tháng này</option>
                <option value="year" {{ $period == 'year' ? 'selected' : '' }}>Năm này</option>
            </select>
        </form>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <!-- Total Revenue -->
        <div class="bg-surface-container-low p-6 rounded-xl">
            <div class="flex items-center gap-3 mb-2">
                <span class="material-symbols-outlined text-green-400">payments</span>
                <span class="text-neutral-400 text-sm">Doanh thu</span>
            </div>
            <h3 class="text-2xl font-bold text-white">{{ number_format($totalRevenue) }}₫</h3>
        </div>

        <!-- Total Orders -->
        <div class="bg-surface-container-low p-6 rounded-xl">
            <div class="flex items-center gap-3 mb-2">
                <span class="material-symbols-outlined text-blue-400">shopping_cart</span>
                <span class="text-neutral-400 text-sm">Đơn hàng</span>
            </div>
            <h3 class="text-2xl font-bold text-white">{{ $totalOrders }}</h3>
        </div>

        <!-- Total Products Sold -->
        <div class="bg-surface-container-low p-6 rounded-xl">
            <div class="flex items-center gap-3 mb-2">
                <span class="material-symbols-outlined text-purple-400">inventory_2</span>
                <span class="text-neutral-400 text-sm">Sản phẩm bán</span>
            </div>
            <h3 class="text-2xl font-bold text-white">{{ $totalProducts }}</h3>
        </div>

        <!-- New Customers -->
        <div class="bg-surface-container-low p-6 rounded-xl">
            <div class="flex items-center gap-3 mb-2">
                <span class="material-symbols-outlined text-orange-400">person_add</span>
                <span class="text-neutral-400 text-sm">Khách hàng mới</span>
            </div>
            <h3 class="text-2xl font-bold text-white">{{ $newCustomers }}</h3>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Revenue Chart -->
        <div class="bg-surface-container-low p-6 rounded-xl">
            <h3 class="text-xl font-bold text-white mb-4">Doanh thu theo ngày</h3>
            <div style="height: 300px;">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        <!-- Top Products Chart -->
        <div class="bg-surface-container-low p-6 rounded-xl">
            <h3 class="text-xl font-bold text-white mb-4">Sản phẩm bán chạy</h3>
            <div style="height: 300px;">
                <canvas id="productsChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Top Customers -->
    <div class="bg-surface-container-low p-6 rounded-xl">
        <h3 class="text-xl font-bold text-white mb-4">Khách hàng chi tiêu nhiều nhất</h3>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-neutral-800">
                        <th class="text-left py-3 px-4 text-neutral-400 font-medium">Tên</th>
                        <th class="text-left py-3 px-4 text-neutral-400 font-medium">Email</th>
                        <th class="text-left py-3 px-4 text-neutral-400 font-medium">Số điện thoại</th>
                        <th class="text-left py-3 px-4 text-neutral-400 font-medium">Hạng</th>
                        <th class="text-right py-3 px-4 text-neutral-400 font-medium">Tổng chi tiêu</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($topCustomers as $customer)
                    <tr class="border-b border-neutral-800/50 hover:bg-neutral-900/30">
                        <td class="py-3 px-4 text-white">{{ $customer->name }}</td>
                        <td class="py-3 px-4 text-neutral-400">{{ $customer->email }}</td>
                        <td class="py-3 px-4 text-neutral-400">{{ $customer->phone }}</td>
                        <td class="py-3 px-4">
                            <span class="px-2 py-1 rounded text-xs font-medium
                                @if($customer->tier == 'platinum') bg-purple-500/20 text-purple-400
                                @elseif($customer->tier == 'gold') bg-yellow-500/20 text-yellow-400
                                @elseif($customer->tier == 'silver') bg-gray-500/20 text-gray-400
                                @else bg-orange-500/20 text-orange-400
                                @endif">
                                {{ ucfirst($customer->tier) }}
                            </span>
                        </td>
                        <td class="py-3 px-4 text-right text-green-400 font-bold">{{ number_format($customer->total_spent) }}₫</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-8 text-center text-neutral-400">Chưa có dữ liệu</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Revenue Chart
const revenueCtx = document.getElementById('revenueChart').getContext('2d');
const revenueChart = new Chart(revenueCtx, {
    type: 'line',
    data: {
        labels: {!! json_encode($revenueByDay->map(fn($d) => \Carbon\Carbon::parse($d->date)->format('d/m'))) !!},
        datasets: [{
            label: 'Doanh thu (₫)',
            data: {!! json_encode($revenueByDay->pluck('revenue')) !!},
            borderColor: 'rgb(251, 146, 60)',
            backgroundColor: 'rgba(251, 146, 60, 0.1)',
            tension: 0.4,
            fill: true
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                labels: {
                    color: '#fff'
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    color: '#999',
                    callback: function(value) {
                        return new Intl.NumberFormat('vi-VN').format(value) + '₫';
                    }
                },
                grid: {
                    color: 'rgba(255, 255, 255, 0.1)'
                }
            },
            x: {
                ticks: {
                    color: '#999'
                },
                grid: {
                    color: 'rgba(255, 255, 255, 0.1)'
                }
            }
        }
    }
});

// Products Chart
const productsCtx = document.getElementById('productsChart').getContext('2d');
const productsChart = new Chart(productsCtx, {
    type: 'bar',
    data: {
        labels: {!! json_encode($topProducts->map(fn($p) => \Illuminate\Support\Str::limit($p->laptop->name ?? 'N/A', 20))) !!},
        datasets: [{
            label: 'Số lượng bán',
            data: {!! json_encode($topProducts->pluck('total_sold')) !!},
            backgroundColor: [
                'rgba(251, 146, 60, 0.8)',
                'rgba(59, 130, 246, 0.8)',
                'rgba(168, 85, 247, 0.8)',
                'rgba(34, 197, 94, 0.8)',
                'rgba(234, 179, 8, 0.8)',
                'rgba(239, 68, 68, 0.8)',
                'rgba(20, 184, 166, 0.8)',
                'rgba(249, 115, 22, 0.8)',
                'rgba(139, 92, 246, 0.8)',
                'rgba(236, 72, 153, 0.8)'
            ],
            borderColor: [
                'rgb(251, 146, 60)',
                'rgb(59, 130, 246)',
                'rgb(168, 85, 247)',
                'rgb(34, 197, 94)',
                'rgb(234, 179, 8)',
                'rgb(239, 68, 68)',
                'rgb(20, 184, 166)',
                'rgb(249, 115, 22)',
                'rgb(139, 92, 246)',
                'rgb(236, 72, 153)'
            ],
            borderWidth: 2
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    color: '#999',
                    stepSize: 1
                },
                grid: {
                    color: 'rgba(255, 255, 255, 0.1)'
                }
            },
            x: {
                ticks: {
                    color: '#999'
                },
                grid: {
                    display: false
                }
            }
        }
    }
});
</script>
@endsection
