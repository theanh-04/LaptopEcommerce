<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Laptop;
use App\Models\Order;

class UpdateLaptopsSeeder extends Seeder
{
    public function run(): void
    {
        // Update laptops with SKU
        $laptops = Laptop::all();
        foreach ($laptops as $laptop) {
            $brandPrefix = strtoupper(substr($laptop->brand, 0, 3));
            $laptop->sku = $brandPrefix . '-' . str_pad($laptop->id, 6, '0', STR_PAD_LEFT);
            $laptop->save();
        }

        // Update orders with order_number
        $orders = Order::all();
        foreach ($orders as $order) {
            $order->order_number = 'NK-' . $order->created_at->format('ymd') . '-' . str_pad($order->id, 4, '0', STR_PAD_LEFT);
            $order->save();
        }

        $this->command->info('Updated ' . $laptops->count() . ' laptops with SKU');
        $this->command->info('Updated ' . $orders->count() . ' orders with order_number');
    }
}
