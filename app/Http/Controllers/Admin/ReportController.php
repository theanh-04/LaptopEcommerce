<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Laptop;
use App\Models\Customer;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * Hiển thị báo cáo
     */
    public function index(Request $request)
    {
        $period = $request->get('period', 'week');
        
        // Xác định khoảng thời gian
        switch ($period) {
            case 'today':
                $startDate = now()->startOfDay();
                $endDate = now()->endOfDay();
                break;
            case 'week':
                $startDate = now()->startOfWeek();
                $endDate = now()->endOfWeek();
                break;
            case 'month':
                $startDate = now()->startOfMonth();
                $endDate = now()->endOfMonth();
                break;
            case 'year':
                $startDate = now()->startOfYear();
                $endDate = now()->endOfYear();
                break;
            default:
                $startDate = now()->startOfWeek();
                $endDate = now()->endOfWeek();
        }

        // Tổng quan
        $totalRevenue = Order::whereIn('status', ['processing', 'completed'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('total_amount');

        $totalOrders = Order::whereBetween('created_at', [$startDate, $endDate])->count();
        
        $totalProducts = OrderItem::whereHas('order', function($q) use ($startDate, $endDate) {
            $q->whereBetween('created_at', [$startDate, $endDate]);
        })->sum('quantity');

        $newCustomers = Customer::whereBetween('created_at', [$startDate, $endDate])->count();

        // Sản phẩm bán chạy
        $topProducts = OrderItem::select('laptop_id', DB::raw('SUM(quantity) as total_sold'), DB::raw('SUM(price * quantity) as revenue'))
            ->whereHas('order', function($q) use ($startDate, $endDate) {
                $q->whereBetween('created_at', [$startDate, $endDate]);
            })
            ->with('laptop')
            ->groupBy('laptop_id')
            ->orderBy('total_sold', 'desc')
            ->limit(10)
            ->get();

        // Doanh thu theo ngày
        $revenueByDay = Order::whereIn('status', ['processing', 'completed'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total_amount) as revenue'),
                DB::raw('COUNT(*) as orders')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Khách hàng chi tiêu nhiều nhất (dùng data có sẵn từ bảng customers)
        $topCustomers = Customer::where('total_spent', '>', 0)
            ->orderBy('total_spent', 'desc')
            ->limit(10)
            ->get();

        return view('admin.reports.index', compact(
            'totalRevenue',
            'totalOrders',
            'totalProducts',
            'newCustomers',
            'topProducts',
            'revenueByDay',
            'topCustomers',
            'period'
        ));
    }
}
