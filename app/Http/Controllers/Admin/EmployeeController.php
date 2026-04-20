<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    /**
     * Hiển thị danh sách nhân viên
     */
    public function index()
    {
        $employees = Employee::where('is_active', true)
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

        $departments = Employee::where('is_active', true)
            ->select('department')
            ->distinct()
            ->pluck('department');

        $totalEmployees = $employees->count();
        $onlineCount = $employees->where('is_online', true)->count();
        $departmentCount = $departments->count();

        return view('admin.employees.index', compact('employees', 'departments', 'totalEmployees', 'onlineCount', 'departmentCount'));
    }

    /**
     * Hiển thị form tạo nhân viên mới
     */
    public function create()
    {
        $departments = Employee::select('department')->distinct()->pluck('department');
        return view('admin.employees.form', compact('departments'));
    }

    /**
     * Lưu nhân viên mới
     */
    public function store(Request $request)
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

        Employee::create($data);

        return redirect()->route('admin.employees')->with('success', 'Thêm nhân viên thành công!');
    }

    /**
     * Hiển thị form chỉnh sửa nhân viên
     */
    public function edit($id)
    {
        $employee = Employee::findOrFail($id);
        $departments = Employee::select('department')->distinct()->pluck('department');
        return view('admin.employees.form', compact('employee', 'departments'));
    }

    /**
     * Cập nhật nhân viên
     */
    public function update(Request $request, $id)
    {
        $employee = Employee::findOrFail($id);

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

    /**
     * Xóa nhân viên
     */
    public function destroy($id)
    {
        $employee = Employee::findOrFail($id);
        
        // Soft delete by setting is_active to false
        $employee->update(['is_active' => false]);

        return response()->json([
            'success' => true,
            'message' => 'Xóa nhân viên thành công'
        ]);
    }

    /**
     * Tìm kiếm nhân viên
     */
    public function search(Request $request)
    {
        $query = Employee::where('is_active', true);

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
}
