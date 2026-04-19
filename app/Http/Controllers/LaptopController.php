<?php

namespace App\Http\Controllers;

use App\Models\Laptop;
use App\Models\Category;
use Illuminate\Http\Request;

class LaptopController extends Controller
{
    public function index(Request $request)
    {
        $query = Laptop::with(['category', 'brand']);
        
        // Search by name
        if ($request->has('search') && $request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        
        // Filter by category
        if ($request->has('category') && $request->category) {
            $query->whereHas('category', function($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }
        
        // Filter by brand
        if ($request->has('brand') && $request->brand) {
            $query->where('brand_id', $request->brand);
        }
        
        // Filter by price range
        if ($request->has('min_price') && $request->min_price) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->has('max_price') && $request->max_price) {
            $query->where('price', '<=', $request->max_price);
        }
        
        // Filter by specs
        if ($request->has('cpu') && $request->cpu) {
            $query->where('cpu', 'like', '%' . $request->cpu . '%');
        }
        if ($request->has('ram') && $request->ram) {
            $query->where('ram', 'like', '%' . $request->ram . '%');
        }
        if ($request->has('storage') && $request->storage) {
            $query->where('storage', 'like', '%' . $request->storage . '%');
        }
        
        // Sort
        $sort = $request->get('sort', 'newest');
        switch($sort) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            default: // newest
                $query->orderBy('created_at', 'desc');
        }
        
        $laptops = $query->paginate(12)->appends($request->all());
        $categories = Category::all();
        $brands = \App\Models\Brand::orderBy('name')->get();
        
        return view('laptops.index', compact('laptops', 'categories', 'brands'));
    }

    public function show($slug)
    {
        $laptop = Laptop::with('category')->where('slug', $slug)->firstOrFail();
        return view('laptops.show', compact('laptop'));
    }
}
