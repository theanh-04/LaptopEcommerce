@extends('layouts.admin')

@section('title', 'Quản lý Khách hàng')

@section('content')
<!-- Header -->
<header class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-6">
    <div>
        <h1 class="text-4xl font-bold tracking-tight text-on-background mb-2">Quản lý Khách hàng</h1>
        <p class="text-on-surface-variant max-w-lg">Quản lý thông tin khách hàng và chương trình khách hàng thân thiết</p>
    </div>
    <a href="{{ route('admin.customers.create') }}" class="px-6 py-3 bg-primary text-black rounded-xl font-bold text-sm hover:bg-primary/90 transition-all flex items-center gap-2 w-fit">
        <span class="material-symbols-outlined text-sm">add</span>
        Thêm khách hàng
    </a>
</header>

<!-- Stats -->
<section class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="surface-container-low rounded-xl p-6 glass-panel">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-lg bg-primary/10 flex items-center justify-center">
                <span class="material-symbols-outlined text-primary">group</span>
            </div>
            <div>
                <p class="text-xs text-on-surface-variant uppercase font-bold">Tổng khách hàng</p>
                <p class="text-2xl font-bold text-on-background">{{ $totalCustomers }}</p>
            </div>
        </div>
    </div>
    <div class="surface-container-low rounded-xl p-6 glass-panel">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-lg bg-green-500/10 flex items-center justify-center">
                <span class="material-symbols-outlined text-green-500">trending_up</span>
            </div>
            <div>
                <p class="text-xs text-on-surface-variant uppercase font-bold">Hoạt động (30 ngày)</p>
                <p class="text-2xl font-bold text-on-background">{{ $activeCustomers }}</p>
            </div>
        </div>
    </div>
    <div class="surface-container-low rounded-xl p-6 glass-panel">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-lg bg-secondary/10 flex items-center justify-center">
                <span class="material-symbols-outlined text-secondary">payments</span>
            </div>
            <div>
                <p class="text-xs text-on-surface-variant uppercase font-bold">Tổng doanh thu</p>
                <p class="text-2xl font-bold text-on-background">{{ number_format($totalRevenue) }}₫</p>
            </div>
        </div>
    </div>
    <div class="surface-container-low rounded-xl p-6 glass-panel">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-lg bg-cyan-400/10 flex items-center justify-center">
                <span class="material-symbols-outlined text-cyan-400">shopping_cart</span>
            </div>
            <div>
                <p class="text-xs text-on-surface-variant uppercase font-bold">Giá trị TB/đơn</p>
                <p class="text-2xl font-bold text-on-background">{{ number_format($avgOrderValue ?? 0) }}₫</p>
            </div>
        </div>
    </div>
</section>

<!-- Search & Filter -->
<section class="mb-6 flex flex-col md:flex-row gap-4">
    <div class="flex-1 relative">
        <input type="text" id="searchInput" placeholder="Tìm theo tên, email, số điện thoại..." class="w-full bg-surface-container-low border border-outline-variant/10 rounded-lg px-4 py-3 pl-12 text-on-background placeholder-on-surface-variant focus:ring-1 focus:ring-primary">
        <span class="material-symbols-outlined absolute left-4 top-3.5 text-on-surface-variant">search</span>
    </div>
    <select id="tierFilter" class="bg-surface-container-low border border-outline-variant/10 rounded-lg px-4 py-3 text-on-background focus:ring-1 focus:ring-primary">
        <option value="all">Tất cả hạng</option>
        <option value="bronze">Đồng</option>
        <option value="silver">Bạc</option>
        <option value="gold">Vàng</option>
        <option value="platinum">Bạch Kim</option>
    </select>
</section>

