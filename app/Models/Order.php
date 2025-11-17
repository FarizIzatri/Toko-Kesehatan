<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

use App\Models\User;
use App\Models\OrderItem;
use App\Models\Transaction;
use App\Models\Report;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'shipping_address',
        'payment_method',
        'total_amount',
        'status',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function report(): HasOne
    {
        return $this->hasOne(Report::class);
    }
}