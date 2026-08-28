<?php

namespace App\Models;

use App\Enums\OrderStatus;
use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'serial_number',
        'customer_id',
        'address_id',

        'subtotal',
        'discount_total',
        'total',

        'status',
        'payment_method',
        'payment_status',

        'paid_at',
        'payment_reference',
        'payment_meta',

        'coupon_id',
        'coupon_discount',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'discount_total' => 'decimal:2',
        'total' => 'decimal:2',

        'payment_meta' => 'array',
        'paid_at' => 'datetime',

        'status' => OrderStatus::class,
        'payment_method' => PaymentMethod::class,
        'payment_status' => PaymentStatus::class,

        'coupon_discount' => 'decimal:2',
    ];

    public function items()
    {
        return $this->hasMany(Item::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }

    public function review()
    {
        return $this->hasOne(Review::class);
    }

    public function address()
    {
        return $this->belongsTo(Address::class);
    }


    public function isPayable(): bool
    {
        return $this->status === OrderStatus::Accepted
            && $this->payment_status !== PaymentStatus::Paid
            && $this->payment_status !== PaymentStatus::Canceled;
    }

    public function markPaid(?string $reference = null): void
    {
        $this->payment_status = PaymentStatus::Paid;
        $this->paid_at = now();

        if ($reference) {
            $this->payment_reference = $reference;
        }

        $this->save();
    }


    protected static function booted()
{
    static::created(function ($order) {

        if ($order->serial_number) return;

        $year = now()->year;

        $order->updateQuietly([
            'serial_number' => 'ORD-' . $year . '-' . str_pad($order->id, 6, '0', STR_PAD_LEFT),
        ]);
    });
}

}
