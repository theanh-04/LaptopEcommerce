<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Promotion;
use Carbon\Carbon;

class PromotionSeeder extends Seeder
{
    public function run(): void
    {
        $promotions = [
            [
                'code' => 'WELCOME10',
                'name' => 'Chào mừng khách hàng mới',
                'description' => 'Giảm 10% cho đơn hàng đầu tiên',
                'type' => 'percentage',
                'value' => 10,
                'min_order_amount' => 5000000,
                'max_discount' => 1000000,
                'usage_limit' => 100,
                'used_count' => 15,
                'start_date' => Carbon::now()->subDays(10),
                'end_date' => Carbon::now()->addDays(20),
                'is_active' => true
            ],
            [
                'code' => 'SUMMER2026',
                'name' => 'Khuyến mãi mùa hè 2026',
                'description' => 'Giảm 15% cho tất cả laptop gaming',
                'type' => 'percentage',
                'value' => 15,
                'min_order_amount' => 10000000,
                'max_discount' => 3000000,
                'usage_limit' => 200,
                'used_count' => 45,
                'start_date' => Carbon::now()->subDays(5),
                'end_date' => Carbon::now()->addDays(60),
                'is_active' => true
            ],
            [
                'code' => 'FLASH500K',
                'name' => 'Flash Sale - Giảm 500K',
                'description' => 'Giảm ngay 500.000đ cho đơn từ 15 triệu',
                'type' => 'fixed',
                'value' => 500000,
                'min_order_amount' => 15000000,
                'max_discount' => null,
                'usage_limit' => 50,
                'used_count' => 32,
                'start_date' => Carbon::now()->subDays(2),
                'end_date' => Carbon::now()->addDays(3),
                'is_active' => true
            ],
            [
                'code' => 'MEGA20',
                'name' => 'Mega Sale 20%',
                'description' => 'Giảm 20% cho đơn hàng trên 20 triệu',
                'type' => 'percentage',
                'value' => 20,
                'min_order_amount' => 20000000,
                'max_discount' => 5000000,
                'usage_limit' => 150,
                'used_count' => 78,
                'start_date' => Carbon::now()->subDays(15),
                'end_date' => Carbon::now()->addDays(15),
                'is_active' => true
            ],
            [
                'code' => 'FREESHIP',
                'name' => 'Miễn phí vận chuyển',
                'description' => 'Giảm 50.000đ phí ship cho đơn từ 3 triệu',
                'type' => 'fixed',
                'value' => 50000,
                'min_order_amount' => 3000000,
                'max_discount' => null,
                'usage_limit' => null, // Không giới hạn
                'used_count' => 234,
                'start_date' => Carbon::now()->subDays(30),
                'end_date' => Carbon::now()->addDays(90),
                'is_active' => true
            ],
            [
                'code' => 'EXPIRED2025',
                'name' => 'Khuyến mãi đã hết hạn',
                'description' => 'Mã này đã hết hạn sử dụng',
                'type' => 'percentage',
                'value' => 25,
                'min_order_amount' => 10000000,
                'max_discount' => 2000000,
                'usage_limit' => 100,
                'used_count' => 100,
                'start_date' => Carbon::now()->subDays(60),
                'end_date' => Carbon::now()->subDays(10),
                'is_active' => false
            ],
        ];

        foreach ($promotions as $promotion) {
            Promotion::create($promotion);
        }
    }
}
