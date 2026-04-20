<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Laptop;
use App\Models\Customer;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Hiển thị dashboard với thống kê
     */
    public function index()
    {
        // Tổng doanh thu
        $totalRevenue = Order::whereIn('status', ['processing', 'completed'])
            ->sum('total_amount');

        // Đơn hàng đang xử lý
        $activeOrders = Order::where('status', 'pending')->count();
        $urgentOrders = Order::where('status', 'pending')
            ->whereDate('created_at', today())
            ->count();

        // Sản phẩm bán chạy nhất
        $topProduct = OrderItem::select('laptop_id', \DB::raw('SUM(quantity) as total_sold'))
            ->groupBy('laptop_id')
            ->orderBy('total_sold', 'desc')
            ->with('laptop')
            ->first();

        // Doanh thu 7 ngày gần nhất
        $revenueChart = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $revenue = Order::whereIn('status', ['processing', 'completed'])
                ->whereDate('created_at', $date)
                ->sum('total_amount');
            $revenueChart[] = [
                'date' => $date->format('d/m'),
                'day' => $date->locale('vi')->isoFormat('dddd'),
                'revenue' => $revenue
            ];
        }

        // Hoạt động gần đây
        $recentActivities = Order::with('orderItems.laptop')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(function($order) {
                return [
                    'type' => 'order',
                    'title' => 'Đơn hàng mới #' . $order->order_number,
                    'description' => 'Khách hàng: ' . $order->customer_name . ' • ' . number_format($order->total_amount) . '₫',
                    'time' => $order->created_at->diffForHumans(),
                    'icon' => 'shopping_cart_checkout',
                    'color' => 'primary'
                ];
            });

        // Sản phẩm nổi bật
        $featuredLaptops = Laptop::where('is_featured', true)
            ->orderBy('created_at', 'desc')
            ->take(4)
            ->get();

        // Nếu không đủ 4 sản phẩm featured, lấy thêm sản phẩm mới
        if ($featuredLaptops->count() < 4) {
            $additionalLaptops = Laptop::where('is_featured', false)
                ->orderBy('created_at', 'desc')
                ->take(4 - $featuredLaptops->count())
                ->get();
            $featuredLaptops = $featuredLaptops->merge($additionalLaptops);
        }

        // Tính % tăng trưởng doanh thu so với tuần trước
        $lastWeekRevenue = Order::whereIn('status', ['processing', 'completed'])
            ->whereBetween('created_at', [now()->subDays(14), now()->subDays(7)])
            ->sum('total_amount');
        $thisWeekRevenue = Order::whereIn('status', ['processing', 'completed'])
            ->whereBetween('created_at', [now()->subDays(7), now()])
            ->sum('total_amount');
        $revenueGrowth = $lastWeekRevenue > 0 
            ? round((($thisWeekRevenue - $lastWeekRevenue) / $lastWeekRevenue) * 100, 1)
            : 0;

        return view('admin.dashboard.index', compact(
            'totalRevenue',
            'activeOrders',
            'urgentOrders',
            'topProduct',
            'revenueChart',
            'recentActivities',
            'featuredLaptops',
            'revenueGrowth'
        ));
    }
}
