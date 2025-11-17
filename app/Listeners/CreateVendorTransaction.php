<?php

namespace App\Listeners;

use App\Events\OrderCompleted;
use App\Models\VendorTransaction; 
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

// Implement ShouldQueue agar berjalan di background
class CreateVendorTransaction implements ShouldQueue
{
    use InteractsWithQueue;

    public function __construct()
    {
        //
    }

    // Method ini akan dijalankan saat OrderCompleted dipicu
    public function handle(OrderCompleted $event): void
    {
        $order = $event->order;

        // Load item-item dalam pesanan
        $order->load('orderItems');

        // Loop setiap item dan buat catatan transaksi
        foreach ($order->orderItems as $item) {
            VendorTransaction::create([
                'shop_id' => $item->shop_id,
                'order_id' => $order->id,
                'order_item_id' => $item->id,
                'product_name' => $item->product_name,
                'quantity' => $item->quantity,
                'price' => $item->price,
                'total_amount' => $item->price * $item->quantity,
                'status' => 'completed', // Status default saat pesanan selesai
            ]);
        }
    }
}