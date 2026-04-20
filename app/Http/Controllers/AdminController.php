<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**
 * AdminController - Controller chính cho admin
 * 
 * Các chức năng đã được tách ra thành các controller riêng:
 * - DashboardController: Thống kê dashboard
 * - OrderController: Quản lý đơn hàng
 * - InventoryController: Quản lý kho hàng
 * - PosController: Bán hàng POS
 * - BrandController: Quản lý thương hiệu
 * - CustomerController: Quản lý khách hàng
 * - EmployeeController: Quản lý nhân viên
 * - PromotionController: Quản lý khuyến mãi
 * - ReportController: Báo cáo
 */
class AdminController extends Controller
{
    /**
     * Redirect to dashboard
     */
    public function index()
    {
        return redirect()->route('admin.dashboard');
    }
}
