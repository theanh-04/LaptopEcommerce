<?php

namespace App\Http\Controllers;

use App\Models\Laptop;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    public function dashboard()
    {
        $featuredLaptops = Laptop::orderBy('created_at', 'desc')
            ->take(4)
            ->get();

        return view('admin.dashboard', compact('featuredLaptops'));
    }

    public function employees()
    {
        // Sample employee data
        $employees = collect([
            (object)[
                'name' => 'Alex Rivers',
                'email' => 'alex.r@neonkinetic.io',
                'position' => 'Senior Developer',
                'is_online' => true,
                'performance' => 92
            ],
            (object)[
                'name' => 'Sarah Chen',
                'email' => 's.chen@neonkinetic.io',
                'position' => 'Order Manager',
                'is_online' => true,
                'performance' => 88
            ],
            (object)[
                'name' => 'Marcus Wright',
                'email' => 'm.wright@neonkinetic.io',
                'position' => 'Support Lead',
                'is_online' => false,
                'performance' => 76
            ],
            (object)[
                'name' => 'Julia Nova',
                'email' => 'j.nova@neonkinetic.io',
                'position' => 'Operations Director',
                'is_online' => true,
                'performance' => 95
            ],
        ]);

        return view('admin.employees', compact('employees'));
    }

    public function orders()
    {
        $pendingOrders = Order::where('status', 'pending')
            ->with('orderItems.laptop')
            ->get()
            ->map(function($order) {
                return (object)[
                    'id' => $order->id,
                    'order_number' => $order->order_number ?? 'NK-' . str_pad($order->id, 6, '0', STR_PAD_LEFT),
                    'customer_name' => $order->customer_name,
                    'items_count' => $order->orderItems->sum('quantity'),
                    'total' => $order->total_amount
                ];
            });

        $processingOrders = Order::where('status', 'processing')
            ->with('orderItems.laptop')
            ->get()
            ->map(function($order) {
                return (object)[
                    'id' => $order->id,
                    'order_number' => $order->order_number ?? 'NK-' . str_pad($order->id, 6, '0', STR_PAD_LEFT),
                    'customer_name' => $order->customer_name,
                    'items_count' => $order->orderItems->sum('quantity'),
                    'total' => $order->total_amount
                ];
            });

        $shippedOrders = Order::where('status', 'completed')
            ->with('orderItems.laptop')
            ->latest()
            ->take(10)
            ->get()
            ->map(function($order) {
                return (object)[
                    'id' => $order->id,
                    'order_number' => $order->order_number ?? 'NK-' . str_pad($order->id, 6, '0', STR_PAD_LEFT),
                    'customer_name' => $order->customer_name,
                    'items_count' => $order->orderItems->sum('quantity'),
                    'total' => $order->total_amount,
                    'completed_at' => $order->updated_at->format('d/m/Y')
                ];
            });

        return view('admin.orders', compact('pendingOrders', 'processingOrders', 'shippedOrders'));
    }

    public function updateOrderStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $order->status = $request->status;
        $order->save();

        return response()->json(['success' => true, 'message' => 'Cập nhật trạng thái thành công']);
    }

    public function inventory()
    {
        $products = Laptop::with('category')->get()->map(function($laptop) {
            return (object)[
                'id' => $laptop->id,
                'name' => $laptop->name,
                'sku' => $laptop->sku ?? 'SKU-' . str_pad($laptop->id, 6, '0', STR_PAD_LEFT),
                'brand' => $laptop->brand,
                'stock' => $laptop->stock,
                'price' => $laptop->price,
                'image' => $laptop->image
            ];
        });

        $totalProducts = Laptop::count();
        $lowStockCount = Laptop::where('stock', '<', 10)->count();

        return view('admin.inventory', compact('products', 'totalProducts', 'lowStockCount'));
    }

    public function updateStock(Request $request, $id)
    {
        $laptop = Laptop::findOrFail($id);
        $laptop->stock = $request->stock;
        $laptop->save();

        return response()->json(['success' => true, 'message' => 'Cập nhật tồn kho thành công']);
    }

    public function pos()
    {
        $products = Laptop::where('stock', '>', 0)
            ->get()
            ->map(function($laptop) {
                return (object)[
                    'id' => $laptop->id,
                    'name' => $laptop->name,
                    'description' => Str::limit($laptop->description, 100),
                    'price' => $laptop->price,
                    'image' => $laptop->image,
                    'is_new' => $laptop->created_at->diffInDays(now()) < 30
                ];
            });

        $cartItems = [];
        $subtotal = 0;

        return view('admin.pos', compact('products', 'cartItems', 'subtotal'));
    }

    public function processPayment(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'customer_name' => 'required|string',
            'customer_email' => 'required|email',
            'customer_phone' => 'required|string',
            'payment_method' => 'required|string'
        ]);

        // Create order
        $order = Order::create([
            'order_number' => 'NK-' . date('ymd') . '-' . str_pad(Order::count() + 1, 4, '0', STR_PAD_LEFT),
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

        return response()->json([
            'success' => true,
            'message' => 'Thanh toán thành công',
            'order_number' => $order->order_number
        ]);
    }
}
