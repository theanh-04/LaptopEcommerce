<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Customer;
use Carbon\Carbon;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        $customers = [
            [
                'name' => 'Nguyễn Văn An',
                'email' => 'nguyenvanan@gmail.com',
                'phone' => '0901234567',
                'address' => '123 Nguyễn Huệ, Quận 1, TP.HCM',
                'date_of_birth' => '1990-05-15',
                'gender' => 'male',
                'loyalty_points' => 1500,
                'tier' => 'gold',
                'total_spent' => 45000000,
                'total_orders' => 8,
                'last_purchase_at' => Carbon::now()->subDays(5),
                'is_active' => true
            ],
            [
                'name' => 'Trần Thị Bình',
                'email' => 'tranthibinh@gmail.com',
                'phone' => '0912345678',
                'address' => '456 Lê Lợi, Quận 3, TP.HCM',
                'date_of_birth' => '1995-08-20',
                'gender' => 'female',
                'loyalty_points' => 2800,
                'tier' => 'platinum',
                'total_spent' => 78000000,
                'total_orders' => 15,
                'last_purchase_at' => Carbon::now()->subDays(2),
                'is_active' => true
            ],
            [
                'name' => 'Lê Minh Cường',
                'email' => 'leminhcuong@gmail.com',
                'phone' => '0923456789',
                'address' => '789 Trần Hưng Đạo, Quận 5, TP.HCM',
                'date_of_birth' => '1988-12-10',
                'gender' => 'male',
                'loyalty_points' => 800,
                'tier' => 'silver',
                'total_spent' => 28000000,
                'total_orders' => 5,
                'last_purchase_at' => Carbon::now()->subDays(15),
                'is_active' => true
            ],
            [
                'name' => 'Phạm Thu Dung',
                'email' => 'phamthudung@gmail.com',
                'phone' => '0934567890',
                'address' => '321 Võ Văn Tần, Quận 3, TP.HCM',
                'date_of_birth' => '1992-03-25',
                'gender' => 'female',
                'loyalty_points' => 350,
                'tier' => 'bronze',
                'total_spent' => 12000000,
                'total_orders' => 2,
                'last_purchase_at' => Carbon::now()->subDays(30),
                'is_active' => true
            ],
            [
                'name' => 'Hoàng Văn Em',
                'email' => 'hoangvanem@gmail.com',
                'phone' => '0945678901',
                'address' => '654 Hai Bà Trưng, Quận 1, TP.HCM',
                'date_of_birth' => '1985-07-18',
                'gender' => 'male',
                'loyalty_points' => 1200,
                'tier' => 'silver',
                'total_spent' => 35000000,
                'total_orders' => 6,
                'last_purchase_at' => Carbon::now()->subDays(10),
                'is_active' => true
            ],
            [
                'name' => 'Đỗ Thị Phương',
                'email' => 'dothiphuong@gmail.com',
                'phone' => '0956789012',
                'address' => '987 Nguyễn Thị Minh Khai, Quận 3, TP.HCM',
                'date_of_birth' => '1998-11-05',
                'gender' => 'female',
                'loyalty_points' => 450,
                'tier' => 'bronze',
                'total_spent' => 15000000,
                'total_orders' => 3,
                'last_purchase_at' => Carbon::now()->subDays(20),
                'is_active' => true
            ],
            [
                'name' => 'Vũ Minh Giang',
                'email' => 'vuminh giang@gmail.com',
                'phone' => '0967890123',
                'address' => '147 Pasteur, Quận 1, TP.HCM',
                'date_of_birth' => '1991-09-12',
                'gender' => 'male',
                'loyalty_points' => 1800,
                'tier' => 'gold',
                'total_spent' => 52000000,
                'total_orders' => 10,
                'last_purchase_at' => Carbon::now()->subDays(7),
                'is_active' => true
            ],
            [
                'name' => 'Bùi Thu Hà',
                'email' => 'buithuha@gmail.com',
                'phone' => '0978901234',
                'address' => '258 Cách Mạng Tháng 8, Quận 10, TP.HCM',
                'date_of_birth' => '1994-06-30',
                'gender' => 'female',
                'loyalty_points' => 600,
                'tier' => 'silver',
                'total_spent' => 22000000,
                'total_orders' => 4,
                'last_purchase_at' => Carbon::now()->subDays(12),
                'is_active' => true
            ],
            [
                'name' => 'Ngô Văn Hùng',
                'email' => 'ngovanhung@gmail.com',
                'phone' => '0989012345',
                'address' => '369 Điện Biên Phủ, Quận Bình Thạnh, TP.HCM',
                'date_of_birth' => '1987-04-22',
                'gender' => 'male',
                'loyalty_points' => 200,
                'tier' => 'bronze',
                'total_spent' => 8000000,
                'total_orders' => 1,
                'last_purchase_at' => Carbon::now()->subDays(45),
                'is_active' => true
            ],
            [
                'name' => 'Lý Thị Lan',
                'email' => 'lythilan@gmail.com',
                'phone' => '0990123456',
                'address' => '741 Lý Thường Kiệt, Quận 10, TP.HCM',
                'date_of_birth' => '1993-01-08',
                'gender' => 'female',
                'loyalty_points' => 3200,
                'tier' => 'platinum',
                'total_spent' => 95000000,
                'total_orders' => 18,
                'last_purchase_at' => Carbon::now()->subDays(1),
                'is_active' => true
            ]
        ];

        foreach ($customers as $customer) {
            Customer::create($customer);
        }
    }
}
