<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Laptop;
use App\Models\Brand;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class InventoryController extends Controller
{
    /**
     * Hiển thị danh sách sản phẩm
     */
    public function index()
    {
        $products = Laptop::all()->map(function($laptop) {
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
        $brands = Brand::where('is_active', true)
            ->orderBy('display_order')
            ->get();

        return view('admin.inventory.index', compact('products', 'totalProducts', 'lowStockCount', 'brands'));
    }

    /**
     * Hiển thị form tạo sản phẩm mới
     */
    public function create()
    {
        $categories = Category::all();
        $brands = Brand::where('is_active', true)->orderBy('display_order')->get();
        return view('admin.inventory.create', compact('categories', 'brands'));
    }

    /**
     * Lưu sản phẩm mới
     */
    public function store(Request $request)
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
        $data['is_featured'] = $request->has('is_featured');
        
        // Generate SKU
        $brandPrefix = strtoupper(substr($request->brand, 0, 3));
        $data['sku'] = $brandPrefix . '-' . str_pad(Laptop::count() + 1, 6, '0', STR_PAD_LEFT);

        // Handle image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('laptops', 'public');
            $data['image'] = $imagePath;
        }

        Laptop::create($data);

        return redirect()->route('admin.inventory.index')->with('success', 'Thêm sản phẩm thành công!');
    }

    /**
     * Hiển thị form chỉnh sửa sản phẩm
     */
    public function edit($id)
    {
        $laptop = Laptop::findOrFail($id);
        $categories = Category::all();
        $brands = Brand::where('is_active', true)->orderBy('display_order')->get();
        return view('admin.inventory.edit', compact('laptop', 'categories', 'brands'));
    }

    /**
     * Cập nhật sản phẩm
     */
    public function update(Request $request, $id)
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
        $data['is_featured'] = $request->has('is_featured');

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

        return redirect()->route('admin.inventory.index')->with('success', 'Cập nhật sản phẩm thành công!');
    }

    /**
     * Xóa sản phẩm
     */
    public function destroy($id)
    {
        $laptop = Laptop::findOrFail($id);
        
        // Delete image
        if ($laptop->image && \Storage::disk('public')->exists($laptop->image)) {
            \Storage::disk('public')->delete($laptop->image);
        }

        $laptop->delete();

        return response()->json(['success' => true, 'message' => 'Xóa sản phẩm thành công']);
    }

    /**
     * Cập nhật tồn kho
     */
    public function updateStock(Request $request, $id)
    {
        $laptop = Laptop::findOrFail($id);
        $laptop->stock = $request->stock;
        $laptop->save();

        return response()->json(['success' => true, 'message' => 'Cập nhật tồn kho thành công']);
    }

    /**
     * Lọc sản phẩm
     */
    public function filter(Request $request)
    {
        $query = Laptop::query();

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
}
