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
        $employees = \App\Models\Employee::where('is_active', true)
            ->withCount(['orders' => function($query) {
                $query->whereIn('status', ['processing', 'completed']);
            }])
            ->orderBy('name')
            ->get()
            ->map(function($emp) {
                return (object)[
                    'id' => $emp->id,
                    'name' => $emp->name,
                    'email' => $emp->email,
                    'phone' => $emp->phone,
                    'position' => $emp->position,
                    'department' => $emp->department,
                    'hire_date' => $emp->hire_date->format('d/m/Y'),
                    'salary' => $emp->salary,
                    'is_online' => rand(0, 1) == 1,
                    'orders_count' => $emp->orders_count
                ];
            });

        $departments = \App\Models\Employee::where('is_active', true)
            ->select('department')
            ->distinct()
            ->pluck('department');

        $totalEmployees = $employees->count();
        $onlineCount = $employees->where('is_online', true)->count();
        $departmentCount = $departments->count();

        return view('admin.employees.index', compact('employees', 'departments', 'totalEmployees', 'onlineCount', 'departmentCount'));
    }

    public function employeesCreate()
    {
        $departments = \App\Models\Employee::select('department')->distinct()->pluck('department');
        return view('admin.employees.form', compact('departments'));
    }

    public function employeesStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:employees,email',
            'phone' => 'nullable|string|max:20',
            'position' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'hire_date' => 'required|date',
            'salary' => 'nullable|numeric|min:0',
            'address' => 'nullable|string',
            'avatar' => 'nullable|image|max:2048'
        ]);

        $data = $request->all();
        $data['is_active'] = true;

        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('employees', 'public');
            $data['avatar'] = $avatarPath;
        }

        \App\Models\Employee::create($data);

        return redirect()->route('admin.employees')->with('success', 'Thêm nhân viên thành công!');
    }

    public function employeesEdit($id)
    {
        $employee = \App\Models\Employee::findOrFail($id);
        $departments = \App\Models\Employee::select('department')->distinct()->pluck('department');
        return view('admin.employees.form', compact('employee', 'departments'));
    }

    public function employeesUpdate(Request $request, $id)
    {
        $employee = \App\Models\Employee::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:employees,email,' . $id,
            'phone' => 'nullable|string|max:20',
            'position' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'hire_date' => 'required|date',
            'salary' => 'nullable|numeric|min:0',
            'address' => 'nullable|string',
            'avatar' => 'nullable|image|max:2048'
        ]);

        $data = $request->all();

        if ($request->hasFile('avatar')) {
            if ($employee->avatar && \Storage::disk('public')->exists($employee->avatar)) {
                \Storage::disk('public')->delete($employee->avatar);
            }
            $avatarPath = $request->file('avatar')->store('employees', 'public');
            $data['avatar'] = $avatarPath;
        }

        $employee->update($data);

        return redirect()->route('admin.employees')->with('success', 'Cập nhật nhân viên thành công!');
    }

    public function employeesDelete($id)
    {
        $employee = \App\Models\Employee::findOrFail($id);
        
        // Soft delete by setting is_active to false
        $employee->update(['is_active' => false]);

        return response()->json([
            'success' => true,
            'message' => 'Xóa nhân viên thành công'
        ]);
    }

    public function employeesSearch(Request $request)
    {
        $query = \App\Models\Employee::where('is_active', true);

        // Search by name or email
        if ($request->has('search') && $request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%')
                  ->orWhere('phone', 'like', '%' . $request->search . '%');
            });
        }

        // Filter by department
        if ($request->has('department') && $request->department != 'all') {
            $query->where('department', $request->department);
        }

        $employees = $query->withCount(['orders' => function($q) {
            $q->whereIn('status', ['processing', 'completed']);
        }])->orderBy('name')->get()->map(function($emp) {
            return [
                'id' => $emp->id,
                'name' => $emp->name,
                'email' => $emp->email,
                'phone' => $emp->phone,
                'position' => $emp->position,
                'department' => $emp->department,
                'hire_date' => $emp->hire_date->format('d/m/Y'),
                'salary' => $emp->salary,
                'is_online' => rand(0, 1) == 1,
                'orders_count' => $emp->orders_count
            ];
        });

        return response()->json($employees);
    }

    // Promotions Management
    public function promotions()
    {
        $promotions = \App\Models\Promotion::orderBy('created_at', 'desc')->get();
        
        $activeCount = $promotions->where('is_active', true)->count();
        $expiredCount = $promotions->filter(function($promo) {
            return $promo->end_date->isPast();
        })->count();
        $totalUsage = $promotions->sum('used_count');
        
        return view('admin.promotions.index', compact('promotions', 'activeCount', 'expiredCount', 'totalUsage'));
    }

    public function promotionsCreate()
    {
        return view('admin.promotions.form');
    }

    public function promotionsStore(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:50|unique:promotions,code',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric|min:0',
            'min_order_amount' => 'required|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        $data = $request->all();
        $data['code'] = strtoupper($data['code']);
        $data['is_active'] = $request->has('is_active');
        $data['used_count'] = 0;

        \App\Models\Promotion::create($data);

        return redirect()->route('admin.promotions')->with('success', 'Thêm khuyến mãi thành công!');
    }

    public function promotionsEdit($id)
    {
        $promotion = \App\Models\Promotion::findOrFail($id);
        return view('admin.promotions.form', compact('promotion'));
    }

    public function promotionsUpdate(Request $request, $id)
    {
        $promotion = \App\Models\Promotion::findOrFail($id);

        $request->validate([
            'code' => 'required|string|max:50|unique:promotions,code,' . $id,
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric|min:0',
            'min_order_amount' => 'required|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        $data = $request->all();
        $data['code'] = strtoupper($data['code']);
        $data['is_active'] = $request->has('is_active');

        $promotion->update($data);

        return redirect()->route('admin.promotions')->with('success', 'Cập nhật khuyến mãi thành công!');
    }

    public function promotionsDelete($id)
    {
        $promotion = \App\Models\Promotion::findOrFail($id);
        $promotion->delete();

        return response()->json([
            'success' => true,
            'message' => 'Xóa khuyến mãi thành công'
        ]);
    }

    public function promotionsToggle($id)
    {
        $promotion = \App\Models\Promotion::findOrFail($id);
        $promotion->is_active = !$promotion->is_active;
        $promotion->save();

        return response()->json([
            'success' => true,
            'is_active' => $promotion->is_active,
            'message' => $promotion->is_active ? 'Đã kích hoạt' : 'Đã tắt'
        ]);
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

        // Update promotion usage if applied
        if ($request->promo_code) {
            $promotion = \App\Models\Promotion::where('code', $request->promo_code)->first();
            if ($promotion) {
                $promotion->increment('used_count');
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Thanh toán thành công',
            'order_number' => $order->order_number
        ]);
    }

    public function applyPromoCode(Request $request)
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
