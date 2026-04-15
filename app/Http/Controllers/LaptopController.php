<?php

namespace App\Http\Controllers;

use App\Models\Laptop;
use App\Models\Category;
use Illuminate\Http\Request;

class LaptopController extends Controller
{
    public function index(Request $request)
    {
        $query = Laptop::with('category');
        
        if ($request->has('category')) {
            $query->whereHas('category', function($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }
        
        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        
        $laptops = $query->paginate(12);
        $categories = Category::all();
        
        return view('laptops.index', compact('laptops', 'categories'));
    }

    public function show($slug)
    {
        $laptop = Laptop::with('category')->where('slug', $slug)->firstOrFail();
        return view('laptops.show', compact('laptop'));
    }
}
