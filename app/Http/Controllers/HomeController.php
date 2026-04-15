<?php

namespace App\Http\Controllers;

use App\Models\Laptop;
use App\Models\Category;

class HomeController extends Controller
{
    public function index()
    {
        $featuredLaptops = Laptop::with('category')->latest()->take(8)->get();
        $categories = Category::all();
        
        return view('home', compact('featuredLaptops', 'categories'));
    }
}
