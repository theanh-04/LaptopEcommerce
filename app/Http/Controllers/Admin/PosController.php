<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Laptop;
use App\Models\Order;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Promotion;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PosController extends Controller
{
    /**
     * Hiển thị giao diện POS
     */
    public function index()
    {
        $categories = Category::withCount('laptops')->get();
        
        $products = Laptop::where('stock', '>', 0)
            ->get()
            ->map(function($laptop) {
                return (object)[
                    'id' => $laptop->id,
                    'name' => $laptop->name,
                    'description' => Str::limit($laptop->description, 100),
                    'price' => $laptop->price,
                    'image' => $laptop->image,
                    'category_id' => $laptop->category_id,
                    'is_new' => $laptop->created_at->diffInDays(now()) < 30
                ];
            });

        $customers = Customer::where('is_active', true)
            ->orderBy('name')
            ->get();

        $cartItems = [];
        $subtotal = 0;

        return view('admin.pos.index', compact('products', 'cartItems', 'subtotal', 'categories', 'customers'));
    }

    /**
     * Xử lý thanh toán
     */
    public function processPayment(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'customer_name' => 'required|string',
            'customer_email' => 'required|email',
            'customer_phone' => 'required|string',
            'payment_method' => 'required|string',
            'customer_id' => 'nullable|exists:customers,id'
        ]);

        // Create order
        $order = Order::create([
            'order_number' => 'NK-' . date('ymd') . '-' . str_pad(Order::count() + 1, 4, '0', STR_PAD_LEFT),
            'employee_id' => auth()->check() ? auth()->user()->employee_id ?? null : null,
            'customer_id' => $request->customer_id,
            'customer_name' => $request->customer_name,
            'customer_email' => $request->customer_email,
            'customer_phone' => $request->customer_phone,
            'customer_address' => $request->customer_address ?? 'POS Sale',
            'total_amount' => $request->total,
            'status' => 'completed'
        ]);

        // Create order items
        foreach ($request->items as $item) {
            $order->orderItems()->create([
                'laptop_id' => $item['id'],
                'quantity' => $item['quantity'],
                'price' => $item['price']
            ]);

            // Update stock
            $laptop = Laptop::find($item['id']);
            $laptop->decrement('stock', $item['quantity']);
        }

        // Update promotion usage if applied
        if ($request->promo_code) {
            $promotion = Promotion::where('code', $request->promo_code)->first();
            if ($promotion) {
                $promotion->increment('used_count');
            }
        }

        // Update customer stats if customer_id is provided
        if ($request->customer_id) {
            $customer = Customer::find($request->customer_id);
            if ($customer) {
                $customer->increment('total_orders');
                $customer->increment('total_spent', $request->total);
                $customer->last_purchase_at = now();
                $customer->save();
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Thanh toán thành công',
            'order_number' => $order->order_number
        ]);
    }

    /**
     * Áp dụng mã khuyến mãi
     */
    public function applyPromoCode(Request $request)
    {
        $code = strtoupper($request->code);
        $subtotal = $request->subtotal;

        $promotion = Promotion::where('code', $code)->first();

        if (!$promotion) {
            return response()->json([
                'success' => false,
                'message' => 'Mã khuyến mãi không tồn tại'
            ]);
        }

        if (!$promotion->isValid()) {
            return response()->json([
                'success' => false,
                'message' => 'Mã khuyến mãi không còn hiệu lực'
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
            'promo' => [
                'code' => $promotion->code,
                'discount' => $discount
            ]
        ]);
    }
}
