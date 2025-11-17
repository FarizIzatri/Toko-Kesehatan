<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VendorTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'shop_id',
        'order_id',
        'order_item_id',
        'product_name',
        'quantity',
        'price',
        'total_amount',
        'status',
    ];

    // Relasi ke Toko (Vendor)
    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    // Relasi ke Pesanan
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    // Relasi ke Item Pesanan
    public function orderItem(): BelongsTo
    {
        return $this->belongsTo(OrderItem::class);
    }
}