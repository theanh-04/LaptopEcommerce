<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hóa đơn #{{ $order->order_number }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Arial', sans-serif; padding: 40px; background: #f5f5f5; }
        .invoice { max-width: 800px; margin: 0 auto; background: white; padding: 60px; box-shadow: 0 0 20px rgba(0,0,0,0.1); }
        .header { text-align: center; margin-bottom: 40px; border-bottom: 3px solid #00F5FF; padding-bottom: 20px; }
        .company-name { font-size: 32px; font-weight: bold; color: #00F5FF; margin-bottom: 5px; }
        .company-info { color: #666; font-size: 14px; }
        .invoice-title { font-size: 28px; font-weight: bold; text-align: center; margin: 30px 0; color: #333; }
        .invoice-meta { display: flex; justify-between; margin-bottom: 40px; }
        .meta-block { flex: 1; }
        .meta-label { font-weight: bold; color: #00F5FF; margin-bottom: 5px; }
        .meta-value { color: #333; margin-bottom: 8px; }
        .section-title { font-size: 18px; font-weight: bold; color: #00F5FF; margin: 30px 0 15px; border-bottom: 2px solid #00F5FF; padding-bottom: 8px; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th { background: #00F5FF; color: white; padding: 12px; text-align: left; font-weight: bold; }
        td { padding: 12px; border-bottom: 1px solid #eee; }
        .text-right { text-align: right; }
        .total-section { margin-top: 30px; }
        .total-row { display: flex; justify-between; padding: 10px 0; font-size: 16px; }
        .total-row.grand { font-size: 24px; font-weight: bold; color: #00F5FF; border-top: 3px solid #00F5FF; padding-top: 15px; margin-top: 15px; }
        .footer { margin-top: 60px; text-align: center; color: #666; font-size: 12px; border-top: 1px solid #eee; padding-top: 20px; }
        .signature { margin-top: 60px; display: flex; justify-between; }
        .signature-block { text-align: center; flex: 1; }
        .signature-line { border-top: 1px solid #333; margin-top: 60px; padding-top: 10px; font-weight: bold; }
        @media print {
            body { background: white; padding: 0; }
            .invoice { box-shadow: none; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="invoice">
        <!-- Header -->
        <div class="header">
            <div class="company-name">NEON KINETIC</div>
            <div class="company-info">
                Địa chỉ: 123 Đường ABC, Quận XYZ, TP.HCM<br>
                Điện thoại: 0123-456-789 | Email: info@neonkinetic.com<br>
                Website: www.neonkinetic.com
            </div>
        </div>

        <div class="invoice-title">HÓA ĐƠN BÁN HÀNG</div>

        <!-- Invoice Meta -->
        <div class="invoice-meta">
            <div class="meta-block">
                <div class="meta-label">Mã đơn hàng:</div>
                <div class="meta-value">#{{ $order->order_number }}</div>
                <div class="meta-label">Ngày đặt:</div>
                <div class="meta-value">{{ $order->created_at->format('d/m/Y H:i') }}</div>
                @if($order->employee)
                <div class="meta-label">Nhân viên xử lý:</div>
                <div class="meta-value">{{ $order->employee->name }}</div>
                @endif
            </div>
            <div class="meta-block" style="text-align: right;">
                <div class="meta-label">Khách hàng:</div>
                <div class="meta-value">{{ $order->customer_name }}</div>
                <div class="meta-label">Điện thoại:</div>
                <div class="meta-value">{{ $order->customer_phone }}</div>
                <div class="meta-label">Email:</div>
                <div class="meta-value">{{ $order->customer_email }}</div>
            </div>
        </div>

        <!-- Shipping Address -->
        <div class="section-title">Địa chỉ giao hàng</div>
        <p style="color: #333; line-height: 1.6;">{{ $order->customer_address }}</p>

        <!-- Order Items -->
        <div class="section-title">Chi tiết đơn hàng</div>
        <table>
            <thead>
                <tr>
                    <th>STT</th>
                    <th>Sản phẩm</th>
                    <th class="text-right">Đơn giá</th>
                    <th class="text-right">Số lượng</th>
                    <th class="text-right">Thành tiền</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->orderItems as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->laptop->name }}</td>
                    <td class="text-right">{{ number_format($item->price) }}₫</td>
                    <td class="text-right">{{ $item->quantity }}</td>
                    <td class="text-right">{{ number_format($item->price * $item->quantity) }}₫</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Total Section -->
        <div class="total-section">
            @php
                $subtotal = $order->orderItems->sum(function($item) {
                    return $item->price * $item->quantity;
                });
            @endphp
            <div class="total-row">
                <span>Tạm tính:</span>
                <span>{{ number_format($subtotal) }}₫</span>
            </div>
            @if($order->discount_amount > 0)
            <div class="total-row">
                <span>Giảm giá ({{ $order->promo_code }}):</span>
                <span style="color: #22c55e;">-{{ number_format($order->discount_amount) }}₫</span>
            </div>
            @endif
            <div class="total-row">
                <span>Phí vận chuyển:</span>
                <span>{{ $order->shipping_fee > 0 ? number_format($order->shipping_fee) . '₫' : 'MIỄN PHÍ' }}</span>
            </div>
            <div class="total-row grand">
                <span>TỔNG CỘNG:</span>
                <span>{{ number_format($order->total_amount) }}₫</span>
            </div>
        </div>

        <!-- Payment Info -->
        <div class="section-title">Thông tin thanh toán</div>
        <p style="color: #333; line-height: 1.6;">
            <strong>Phương thức:</strong> Thanh toán khi nhận hàng (COD)<br>
            <strong>Trạng thái:</strong> 
            @if($order->status == 'pending')
                Chờ xử lý
            @elseif($order->status == 'processing')
                Đang xử lý
            @elseif($order->status == 'completed')
                Đã hoàn thành
            @else
                Đã hủy
            @endif
        </p>

        <!-- Signatures -->
        <div class="signature">
            <div class="signature-block">
                <div>Người mua hàng</div>
                <div class="signature-line">{{ $order->customer_name }}</div>
            </div>
            <div class="signature-block">
                <div>Người bán hàng</div>
                <div class="signature-line">{{ $order->employee->name ?? 'NEON KINETIC' }}</div>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            Cảm ơn quý khách đã tin tưởng và sử dụng sản phẩm của NEON KINETIC!<br>
            Mọi thắc mắc xin vui lòng liên hệ hotline: 0123-456-789
        </div>

        <!-- Print Button -->
        <div class="no-print" style="text-align: center; margin-top: 30px;">
            <button onclick="window.print()" style="background: #00F5FF; color: white; border: none; padding: 15px 40px; font-size: 16px; font-weight: bold; border-radius: 8px; cursor: pointer;">
                In hóa đơn
            </button>
        </div>
    </div>
</body>
</html>