<!-- Customers Table -->
<section class="surface-container-low rounded-xl overflow-hidden glass-panel">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-surface-container-high/50 text-on-surface-variant text-xs uppercase tracking-widest font-bold">
                    <th class="px-6 py-5">Khách hàng</th>
                    <th class="px-6 py-5">Liên hệ</th>
                    <th class="px-6 py-5">Hạng</th>
                    <th class="px-6 py-5">Điểm tích lũy</th>
                    <th class="px-6 py-5">Tổng chi tiêu</th>
                    <th class="px-6 py-5">Đơn hàng</th>
                    <th class="px-6 py-5">Mua gần nhất</th>
                    <th class="px-6 py-5 text-right">Thao tác</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-outline-variant/10" id="customersTable">
                @foreach($customers as $customer)
                <tr class="hover:bg-surface-container-high/40 transition-colors customer-row" data-id="{{ $customer->id }}">
                    <td class="px-6 py-5">
                        <div>
                            <div class="font-bold text-on-background">{{ $customer->name }}</div>
                            <div class="text-xs text-on-surface-variant">ID: #{{ str_pad($customer->id, 6, '0', STR_PAD_LEFT) }}</div>
                        </div>
                    </td>
                    <td class="px-6 py-5">
                        <div class="text-sm text-on-background">{{ $customer->email }}</div>
                        <div class="text-xs text-on-surface-variant">{{ $customer->phone ?? 'N/A' }}</div>
                    </td>
                    <td class="px-6 py-5">
                        <span class="px-3 py-1 {{ $customer->tier_color }} text-xs font-bold rounded-full">
                            {{ $customer->tier_name }}
                        </span>
                    </td>
                    <td class="px-6 py-5">
                        <div class="text-on-background font-bold">{{ number_format($customer->loyalty_points) }}</div>
                    </td>
                    <td class="px-6 py-5">
                        <div class="text-on-background font-bold">{{ number_format($customer->total_spent) }}₫</div>
                    </td>
                    <td class="px-6 py-5">
                        <div class="text-on-background font-bold">{{ $customer->total_orders }}</div>
                    </td>
                    <td class="px-6 py-5">
                        <div class="text-sm text-on-background">
                            {{ $customer->last_purchase_at ? $customer->last_purchase_at->format('d/m/Y') : 'Chưa mua' }}
                        </div>
                    </td>
                    <td class="px-6 py-5 text-right">
                        <div class="flex justify-end gap-3">
                            <button onclick="viewCustomer({{ $customer->id }})" class="p-2 hover:bg-surface-container-highest rounded-lg transition-all text-on-surface-variant hover:text-cyan-400">
                                <span class="material-symbols-outlined text-xl">visibility</span>
                            </button>
                            <a href="{{ route('admin.customers.edit', $customer->id) }}" class="p-2 hover:bg-surface-container-highest rounded-lg transition-all text-on-surface-variant hover:text-primary">
                                <span class="material-symbols-outlined text-xl">edit</span>
                            </a>
                            <button onclick="deleteCustomer({{ $customer->id }})" class="p-2 hover:bg-surface-container-highest rounded-lg transition-all text-on-surface-variant hover:text-error">
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

<!-- Customer Detail Modal -->
<div id="customerModal" class="fixed inset-0 bg-black/70 backdrop-blur-md z-50 hidden flex items-center justify-center">
    <div class="bg-neutral-900 rounded-xl w-full max-w-2xl overflow-hidden shadow-2xl border border-neutral-800 max-h-[90vh] overflow-y-auto">
        <div class="p-6 bg-neutral-950 border-b border-neutral-800 flex justify-between items-center sticky top-0 z-10">
            <h2 class="text-2xl font-headline font-bold text-white">Chi tiết khách hàng</h2>
            <button onclick="closeCustomerModal()" class="p-2 hover:bg-neutral-800 rounded-lg transition-colors text-neutral-400 hover:text-white">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <div id="customerDetail" class="p-6"></div>
    </div>
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

@push('scripts')
<script>
let allCustomers = @json($customers);

document.getElementById('searchInput').addEventListener('input', filterCustomers);
document.getElementById('tierFilter').addEventListener('change', filterCustomers);

function filterCustomers() {
    const search = document.getElementById('searchInput').value.toLowerCase();
    const tier = document.getElementById('tierFilter').value;

    fetch(`{{ route('admin.customers.search') }}?search=${search}&tier=${tier}`)
        .then(response => response.json())
        .then(customers => {
            renderCustomers(customers);
        });
}

function renderCustomers(customers) {
    const tbody = document.getElementById('customersTable');
    tbody.innerHTML = customers.map(customer => `
        <tr class="hover:bg-surface-container-high/40 transition-colors customer-row" data-id="${customer.id}">
            <td class="px-6 py-5">
                <div>
                    <div class="font-bold text-on-background">${customer.name}</div>
                    <div class="text-xs text-on-surface-variant">ID: #${String(customer.id).padStart(6, '0')}</div>
                </div>
            </td>
            <td class="px-6 py-5">
                <div class="text-sm text-on-background">${customer.email}</div>
                <div class="text-xs text-on-surface-variant">${customer.phone || 'N/A'}</div>
            </td>
            <td class="px-6 py-5">
                <span class="px-3 py-1 ${getTierColor(customer.tier)} text-xs font-bold rounded-full">
                    ${getTierName(customer.tier)}
                </span>
            </td>
            <td class="px-6 py-5">
                <div class="text-on-background font-bold">${formatNumber(customer.loyalty_points)}</div>
            </td>
            <td class="px-6 py-5">
                <div class="text-on-background font-bold">${formatNumber(customer.total_spent)}₫</div>
            </td>
            <td class="px-6 py-5">
                <div class="text-on-background font-bold">${customer.total_orders}</div>
            </td>
            <td class="px-6 py-5">
                <div class="text-sm text-on-background">
                    ${customer.last_purchase_at ? new Date(customer.last_purchase_at).toLocaleDateString('vi-VN') : 'Chưa mua'}
                </div>
            </td>
            <td class="px-6 py-5 text-right">
                <div class="flex justify-end gap-3">
                    <button onclick="viewCustomer(${customer.id})" class="p-2 hover:bg-surface-container-highest rounded-lg transition-all text-on-surface-variant hover:text-cyan-400">
                        <span class="material-symbols-outlined text-xl">visibility</span>
                    </button>
                    <a href="/admin/customers/${customer.id}/edit" class="p-2 hover:bg-surface-container-highest rounded-lg transition-all text-on-surface-variant hover:text-primary">
                        <span class="material-symbols-outlined text-xl">edit</span>
                    </a>
                    <button onclick="deleteCustomer(${customer.id})" class="p-2 hover:bg-surface-container-highest rounded-lg transition-all text-on-surface-variant hover:text-error">
                        <span class="material-symbols-outlined text-xl">delete</span>
                    </button>
                </div>
            </td>
        </tr>
    `).join('');
}

