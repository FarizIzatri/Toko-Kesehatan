<?php

namespace App\Events;

use App\Models\Order; // Import Order
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderCompleted
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $order; // Buat properti publik

    // Terima Order saat event dibuat
    public function __construct(Order $order)
    {
        $this->order = $order;
    }
}