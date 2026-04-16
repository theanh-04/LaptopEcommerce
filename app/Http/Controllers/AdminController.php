<?php

namespace App\Http\Controllers;

use App\Models\Laptop;
use App\Models\Order;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    public function dashboard()
    {
        $featuredLaptops = Laptop::orderBy('created_at', 'desc')
            ->take(4)
            ->get();

        return view('admin.dashboard.index', compact('featuredLaptops'));
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

        return view('admin.employees.index', compact('employees'));
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
                    'customer_email' => $order->customer_email,
                    'customer_phone' => $order->customer_phone,
                    'items_count' => $order->orderItems->sum('quantity'),
                    'total' => $order->total_amount,
                    'status' => $order->status
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
                    'customer_email' => $order->customer_email,
                    'customer_phone' => $order->customer_phone,
                    'items_count' => $order->orderItems->sum('quantity'),
                    'total' => $order->total_amount,
                    'status' => $order->status
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
                    'customer_email' => $order->customer_email,
                    'customer_phone' => $order->customer_phone,
                    'items_count' => $order->orderItems->sum('quantity'),
                    'total' => $order->total_amount,
                    'status' => $order->status,
                    'completed_at' => $order->updated_at->format('d/m/Y')
                ];
            });

        return view('admin.orders.index', compact('pendingOrders', 'processingOrders', 'shippedOrders'));
    }

    public function updateOrderStatus(Request $request, $id)
    {
        $order = Order::with('orderItems')->findOrFail($id);
        $oldStatus = $order->status;
        $newStatus = $request->status;
        
        // Validate status transition
        $validTransitions = [
            'pending' => ['processing', 'cancelled'],
            'processing' => ['completed', 'cancelled'],
            'completed' => [], // Cannot change from completed
            'cancelled' => [] // Cannot change from cancelled
        ];
        
        if (!in_array($newStatus, $validTransitions[$oldStatus] ?? [])) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể chuyển trạng thái này'
            ]);
        }
        
        // Handle stock changes
        if ($newStatus === 'processing' && $oldStatus === 'pending') {
            // Deduct stock when moving to processing
            foreach ($order->orderItems as $item) {
                $laptop = Laptop::find($item->laptop_id);
                if ($laptop->stock < $item->quantity) {
                    return response()->json([
                        'success' => false,
                        'message' => "Không đủ hàng trong kho cho sản phẩm: {$laptop->name}"
                    ]);
                }
                $laptop->decrement('stock', $item->quantity);
            }
        } elseif ($newStatus === 'cancelled') {
            // Restore stock when cancelling
            if ($oldStatus === 'processing' || $oldStatus === 'completed') {
                foreach ($order->orderItems as $item) {
                    $laptop = Laptop::find($item->laptop_id);
                    $laptop->increment('stock', $item->quantity);
                }
            }
        }
        
        $order->status = $newStatus;
        $order->save();

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật trạng thái thành công'
        ]);
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
        $brands = \App\Models\Brand::where('is_active', true)
            ->orderBy('display_order')
            ->get();

        return view('admin.inventory.index', compact('products', 'totalProducts', 'lowStockCount', 'brands'));
    }

    public function updateStock(Request $request, $id)
    {
        $laptop = Laptop::findOrFail($id);
        $laptop->stock = $request->stock;
        $laptop->save();

        return response()->json(['success' => true, 'message' => 'Cập nhật tồn kho thành công']);
    }

    public function createProduct()
    {
        $categories = \App\Models\Category::all();
        $brands = Brand::where('is_active', true)->orderBy('display_order')->get();
        return view('admin.inventory.create', compact('categories', 'brands'));
    }

    public function storeProduct(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'brand' => 'required|string',
            'processor' => 'required|string',
            'ram' => 'required|string',
            'storage' => 'required|string',
            'display' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'description' => 'required|string',
            'image' => 'nullable|image|max:2048'
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->name);
        
        // Generate SKU
        $brandPrefix = strtoupper(substr($request->brand, 0, 3));
        $data['sku'] = $brandPrefix . '-' . str_pad(Laptop::count() + 1, 6, '0', STR_PAD_LEFT);

        // Handle image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('laptops', 'public');
            $data['image'] = $imagePath;
        }

        Laptop::create($data);

        return redirect()->route('admin.inventory')->with('success', 'Thêm sản phẩm thành công!');
    }

    public function editProduct($id)
    {
        $laptop = Laptop::findOrFail($id);
        $categories = \App\Models\Category::all();
        $brands = Brand::where('is_active', true)->orderBy('display_order')->get();
        return view('admin.inventory.edit', compact('laptop', 'categories', 'brands'));
    }

    public function updateProduct(Request $request, $id)
    {
        $laptop = Laptop::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'brand' => 'required|string',
            'processor' => 'required|string',
            'ram' => 'required|string',
            'storage' => 'required|string',
            'display' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'description' => 'required|string',
            'image' => 'nullable|image|max:2048'
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->name);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image
            if ($laptop->image && \Storage::disk('public')->exists($laptop->image)) {
                \Storage::disk('public')->delete($laptop->image);
            }
            $imagePath = $request->file('image')->store('laptops', 'public');
            $data['image'] = $imagePath;
        }

        $laptop->update($data);

        return redirect()->route('admin.inventory')->with('success', 'Cập nhật sản phẩm thành công!');
    }

    public function deleteProduct($id)
    {
        $laptop = Laptop::findOrFail($id);
        
        // Delete image
        if ($laptop->image && \Storage::disk('public')->exists($laptop->image)) {
            \Storage::disk('public')->delete($laptop->image);
        }

        $laptop->delete();

        return response()->json(['success' => true, 'message' => 'Xóa sản phẩm thành công']);
    }

    public function filterInventory(Request $request)
    {
        $query = Laptop::with('category');

        // Filter by brand
        if ($request->has('brand') && $request->brand != 'all') {
            $query->where('brand', $request->brand);
        }

        // Filter by stock status
        if ($request->has('stock_status')) {
            switch ($request->stock_status) {
                case 'in_stock':
                    $query->where('stock', '>=', 10);
                    break;
                case 'low_stock':
                    $query->where('stock', '>', 0)->where('stock', '<', 10);
                    break;
                case 'out_of_stock':
                    $query->where('stock', 0);
                    break;
            }
        }

        // Search
        if ($request->has('search') && $request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('sku', 'like', '%' . $request->search . '%')
                  ->orWhere('brand', 'like', '%' . $request->search . '%');
            });
        }

        $products = $query->get()->map(function($laptop) {
            return [
                'id' => $laptop->id,
                'name' => $laptop->name,
                'sku' => $laptop->sku ?? 'SKU-' . str_pad($laptop->id, 6, '0', STR_PAD_LEFT),
                'brand' => $laptop->brand,
                'stock' => $laptop->stock,
                'price' => $laptop->price,
                'image' => $laptop->image
            ];
        });

        return response()->json($products);
    }

    public function pos()
    {
        $categories = \App\Models\Category::withCount('laptops')->get();
        
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

        $cartItems = [];
        $subtotal = 0;

        return view('admin.pos.index', compact('products', 'cartItems', 'subtotal', 'categories'));
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

    // Brand Management
    public function brandsIndex()
    {
        $brands = Brand::withCount('laptops')->orderBy('display_order')->get();
        return view('admin.brands.index', compact('brands'));
    }

    public function brandsCreate()
    {
        return view('admin.brands.form');
    }

    public function brandsStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:brands,name',
            'slug' => 'nullable|string|max:255|unique:brands,slug',
            'logo' => 'nullable|image|max:2048',
            'display_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean'
        ]);

        $data = $request->all();
        $data['slug'] = $request->slug ?: Str::slug($request->name);
        $data['is_active'] = $request->has('is_active');

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('brands', 'public');
            $data['logo'] = $logoPath;
        }

        Brand::create($data);

        return redirect()->route('admin.brands.index')->with('success', 'Thêm thương hiệu thành công!');
    }

    public function brandsEdit($id)
    {
        $brand = Brand::findOrFail($id);
        return view('admin.brands.form', compact('brand'));
    }

    public function brandsUpdate(Request $request, $id)
    {
        $brand = Brand::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:brands,name,' . $id,
            'slug' => 'nullable|string|max:255|unique:brands,slug,' . $id,
            'logo' => 'nullable|image|max:2048',
            'display_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean'
        ]);

        $data = $request->all();
        $data['slug'] = $request->slug ?: Str::slug($request->name);
        $data['is_active'] = $request->has('is_active');

        // Handle logo upload
        if ($request->hasFile('logo')) {
            // Delete old logo
            if ($brand->logo && \Storage::disk('public')->exists($brand->logo)) {
                \Storage::disk('public')->delete($brand->logo);
            }
            $logoPath = $request->file('logo')->store('brands', 'public');
            $data['logo'] = $logoPath;
        }

        $brand->update($data);

        return redirect()->route('admin.brands.index')->with('success', 'Cập nhật thương hiệu thành công!');
    }

    public function brandsDelete($id)
    {
        $brand = Brand::findOrFail($id);
        
        // Check if brand has products
        if ($brand->laptops()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể xóa thương hiệu đang có sản phẩm!'
            ]);
        }

        // Delete logo
        if ($brand->logo && \Storage::disk('public')->exists($brand->logo)) {
            \Storage::disk('public')->delete($brand->logo);
        }

        $brand->delete();

        return response()->json([
            'success' => true,
            'message' => 'Xóa thương hiệu thành công'
        ]);
    }

    public function getOrderDetail($id)
    {
        $order = Order::with('orderItems.laptop')->findOrFail($id);
        
        return response()->json([
            'id' => $order->id,
            'order_number' => $order->order_number,
            'customer_name' => $order->customer_name,
            'customer_email' => $order->customer_email,
            'customer_phone' => $order->customer_phone,
            'customer_address' => $order->customer_address,
            'total_amount' => $order->total_amount,
            'status' => $order->status,
            'items' => $order->orderItems->map(function($item) {
                return [
                    'laptop_name' => $item->laptop->name,
                    'quantity' => $item->quantity,
                    'price' => $item->price
                ];
            })
        ]);
    }
}
