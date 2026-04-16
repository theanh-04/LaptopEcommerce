@extends('layouts.admin')

@section('title', 'Quản lý Đơn hàng')

@section('content')
<!-- Header Section -->
<div class="flex flex-col md:flex-row md:items-end justify-between mb-10 gap-6">
    <div>
        <h2 class="text-4xl font-extrabold tracking-tighter text-white mb-2 font-headline">Quản Lý Đơn Hàng</h2>
        <p class="text-neutral-400 max-w-lg font-body">Theo dõi và xử lý đơn hàng theo thời gian thực.</p>
    </div>
    <div class="flex gap-4">
        <div class="bg-surface-container-low px-6 py-3 rounded-lg flex items-center gap-4 border border-outline-variant/10">
            <div class="w-10 h-10 rounded-full bg-secondary/10 flex items-center justify-center text-secondary">
                <span class="material-symbols-outlined">pending</span>
            </div>
            <div>
                <p class="text-xs text-neutral-500 uppercase font-bold tracking-wider">Chờ duyệt</p>
                <p class="text-xl font-bold font-headline text-secondary">{{ $pendingOrders->count() }}</p>
            </div>
        </div>
        <div class="bg-surface-container-low px-6 py-3 rounded-lg flex items-center gap-4 border border-outline-variant/10">
            <div class="w-10 h-10 rounded-full bg-cyan-400/10 flex items-center justify-center text-cyan-400">
                <span class="material-symbols-outlined">local_shipping</span>
            </div>
            <div>
                <p class="text-xs text-neutral-500 uppercase font-bold tracking-wider">Đang xử lý</p>
                <p class="text-xl font-bold font-headline text-cyan-400">{{ $processingOrders->count() }}</p>
            </div>
        </div>
        <div class="bg-surface-container-low px-6 py-3 rounded-lg flex items-center gap-4 border border-outline-variant/10">
            <div class="w-10 h-10 rounded-full bg-green-500/10 flex items-center justify-center text-green-500">
                <span class="material-symbols-outlined">check_circle</span>
            </div>
            <div>
                <p class="text-xs text-neutral-500 uppercase font-bold tracking-wider">Hoàn thành</p>
                <p class="text-xl font-bold font-headline text-green-500">{{ $shippedOrders->count() }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Filter Bar -->
<div class="bg-surface-container-low rounded-xl mb-6 overflow-hidden border border-outline-variant/5">
    <div class="p-6 flex items-end gap-4">
        <div class="w-64">
            <label class="block text-[10px] uppercase font-bold text-neutral-500 tracking-widest mb-2">Trạng thái</label>
            <select id="statusFilter" onchange="filterOrders()" class="bg-white border border-outline-variant/10 rounded-lg text-sm text-gray-900 focus:ring-1 focus:ring-cyan-400/50 w-full py-2.5 px-3 font-body cursor-pointer">
                <option value="all">Tất cả trạng thái</option>
                <option value="pending">Chờ duyệt</option>
                <option value="processing">Đang xử lý</option>
                <option value="completed">Hoàn thành</option>
                <option value="cancelled">Đã hủy</option>
            </select>
        </div>
        
        <div class="flex-1">
            <div class="relative">
                <input id="searchInput" type="text" placeholder="Tìm kiếm theo mã đơn, tên khách hàng, email, số điện thoại..." class="bg-white border border-outline-variant/10 rounded-lg text-sm text-gray-900 placeholder-neutral-400 focus:ring-1 focus:ring-cyan-400/50 py-2.5 pl-10 pr-4 font-body w-full" onkeyup="filterOrders()">
                <span class="material-symbols-outlined absolute left-3 bottom-3 text-neutral-400 text-sm">search</span>
            </div>
        </div>
    </div>
</div>

<!-- Orders Table -->
<div class="bg-surface-container-low rounded-xl overflow-hidden border border-outline-variant/5">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-surface-container-high/50 border-b border-neutral-800/30">
                <th class="px-6 py-5 text-xs uppercase font-bold text-neutral-500 tracking-widest">Mã đơn</th>
                <th class="px-6 py-5 text-xs uppercase font-bold text-neutral-500 tracking-widest">Khách hàng</th>
                <th class="px-6 py-5 text-xs uppercase font-bold text-neutral-500 tracking-widest">Liên hệ</th>
                <th class="px-6 py-5 text-xs uppercase font-bold text-neutral-500 tracking-widest">Sản phẩm</th>
                <th class="px-6 py-5 text-xs uppercase font-bold text-neutral-500 tracking-widest">Tổng tiền</th>
                <th class="px-6 py-5 text-xs uppercase font-bold text-neutral-500 tracking-widest">Trạng thái</th>
                <th class="px-6 py-5 text-xs uppercase font-bold text-neutral-500 tracking-widest text-right">Hành động</th>
            </tr>
        </thead>
        <tbody id="ordersTableBody" class="divide-y divide-neutral-800/20">
            @php
                $allOrders = $pendingOrders->concat($processingOrders)->concat($shippedOrders);
            @endphp
            @foreach($allOrders as $order)
            <tr class="hover:bg-surface-container-high/30 transition-colors group" data-order-id="{{ $order->id }}" data-status="{{ $order->status }}">
                <td class="px-6 py-5">
                    <span class="text-cyan-400 font-bold font-mono text-sm">#{{ $order->order_number }}</span>
                </td>
                <td class="px-6 py-5">
                    <p class="font-bold text-white">{{ $order->customer_name }}</p>
                </td>
                <td class="px-6 py-5">
                    <p class="text-sm text-neutral-400">{{ $order->customer_email }}</p>
                    <p class="text-sm text-neutral-400">{{ $order->customer_phone }}</p>
                </td>
                <td class="px-6 py-5">
                    <span class="text-white font-bold">{{ $order->items_count }}x</span>
                    <span class="text-neutral-400 text-sm">sản phẩm</span>
                </td>
                <td class="px-6 py-5">
                    <p class="font-bold text-white text-lg">{{ number_format($order->total) }}₫</p>
                </td>
                <td class="px-6 py-5">
                    @if($order->status == 'pending')
                        <span class="px-3 py-1 bg-secondary/10 text-secondary text-xs font-bold rounded-full">Chờ duyệt</span>
                    @elseif($order->status == 'processing')
                        <span class="px-3 py-1 bg-cyan-400/10 text-cyan-400 text-xs font-bold rounded-full">Đang xử lý</span>
                    @elseif($order->status == 'completed')
                        <span class="px-3 py-1 bg-green-500/10 text-green-500 text-xs font-bold rounded-full">Hoàn thành</span>
                    @else
                        <span class="px-3 py-1 bg-red-500/10 text-red-500 text-xs font-bold rounded-full">Đã hủy</span>
                    @endif
                </td>
                <td class="px-6 py-5">
                    <div class="flex justify-end gap-2">
                        <button onclick="viewOrderDetail({{ $order->id }})" class="p-2 text-neutral-500 hover:text-cyan-400 hover:bg-cyan-400/10 rounded-lg transition-all" title="Xem chi tiết">
                            <span class="material-symbols-outlined text-sm">visibility</span>
                        </button>
                        @if($order->status == 'pending')
                        <button onclick="updateStatus({{ $order->id }}, 'processing')" class="p-2 text-neutral-500 hover:text-cyan-400 hover:bg-cyan-400/10 rounded-lg transition-all" title="Xử lý">
                            <span class="material-symbols-outlined text-sm">play_arrow</span>
                        </button>
                        <button onclick="cancelOrder({{ $order->id }})" class="p-2 text-neutral-500 hover:text-red-500 hover:bg-red-500/10 rounded-lg transition-all" title="Hủy đơn">
                            <span class="material-symbols-outlined text-sm">cancel</span>
                        </button>
                        @elseif($order->status == 'processing')
                        <button onclick="updateStatus({{ $order->id }}, 'completed')" class="p-2 text-neutral-500 hover:text-green-500 hover:bg-green-500/10 rounded-lg transition-all" title="Hoàn thành">
                            <span class="material-symbols-outlined text-sm">check_circle</span>
                        </button>
                        <button onclick="cancelOrder({{ $order->id }})" class="p-2 text-neutral-500 hover:text-red-500 hover:bg-red-500/10 rounded-lg transition-all" title="Hủy đơn">
                            <span class="material-symbols-outlined text-sm">cancel</span>
                        </button>
                        @endif
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Order Detail Modal -->
<div id="orderDetailModal" class="fixed inset-0 bg-black/70 backdrop-blur-md z-50 hidden flex items-center justify-center">
    <div class="bg-neutral-900 rounded-xl w-full max-w-2xl max-h-[90vh] overflow-hidden shadow-2xl border border-neutral-800">
        <div class="p-6 bg-neutral-950 border-b border-neutral-800 flex justify-between items-center">
            <h2 class="text-2xl font-headline font-bold text-white">Chi tiết đơn hàng</h2>
            <button onclick="closeModal()" class="p-2 hover:bg-neutral-800 rounded-lg transition-colors text-neutral-400 hover:text-white">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <div id="orderDetailContent" class="p-6 overflow-y-auto max-h-[calc(90vh-80px)] bg-neutral-900">
            <!-- Content will be loaded here -->
        </div>
    </div>
</div>

@push('scripts')
<script>
function filterOrders() {
    const status = document.getElementById('statusFilter').value;
    const search = document.getElementById('searchInput').value.toLowerCase();
    const rows = document.querySelectorAll('#ordersTableBody tr');
    
    rows.forEach(row => {
        const orderStatus = row.dataset.status;
        const text = row.textContent.toLowerCase();
        
        const statusMatch = status === 'all' || orderStatus === status;
        const searchMatch = text.includes(search);
        
        row.style.display = (statusMatch && searchMatch) ? '' : 'none';
    });
}

function viewOrderDetail(orderId) {
    fetch(`/admin/orders/${orderId}`)
        .then(response => response.json())
        .then(data => {
            renderOrderDetail(data);
            document.getElementById('orderDetailModal').classList.remove('hidden');
        });
}

function closeModal() {
    document.getElementById('orderDetailModal').classList.add('hidden');
}

function renderOrderDetail(order) {
    const statusBadge = order.status === 'pending' 
        ? '<span class="px-3 py-1 bg-secondary/10 text-secondary text-xs font-bold rounded-full">Chờ duyệt</span>'
        : order.status === 'processing'
        ? '<span class="px-3 py-1 bg-cyan-400/10 text-cyan-400 text-xs font-bold rounded-full">Đang xử lý</span>'
        : order.status === 'completed'
        ? '<span class="px-3 py-1 bg-green-500/10 text-green-500 text-xs font-bold rounded-full">Hoàn thành</span>'
        : '<span class="px-3 py-1 bg-red-500/10 text-red-500 text-xs font-bold rounded-full">Đã hủy</span>';
    
    const content = `
        <div class="space-y-6">
            <div class="flex items-center justify-between p-4 bg-neutral-800 rounded-lg border border-neutral-700">
                <div>
                    <p class="text-xs text-cyan-400 uppercase tracking-widest font-bold mb-1">Mã đơn hàng</p>
                    <p class="text-2xl font-headline font-bold text-white">#${order.order_number}</p>
                </div>
                ${statusBadge}
            </div>
            
            <div class="grid grid-cols-2 gap-4">
                <div class="p-4 bg-neutral-800 rounded-lg border border-neutral-700">
                    <p class="text-xs text-cyan-400 uppercase font-bold mb-3">Khách hàng</p>
                    <p class="text-white font-bold text-lg">${order.customer_name}</p>
                    <p class="text-sm text-neutral-300 mt-2">${order.customer_email}</p>
                    <p class="text-sm text-neutral-300">${order.customer_phone}</p>
                </div>
                <div class="p-4 bg-neutral-800 rounded-lg border border-neutral-700">
                    <p class="text-xs text-cyan-400 uppercase font-bold mb-3">Địa chỉ giao hàng</p>
                    <p class="text-sm text-neutral-300 leading-relaxed">${order.customer_address}</p>
                </div>
            </div>
            
            <div>
                <p class="text-xs text-cyan-400 uppercase font-bold mb-3">Sản phẩm</p>
                <div class="space-y-3">
                    ${order.items.map(item => `
                        <div class="flex justify-between items-center p-4 bg-neutral-800 rounded-lg border border-neutral-700">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 rounded-lg bg-cyan-400/10 flex items-center justify-center border border-cyan-400/30">
                                    <span class="text-cyan-400 font-bold">${item.quantity}x</span>
                                </div>
                                <p class="text-white font-medium">${item.laptop_name}</p>
                            </div>
                            <p class="text-white font-bold text-lg">${new Intl.NumberFormat('vi-VN').format(item.price * item.quantity)}₫</p>
                        </div>
                    `).join('')}
                </div>
            </div>
            
            <div class="pt-4 border-t border-neutral-700">
                <div class="flex justify-between items-center p-4 bg-neutral-800 rounded-lg border border-cyan-400/30">
                    <span class="text-cyan-400 uppercase text-sm font-bold">Tổng cộng</span>
                    <span class="text-3xl font-bold text-white">${new Intl.NumberFormat('vi-VN').format(order.total_amount)}₫</span>
                </div>
            </div>
        </div>
    `;
    document.getElementById('orderDetailContent').innerHTML = content;
}

function updateStatus(orderId, newStatus) {
    const statusText = newStatus === 'processing' ? 'xử lý' : 'hoàn thành';
    const message = newStatus === 'processing' 
        ? 'Chuyển sang xử lý sẽ trừ số lượng sản phẩm trong kho. Bạn có chắc chắn?'
        : `Bạn có chắc chắn muốn chuyển đơn hàng sang trạng thái "${statusText}"?`;
    
    if (!confirm(message)) return;
    
    fetch(`/admin/orders/${orderId}/status`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ status: newStatus })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert(data.message || 'Có lỗi xảy ra');
        }
    });
}

function cancelOrder(orderId) {
    if (!confirm('Hủy đơn hàng sẽ hoàn lại số lượng vào kho (nếu đã trừ). Bạn có chắc chắn?')) return;
    
    fetch(`/admin/orders/${orderId}/status`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ status: 'cancelled' })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert(data.message || 'Có lỗi xảy ra');
        }
    });
}
</script>
@endpush
@endsection
