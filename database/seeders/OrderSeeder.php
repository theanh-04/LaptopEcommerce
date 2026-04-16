<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Laptop;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $laptops = Laptop::all();
        
        if ($laptops->isEmpty()) {
            $this->command->error('Không có sản phẩm nào trong database. Hãy chạy LaptopSeeder trước!');
            return;
        }

        // Đơn hàng chờ duyệt
        $pendingOrders = [
            [
                'customer_name' => 'Nguyễn Văn A',
                'customer_email' => 'nguyenvana@gmail.com',
                'customer_phone' => '0901234567',
                'customer_address' => '123 Đường Lê Lợi, Quận 1, TP.HCM',
                'status' => 'pending',
                'items_count' => 2
            ],
            [
                'customer_name' => 'Trần Thị B',
                'customer_email' => 'tranthib@gmail.com',
                'customer_phone' => '0912345678',
                'customer_address' => '456 Đường Nguyễn Huệ, Quận 1, TP.HCM',
                'status' => 'pending',
                'items_count' => 1
            ],
            [
                'customer_name' => 'Lê Văn C',
                'customer_email' => 'levanc@gmail.com',
                'customer_phone' => '0923456789',
                'customer_address' => '789 Đường Trần Hưng Đạo, Quận 5, TP.HCM',
                'status' => 'pending',
                'items_count' => 3
            ],
        ];

        // Đơn hàng đang xử lý
        $processingOrders = [
            [
                'customer_name' => 'Phạm Thị D',
                'customer_email' => 'phamthid@gmail.com',
                'customer_phone' => '0934567890',
                'customer_address' => '321 Đường Võ Văn Tần, Quận 3, TP.HCM',
                'status' => 'processing',
                'items_count' => 1
            ],
            [
                'customer_name' => 'Hoàng Văn E',
                'customer_email' => 'hoangvane@gmail.com',
                'customer_phone' => '0945678901',
                'customer_address' => '654 Đường Cách Mạng Tháng 8, Quận 10, TP.HCM',
                'status' => 'processing',
                'items_count' => 2
            ],
        ];

        // Đơn hàng hoàn thành
        $completedOrders = [
            [
                'customer_name' => 'Vũ Thị F',
                'customer_email' => 'vuthif@gmail.com',
                'customer_phone' => '0956789012',
                'customer_address' => '987 Đường Điện Biên Phủ, Quận Bình Thạnh, TP.HCM',
                'status' => 'completed',
                'items_count' => 1
            ],
            [
                'customer_name' => 'Đặng Văn G',
                'customer_email' => 'dangvang@gmail.com',
                'customer_phone' => '0967890123',
                'customer_address' => '147 Đường Lý Thường Kiệt, Quận 11, TP.HCM',
                'status' => 'completed',
                'items_count' => 2
            ],
            [
                'customer_name' => 'Bùi Thị H',
                'customer_email' => 'buithih@gmail.com',
                'customer_phone' => '0978901234',
                'customer_address' => '258 Đường Phan Văn Trị, Quận Gò Vấp, TP.HCM',
                'status' => 'completed',
                'items_count' => 1
            ],
        ];

        $allOrders = array_merge($pendingOrders, $processingOrders, $completedOrders);
        $orderNumber = 1;

        foreach ($allOrders as $orderData) {
            $itemsCount = $orderData['items_count'];
            unset($orderData['items_count']);

            // Tạo order
            $order = Order::create([
                'order_number' => 'NK-' . date('ymd') . '-' . str_pad($orderNumber++, 4, '0', STR_PAD_LEFT),
                'customer_name' => $orderData['customer_name'],
                'customer_email' => $orderData['customer_email'],
                'customer_phone' => $orderData['customer_phone'],
                'customer_address' => $orderData['customer_address'],
                'status' => $orderData['status'],
                'total_amount' => 0, // Sẽ tính sau
            ]);

            // Thêm order items
            $totalAmount = 0;
            $selectedLaptops = $laptops->random(min($itemsCount, $laptops->count()));
            
            foreach ($selectedLaptops as $laptop) {
                $quantity = rand(1, 2);
                $price = $laptop->price;
                
                OrderItem::create([
                    'order_id' => $order->id,
                    'laptop_id' => $laptop->id,
                    'quantity' => $quantity,
                    'price' => $price,
                ]);

                $totalAmount += $price * $quantity;
            }

            // Cập nhật tổng tiền
            $order->update(['total_amount' => $totalAmount]);
        }

        $this->command->info('Đã tạo ' . count($allOrders) . ' đơn hàng mẫu!');
    }
}
