<?php

namespace App\Models;

use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'customer_id',
        'address_id',
        'subtotal',
        'discount_total',
        'total',
        'payment_method',
        'status',
    ];

    protected $casts = [
        'status' => OrderStatus::class,
        'subtotal' => 'decimal:2',
        'discount' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    public function items()
    {
        return $this->hasMany(Item::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    
}
