<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Laptop;
use App\Models\Employee;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Hiển thị danh sách đơn hàng
     */
    public function index()
    {
        $pendingOrders = Order::where('status', 'pending')
            ->with(['orderItems.laptop', 'employee'])
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
                    'employee_name' => $order->employee ? $order->employee->name : null
                ];
            });

        $processingOrders = Order::where('status', 'processing')
            ->with(['orderItems.laptop', 'employee'])
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
                    'employee_name' => $order->employee ? $order->employee->name : null
                ];
            });

        $shippedOrders = Order::where('status', 'completed')
            ->with(['orderItems.laptop', 'employee'])
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
                    'completed_at' => $order->updated_at->format('d/m/Y'),
                    'employee_name' => $order->employee ? $order->employee->name : null
                ];
            });

        // Get active employees for assignment
        $employees = Employee::where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('admin.orders.index', compact('pendingOrders', 'processingOrders', 'shippedOrders', 'employees'));
    }

    /**
     * Cập nhật trạng thái đơn hàng
     */
    public function updateStatus(Request $request, $id)
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

    /**
     * Gán nhân viên cho đơn hàng
     */
    public function assignEmployee(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $order->employee_id = $request->employee_id;
        $order->save();

        return response()->json([
            'success' => true,
            'message' => 'Gán nhân viên thành công'
        ]);
    }

    /**
     * Lấy chi tiết đơn hàng
     */
    public function show($id)
    {
        $order = Order::with(['orderItems.laptop', 'employee', 'customer'])
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'order' => $order
        ]);
    }

    /**
     * In hóa đơn
     */
    public function printInvoice($id)
    {
        $order = Order::with(['orderItems.laptop', 'employee', 'customer'])
            ->findOrFail($id);

        return view('admin.orders.invoice', compact('order'));
    }
}
