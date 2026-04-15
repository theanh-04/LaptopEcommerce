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

    public function orders()
    {
        // Sample orders data
        $pendingOrders = collect([
            (object)[
                'order_number' => 'NK-2049-01',
                'customer_name' => 'Nguyễn Văn A',
                'items_count' => 1,
                'total' => 109990000
            ],
            (object)[
                'order_number' => 'NK-2049-05',
                'customer_name' => 'Lê Thị B',
                'items_count' => 2,
                'total' => 149990000
            ],
        ]);

        $processingOrders = collect([
            (object)[
                'order_number' => 'NK-2048-92',
                'customer_name' => 'Trần Văn C',
                'items_count' => 1,
                'total' => 39990000
            ],
        ]);

        $shippedOrders = collect([
            (object)[
                'order_number' => 'NK-2048-80',
                'customer_name' => 'Hoàng Văn D',
                'items_count' => 1,
                'total' => 45500000,
                'completed_at' => '12/10/2023'
            ],
        ]);

        return view('admin.orders', compact('pendingOrders', 'processingOrders', 'shippedOrders'));
    }

    public function inventory()
    {
        // Sample inventory data
        $products = collect([
            (object)[
                'name' => 'MacBook Pro 16" M3 Max',
                'sku' => 'APP-MBP-16-M3',
                'brand' => 'Apple',
                'stock' => 84,
                'price' => 79990000,
                'image' => null
            ],
            (object)[
                'name' => 'ROG Zephyrus G14 2024',
                'sku' => 'ASUS-ROG-Z14',
                'brand' => 'ASUS',
                'stock' => 8,
                'price' => 45500000,
                'image' => null
            ],
            (object)[
                'name' => 'MSI Titan GT77 HX',
                'sku' => 'MSI-TT-GT77',
                'brand' => 'MSI',
                'stock' => 42,
                'price' => 120000000,
                'image' => null
            ],
            (object)[
                'name' => 'Razer Blade 14 QHD+',
                'sku' => 'RAZ-BL-14-G4',
                'brand' => 'Razer',
                'stock' => 0,
                'price' => 52990000,
                'image' => null
            ],
        ]);

        $totalProducts = 1284;
        $lowStockCount = 12;

        return view('admin.inventory', compact('products', 'totalProducts', 'lowStockCount'));
    }

    public function pos()
    {
        // Sample POS products
        $products = collect([
            (object)[
                'id' => 1,
                'name' => 'MacBook Pro M3 Max',
                'description' => '16-inch, 128GB Unified Memory, 4TB SSD Space Black',
                'price' => 109990000,
                'image' => null,
                'is_new' => true
            ],
            (object)[
                'id' => 2,
                'name' => 'ROG Zephyrus G16',
                'description' => 'RTX 4090, Intel Core i9-14900HX, OLED 240Hz Display',
                'price' => 89990000,
                'image' => null,
                'is_new' => false
            ],
            (object)[
                'id' => 3,
                'name' => 'Razer Blade 14',
                'description' => 'Mercury White, Ryzen 9 8945HS, RTX 4070, 32GB RAM',
                'price' => 69990000,
                'image' => null,
                'is_new' => false
            ],
            (object)[
                'id' => 4,
                'name' => 'Microsoft Surface Laptop 7',
                'description' => 'Snapdragon X Elite, 32GB RAM, 1TB SSD, 14.5" PixelSense',
                'price' => 51990000,
                'image' => null,
                'is_new' => false
            ],
            (object)[
                'id' => 5,
                'name' => 'Alienware m18 R2',
                'description' => '18-inch Display, i9-14900HX, RTX 4080, 64GB RAM',
                'price' => 99990000,
                'image' => null,
                'is_new' => false
            ],
            (object)[
                'id' => 6,
                'name' => 'Framework Laptop 16',
                'description' => 'DIY Edition, Ryzen 7, Radeon RX 7700S, Fully Modular',
                'price' => 56990000,
                'image' => null,
                'is_new' => false
            ],
        ]);

        $cartItems = [];
        $subtotal = 0;

        return view('admin.pos', compact('products', 'cartItems', 'subtotal'));
    }
}
