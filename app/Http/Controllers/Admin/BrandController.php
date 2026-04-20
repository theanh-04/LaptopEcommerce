<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BrandController extends Controller
{
    /**
     * Hiển thị danh sách thương hiệu
     */
    public function index()
    {
        $brands = Brand::withCount('laptops')->orderBy('display_order')->get();
        return view('admin.brands.index', compact('brands'));
    }

    /**
     * Hiển thị form tạo thương hiệu mới
     */
    public function create()
    {
        return view('admin.brands.form');
    }

    /**
     * Lưu thương hiệu mới
     */
    public function store(Request $request)
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

    /**
     * Hiển thị form chỉnh sửa thương hiệu
     */
    public function edit($id)
    {
        $brand = Brand::findOrFail($id);
        return view('admin.brands.form', compact('brand'));
    }

    /**
     * Cập nhật thương hiệu
     */
    public function update(Request $request, $id)
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

    /**
     * Xóa thương hiệu
     */
    public function destroy($id)
    {
        $brand = Brand::findOrFail($id);
        
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
}