function getTierColor(tier) {
    const colors = {
        'bronze': 'bg-orange-900/20 text-orange-400',
        'silver': 'bg-gray-400/20 text-gray-300',
        'gold': 'bg-yellow-500/20 text-yellow-400',
        'platinum': 'bg-cyan-400/20 text-cyan-400'
    };
    return colors[tier] || 'bg-neutral-500/20 text-neutral-400';
}

function getTierName(tier) {
    const names = {
        'bronze': 'Đồng',
        'silver': 'Bạc',
        'gold': 'Vàng',
        'platinum': 'Bạch Kim'
    };
    return names[tier] || 'N/A';
}

function formatNumber(num) {
    return new Intl.NumberFormat('vi-VN').format(num);
}

function viewCustomer(id) {
    fetch(`/admin/customers/${id}/detail`)
        .then(response => response.json())
        .then(data => {
            const customer = data.customer;
            const orders = data.orders;
            
            document.getElementById('customerDetail').innerHTML = `
                <div class="space-y-6">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs text-cyan-400 uppercase font-bold mb-1">Tên khách hàng</p>
                            <p class="text-white font-medium">${customer.name}</p>
                        </div>
                        <div>
                            <p class="text-xs text-cyan-400 uppercase font-bold mb-1">Email</p>
                            <p class="text-white font-medium">${customer.email}</p>
                        </div>
                        <div>
                            <p class="text-xs text-cyan-400 uppercase font-bold mb-1">Số điện thoại</p>
                            <p class="text-white font-medium">${customer.phone || 'N/A'}</p>
                        </div>
                        <div>
                            <p class="text-xs text-cyan-400 uppercase font-bold mb-1">Hạng thành viên</p>
                            <span class="px-3 py-1 ${getTierColor(customer.tier)} text-xs font-bold rounded-full inline-block">
                                ${getTierName(customer.tier)}
                            </span>
                        </div>
                        <div>
                            <p class="text-xs text-cyan-400 uppercase font-bold mb-1">Điểm tích lũy</p>
                            <p class="text-white font-medium">${formatNumber(customer.loyalty_points)}</p>
                        </div>
                        <div>
                            <p class="text-xs text-cyan-400 uppercase font-bold mb-1">Tổng chi tiêu</p>
                            <p class="text-white font-medium">${formatNumber(customer.total_spent)}₫</p>
                        </div>
                    </div>
                    
                    <div>
                        <p class="text-xs text-cyan-400 uppercase font-bold mb-1">Địa chỉ</p>
                        <p class="text-white font-medium">${customer.address || 'N/A'}</p>
                    </div>
                    
                    <div>
                        <h3 class="text-lg font-bold text-white mb-4">Lịch sử mua hàng gần đây</h3>
                        <div class="space-y-2">
                            ${orders.length > 0 ? orders.map(order => `
                                <div class="bg-neutral-800 rounded-lg p-4 flex justify-between items-center">
                                    <div>
                                        <p class="text-white font-bold">${order.order_number}</p>
                                        <p class="text-xs text-neutral-400">${order.created_at}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-white font-bold">${formatNumber(order.total_amount)}₫</p>
                                        <span class="text-xs px-2 py-1 rounded ${order.status === 'completed' ? 'bg-green-500/20 text-green-400' : 'bg-yellow-500/20 text-yellow-400'}">
                                            ${order.status}
                                        </span>
                                    </div>
                                </div>
                            `).join('') : '<p class="text-neutral-400 text-center py-4">Chưa có đơn hàng nào</p>'}
                        </div>
                    </div>
                </div>
            `;
            
            document.getElementById('customerModal').classList.remove('hidden');
        });
}

function closeCustomerModal() {
    document.getElementById('customerModal').classList.add('hidden');
}

function deleteCustomer(id) {
    if (!confirm('Bạn có chắc chắn muốn xóa khách hàng này?')) return;
    
    fetch(`/admin/customers/${id}`, {
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
