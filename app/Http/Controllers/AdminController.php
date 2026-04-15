<?php

namespace App\Http\Controllers;

use App\Models\Laptop;
use App\Models\Order;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $featuredLaptops = Laptop::orderBy('created_at', 'desc')
            ->take(4)
            ->get();

        return view('admin.dashboard', compact('featuredLaptops'));
    }

    public function employees()
    {
        // Sample employee data
        $employees = collect([
            (object)[
                'name' => 'Alex Rivers',
                'email' => 'alex.r@neonkinetic.io',
                'position' => 'Senior Developer',
                'is_online' => true,
                'performance' => 92
            ],
            (object)[
                'name' => 'Sarah Chen',
                'email' => 's.chen@neonkinetic.io',
                'position' => 'Order Manager',
                'is_online' => true,
                'performance' => 88
            ],
            (object)[
                'name' => 'Marcus Wright',
                'email' => 'm.wright@neonkinetic.io',
                'position' => 'Support Lead',
                'is_online' => false,
                'performance' => 76
            ],
            (object)[
                'name' => 'Julia Nova',
                'email' => 'j.nova@neonkinetic.io',
                'position' => 'Operations Director',
                'is_online' => true,
                'performance' => 95
            ],
        ]);

        return view('admin.employees', compact('employees'));
    }
}
