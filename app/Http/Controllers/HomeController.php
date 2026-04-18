<?php

namespace App\Http\Controllers;

use App\Models\Laptop;
use App\Models\Category;

class HomeController extends Controller
{
    public function index()
    {
        // Ưu tiên sản phẩm featured, nếu không đủ thì lấy thêm sản phẩm mới
        $featuredLaptops = Laptop::with('category')
            ->where('is_featured', true)
            ->latest()
            ->take(8)
            ->get();
        
        // Nếu không đủ 8 sản phẩm featured, lấy thêm sản phẩm mới
        if ($featuredLaptops->count() < 8) {
            $remaining = 8 - $featuredLaptops->count();
            $moreLaptops = Laptop::with('category')
                ->where('is_featured', false)
                ->latest()
                ->take($remaining)
                ->get();
            $featuredLaptops = $featuredLaptops->merge($moreLaptops);
        }
        
        $categories = Category::all();
        
        return view('home', compact('featuredLaptops', 'categories'));
    }
}
