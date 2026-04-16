<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Brand;
use App\Models\Laptop;
use Illuminate\Support\Str;

class BrandSeeder extends Seeder
{
    public function run(): void
    {
        // Get distinct brands from laptops table
        $brands = Laptop::distinct('brand')
            ->pluck('brand')
            ->filter()
            ->sort()
            ->values();

        $order = 1;
        foreach ($brands as $brandName) {
            Brand::create([
                'name' => $brandName,
                'slug' => Str::slug($brandName),
                'is_active' => true,
                'display_order' => $order++
            ]);
        }
    }
}
