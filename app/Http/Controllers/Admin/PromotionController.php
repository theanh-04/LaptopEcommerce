<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Promotion;
use Illuminate\Http\Request;

class PromotionController extends Controller
{
    /**
     * Hiển thị danh sách khuyến mãi
     */
    public function index()
    {
        $promotions = Promotion::orderBy('created_at', 'desc')->get();
        
        $activeCount = $promotions->where('is_active', true)->count();
        $expiredCount = $promotions->filter(function($promo) {
            return $promo->end_date->isPast();
        })->count();
        $totalUsage = $promotions->sum('used_count');
        
        return view('admin.promotions.index', compact('promotions', 'activeCount', 'expiredCount', 'totalUsage'));
    }

    /**
     * Hiển thị form tạo khuyến mãi mới
     */
    public function create()
    {
        return view('admin.promotions.form');
    }

    /**
     * Lưu khuyến mãi mới
     */
    public function store(Request $request)
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

        Promotion::create($data);

        return redirect()->route('admin.promotions')->with('success', 'Thêm khuyến mãi thành công!');
    }

    /**
     * Hiển thị form chỉnh sửa khuyến mãi
     */
    public function edit($id)
    {
        $promotion = Promotion::findOrFail($id);
        return view('admin.promotions.form', compact('promotion'));
    }

    /**
     * Cập nhật khuyến mãi
     */
    public function update(Request $request, $id)
    {
        $promotion = Promotion::findOrFail($id);

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

    /**
     * Xóa khuyến mãi
     */
    public function destroy($id)
    {
        $promotion = Promotion::findOrFail($id);
        $promotion->delete();

        return response()->json([
            'success' => true,
            'message' => 'Xóa khuyến mãi thành công'
        ]);
    }

    /**
     * Bật/tắt khuyến mãi
     */
    public function toggle($id)
    {
        $promotion = Promotion::findOrFail($id);
        $promotion->is_active = !$promotion->is_active;
        $promotion->save();

        return response()->json([
            'success' => true,
            'is_active' => $promotion->is_active,
            'message' => $promotion->is_active ? 'Đã kích hoạt' : 'Đã tắt'
        ]);
    }
}
