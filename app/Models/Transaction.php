<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use App\Models\Order;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'amount',
        'provider',
        'transaction_code',
        'status',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}