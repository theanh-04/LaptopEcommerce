<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Gaming', 'slug' => 'gaming', 'description' => 'Laptop chơi game hiệu năng cao'],
            ['name' => 'Văn phòng', 'slug' => 'van-phong', 'description' => 'Laptop cho công việc văn phòng'],
            ['name' => 'Đồ họa', 'slug' => 'do-hoa', 'description' => 'Laptop cho thiết kế đồ họa'],
            ['name' => 'Sinh viên', 'slug' => 'sinh-vien', 'description' => 'Laptop phù hợp sinh viên'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
