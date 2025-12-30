<?php

namespace App\Models;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Enums\PaymentMethod;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'customer_id',
        'address_id',
        'subtotal',
        'discount_total',
        'total_amount',
        'status',
        'currency',

        'payment_method',
        'payment_status',
        'paid_at',
        'payment_reference',
        'payment_meta',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'discount_total' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'payment_meta' => 'array',
        'paid_at' => 'datetime',

        'status' => OrderStatus::class,
        'payment_method' => PaymentMethod::class,
        'payment_status' => PaymentStatus::class,
    ];


    public function items()
    {
        return $this->hasMany(Item::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function isPayable(): bool
    {
        return in_array($this->status->value, [OrderStatus::Accepted->value], true)
            && $this->payment_status !== PaymentStatus::Paid
            && $this->payment_status !== PaymentStatus::Canceled;
    }

    public function markPaid(?string $reference = null): void
    {
        $this->payment_status = PaymentStatus::Paid;
        $this->paid_at = now();
        if ($reference) $this->payment_reference = $reference;
        $this->save();
    }
}
