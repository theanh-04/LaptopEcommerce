<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function checkout()
    {
        $cart = session()->get('cart', []);
        if(empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng trống!');
        }
        return view('orders.checkout', compact('cart'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email',
            'customer_phone' => 'required|string|max:20',
            'customer_address' => 'required|string',
            'promo_code' => 'nullable|string'
        ]);

        $cart = session()->get('cart', []);
        if(empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng trống!');
        }

        $subtotal = 0;
        foreach($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        // Calculate shipping fee (free if > 10M, else 30k)
        $shippingFee = $subtotal >= 10000000 ? 0 : 30000;

        // Apply promo code
        $discount = 0;
        $promoCode = null;
        if ($request->promo_code) {
            $promotion = \App\Models\Promotion::where('code', strtoupper($request->promo_code))->first();
            if ($promotion && $promotion->isValid()) {
                if ($subtotal >= $promotion->min_order_amount) {
                    $discount = $promotion->calculateDiscount($subtotal);
                    $promoCode = $promotion->code;
                    $promotion->increment('used_count');
                }
            }
        }

        $total = $subtotal - $discount + $shippingFee;

        // Generate order number
        $orderNumber = 'NK-' . date('ymd') . '-' . str_pad(Order::count() + 1, 4, '0', STR_PAD_LEFT);

        $order = Order::create([
            'order_number' => $orderNumber,
            'user_id' => auth()->id(),
            'customer_name' => $validated['customer_name'],
            'customer_email' => $validated['customer_email'],
            'customer_phone' => $validated['customer_phone'],
            'customer_address' => $validated['customer_address'],
            'total_amount' => $total,
            'promo_code' => $promoCode,
            'discount_amount' => $discount,
            'shipping_fee' => $shippingFee,
            'status' => 'pending'
        ]);

        foreach($cart as $id => $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'laptop_id' => $id,
                'quantity' => $item['quantity'],
                'price' => $item['price']
            ]);
        }

        session()->forget('cart');
        return redirect()->route('order.success')->with([
            'order_number' => $orderNumber,
            'total' => $total
        ]);
    }

    public function success()
    {
        return view('orders.success');
    }

    public function checkPromo(Request $request)
    {
        $code = strtoupper($request->code);
        $subtotal = $request->subtotal;

        $promotion = \App\Models\Promotion::where('code', $code)->first();

        if (!$promotion) {
            return response()->json([
                'success' => false,
                'message' => 'Mã khuyến mãi không tồn tại'
            ]);
        }

        if (!$promotion->isValid()) {
            return response()->json([
                'success' => false,
                'message' => 'Mã khuyến mãi đã hết hạn hoặc đã hết lượt sử dụng'
            ]);
        }

        if ($subtotal < $promotion->min_order_amount) {
            return response()->json([
                'success' => false,
                'message' => 'Đơn hàng tối thiểu ' . number_format($promotion->min_order_amount) . '₫'
            ]);
        }

        $discount = $promotion->calculateDiscount($subtotal);

        return response()->json([
            'success' => true,
            'message' => 'Áp dụng mã thành công!',
            'discount' => $discount,
            'code' => $code
        ]);
    }

    public function myOrders()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $orders = Order::where('user_id', auth()->id())
            ->with('orderItems.laptop')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('orders.my-orders', compact('orders'));
    }
}
