<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Laptop;

class LaptopSeeder extends Seeder
{
    public function run(): void
    {
        $laptops = [
            [
                'category_id' => 1,
                'name' => 'ASUS ROG Strix G15',
                'slug' => 'asus-rog-strix-g15',
                'description' => 'Laptop gaming mạnh mẽ với RTX 3060',
                'brand' => 'ASUS',
                'processor' => 'Intel Core i7-12700H',
                'ram' => '16GB DDR5',
                'storage' => '512GB SSD',
                'display' => '15.6" FHD 144Hz',
                'graphics' => 'NVIDIA RTX 3060 6GB',
                'price' => 28990000,
                'stock' => 10,
                'image' => 'https://images.unsplash.com/photo-1603302576837-37561b2e2302?w=800&h=600&fit=crop'
            ],
            [
                'category_id' => 2,
                'name' => 'Dell Inspiron 15',
                'slug' => 'dell-inspiron-15',
                'description' => 'Laptop văn phòng tin cậy',
                'brand' => 'Dell',
                'processor' => 'Intel Core i5-1235U',
                'ram' => '8GB DDR4',
                'storage' => '256GB SSD',
                'display' => '15.6" FHD',
                'graphics' => 'Intel Iris Xe',
                'price' => 15990000,
                'stock' => 15,
                'image' => 'https://images.unsplash.com/photo-1588872657578-7efd1f1555ed?w=800&h=600&fit=crop'
            ],
            [
                'category_id' => 3,
                'name' => 'MacBook Pro 14',
                'slug' => 'macbook-pro-14',
                'description' => 'Laptop đồ họa chuyên nghiệp',
                'brand' => 'Apple',
                'processor' => 'Apple M2 Pro',
                'ram' => '16GB Unified',
                'storage' => '512GB SSD',
                'display' => '14.2" Liquid Retina XDR',
                'graphics' => 'Apple M2 Pro GPU',
                'price' => 52990000,
                'stock' => 5,
                'image' => 'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?w=800&h=600&fit=crop'
            ],
            [
                'category_id' => 4,
                'name' => 'HP Pavilion 14',
                'slug' => 'hp-pavilion-14',
                'description' => 'Laptop sinh viên giá tốt',
                'brand' => 'HP',
                'processor' => 'AMD Ryzen 5 5500U',
                'ram' => '8GB DDR4',
                'storage' => '256GB SSD',
                'display' => '14" FHD',
                'graphics' => 'AMD Radeon',
                'price' => 12990000,
                'stock' => 20,
                'image' => 'https://images.unsplash.com/photo-1496181133206-80ce9b88a853?w=800&h=600&fit=crop'
            ],
            [
                'category_id' => 1,
                'name' => 'MSI Katana GF66',
                'slug' => 'msi-katana-gf66',
                'description' => 'Gaming laptop với hiệu năng vượt trội',
                'brand' => 'MSI',
                'processor' => 'Intel Core i7-11800H',
                'ram' => '16GB DDR4',
                'storage' => '512GB SSD',
                'display' => '15.6" FHD 144Hz',
                'graphics' => 'NVIDIA RTX 3050 Ti',
                'price' => 24990000,
                'stock' => 8,
                'image' => 'https://images.unsplash.com/photo-1593642632823-8f785ba67e45?w=800&h=600&fit=crop'
            ],
            [
                'category_id' => 2,
                'name' => 'Lenovo ThinkPad E14',
                'slug' => 'lenovo-thinkpad-e14',
                'description' => 'Laptop doanh nghiệp bền bỉ',
                'brand' => 'Lenovo',
                'processor' => 'Intel Core i5-1135G7',
                'ram' => '8GB DDR4',
                'storage' => '256GB SSD',
                'display' => '14" FHD',
                'graphics' => 'Intel Iris Xe',
                'price' => 17990000,
                'stock' => 12,
                'image' => 'https://images.unsplash.com/photo-1525547719571-a2d4ac8945e2?w=800&h=600&fit=crop'
            ],
            [
                'category_id' => 3,
                'name' => 'Dell XPS 15',
                'slug' => 'dell-xps-15',
                'description' => 'Workstation mạnh mẽ cho sáng tạo',
                'brand' => 'Dell',
                'processor' => 'Intel Core i7-12700H',
                'ram' => '32GB DDR5',
                'storage' => '1TB SSD',
                'display' => '15.6" 4K OLED',
                'graphics' => 'NVIDIA RTX 3050 Ti',
                'price' => 48990000,
                'stock' => 6,
                'image' => 'https://images.unsplash.com/photo-1593642632559-0c6d3fc62b89?w=800&h=600&fit=crop'
            ],
            [
                'category_id' => 4,
                'name' => 'Acer Aspire 5',
                'slug' => 'acer-aspire-5',
                'description' => 'Laptop học tập giá rẻ',
                'brand' => 'Acer',
                'processor' => 'Intel Core i3-1115G4',
                'ram' => '8GB DDR4',
                'storage' => '256GB SSD',
                'display' => '15.6" FHD',
                'graphics' => 'Intel UHD',
                'price' => 10990000,
                'stock' => 25,
                'image' => 'https://images.unsplash.com/photo-1484788984921-03950022c9ef?w=800&h=600&fit=crop'
            ],
        ];

        foreach ($laptops as $laptop) {
            Laptop::create($laptop);
        }
    }
}
