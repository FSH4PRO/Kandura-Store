<?php

namespace App\Models;

use App\Enums\CouponType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Coupon extends Model
{
    protected $fillable = [
        'code',
        'type',
        'amount',
        'starts_at',
        'ends_at',
        'max_uses',
        'is_active',
        'created_by_admin_id',
    ];

    protected $casts = [
        'type' => CouponType::class,
        'amount' => 'decimal:2',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function redemptions(): HasMany
    {
        return $this->hasMany(CouponRedemption::class);
    }

    public function allowedCustomers(): BelongsToMany
    {
        return $this->belongsToMany(Customer::class, 'coupon_customer');
    }

    public function createdByAdmin(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'created_by_admin_id');
    }

    public function isCurrentlyValid(): bool
    {
        if (!$this->is_active) return false;

        $now = now();
        $start = $this->starts_at ?? $this->created_at;

        if ($start && $now->lt($start)) return false;
        if ($this->ends_at && $now->gt($this->ends_at)) return false;

        if ($this->max_uses && $this->redemptions()->count() >= $this->max_uses) return false;

        return true;
    }

    public function canBeUsedByCustomer(int $customerId): bool
    {
        // If no whitelist, anyone can use it
        if ($this->allowedCustomers()->count() === 0) return true;

        return $this->allowedCustomers()->where('customer_id', $customerId)->exists();
    }

    public function hasBeenUsedByCustomer(int $customerId): bool
    {
        return $this->redemptions()->where('customer_id', $customerId)->exists();
    }

    public function computeDiscount(float $subtotal): float
    {
        $discount = 0;

        if ($this->type === CouponType::Percent) {
            $discount = round($subtotal * ((float)$this->amount / 100), 2);
        } else {
            // Fixed amount - ensure it doesn't exceed subtotal
            $discount = min(round((float)$this->amount, 2), $subtotal);
        }

        return $discount;
    }
}
