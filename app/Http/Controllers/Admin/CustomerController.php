<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::orderBy('created_at', 'desc')->get();
        
        $totalCustomers = $customers->count();
        $activeCustomers = $customers->where('is_active', true)->count();
        $totalRevenue = $customers->sum('total_spent');
        $avgOrderValue = $totalCustomers > 0 ? $totalRevenue / $totalCustomers : 0;
        
        // Tier distribution
        $tierStats = [
            'bronze' => $customers->where('tier', 'bronze')->count(),
            'silver' => $customers->where('tier', 'silver')->count(),
            'gold' => $customers->where('tier', 'gold')->count(),
            'platinum' => $customers->where('tier', 'platinum')->count(),
        ];
        
        return view('admin.customers.index', compact(
            'customers',
            'totalCustomers',
            'activeCustomers',
            'totalRevenue',
            'avgOrderValue',
            'tierStats'
        ));
    }

    public function create()
    {
        return view('admin.customers.form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'notes' => 'nullable|string'
        ]);

        $data = $request->all();
        $data['is_active'] = true;
        $data['loyalty_points'] = 0;
        $data['tier'] = 'bronze';
        $data['total_spent'] = 0;
        $data['total_orders'] = 0;

        Customer::create($data);

        return redirect()->route('admin.customers.index')->with('success', 'Thêm khách hàng thành công!');
    }

    public function edit($id)
    {
        $customer = Customer::findOrFail($id);
        return view('admin.customers.form', compact('customer'));
    }

    public function update(Request $request, $id)
    {
        $customer = Customer::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email,' . $id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'tier' => 'required|in:bronze,silver,gold,platinum',
            'notes' => 'nullable|string'
        ]);

        $customer->update($request->all());

        return redirect()->route('admin.customers.index')->with('success', 'Cập nhật khách hàng thành công!');
    }

    public function destroy($id)
    {
        $customer = Customer::findOrFail($id);
        
        // Soft delete by setting is_active to false
        $customer->update(['is_active' => false]);

        return response()->json([
            'success' => true,
            'message' => 'Xóa khách hàng thành công'
        ]);
    }

    public function search(Request $request)
    {
        $query = Customer::where('is_active', true);

        // Search by name, email, or phone
        if ($request->has('search') && $request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%')
                  ->orWhere('phone', 'like', '%' . $request->search . '%');
            });
        }

        // Filter by tier
        if ($request->has('tier') && $request->tier != 'all') {
            $query->where('tier', $request->tier);
        }

        $customers = $query->orderBy('created_at', 'desc')->get();

        return response()->json($customers);
    }

    public function show($id)
    {
        $customer = Customer::with('orders.orderItems.laptop')->findOrFail($id);
        
        return response()->json([
            'id' => $customer->id,
            'name' => $customer->name,
            'email' => $customer->email,
            'phone' => $customer->phone,
            'address' => $customer->address,
            'date_of_birth' => $customer->date_of_birth ? $customer->date_of_birth->format('d/m/Y') : null,
            'gender' => $customer->gender,
            'loyalty_points' => $customer->loyalty_points,
            'tier' => $customer->tier,
            'tier_name' => $customer->tier_name,
            'total_spent' => $customer->total_spent,
            'total_orders' => $customer->total_orders,
            'last_purchase_at' => $customer->last_purchase_at ? $customer->last_purchase_at->format('d/m/Y H:i') : null,
            'notes' => $customer->notes,
            'orders' => $customer->orders->map(function($order) {
                return [
                    'order_number' => $order->order_number,
                    'total_amount' => $order->total_amount,
                    'status' => $order->status,
                    'created_at' => $order->created_at->format('d/m/Y'),
                    'items_count' => $order->orderItems->count()
                ];
            })
        ]);
    }
}
