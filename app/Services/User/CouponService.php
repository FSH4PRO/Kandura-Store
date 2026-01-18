<?php

namespace App\Services\User;

use App\Models\Order;
use App\Models\Coupon;
use App\Models\Customer;
use App\Models\CouponRedemption;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CouponService
{
   
    public function apply(Customer $customer, Order $order, string $code): Order
    {
        return DB::transaction(function () use ($customer, $order, $code) {

        if($order->payment_status === 'paid'){
            throw ValidationException::withMessages(['code' => __('coupons.order_already_paid')]);
        }

            $coupon = Coupon::where('code', strtoupper(trim($code)))
                ->lockForUpdate()
                ->first();

            if (! $coupon || ! $coupon->is_active) {
                throw ValidationException::withMessages([
                    'code' => __('coupons.invalid_coupon'),
                ]);
            }

            $now = now();
            $start = $coupon->starts_at ?? $coupon->created_at;

            if ($now->lt($start)) {
                throw ValidationException::withMessages(['code' => __('coupons.not_started')]);
            }

            if ($coupon->ends_at && $now->gt($coupon->ends_at)) {
                throw ValidationException::withMessages(['code' => __('coupons.expired')]);
            }

            if ($order->coupon_id) {
                throw ValidationException::withMessages(['code' => __('coupons.order_already_has_coupon')]);
            }

            // Whitelist
            if ($coupon->allowedCustomers()->exists()) {
                if (! $coupon->allowedCustomers()->where('customers.id', $customer->id)->exists()) {
                    throw ValidationException::withMessages(['code' => __('coupons.not_allowed')]);
                }
            }

            // Max uses
            if ($coupon->max_uses !== null) {
                $used = CouponRedemption::where('coupon_id', $coupon->id)->count();
                if ($used >= $coupon->max_uses) {
                    throw ValidationException::withMessages(['code' => __('coupons.usage_limit_reached')]);
                }
            }

            // Per customer once
            if (CouponRedemption::where('coupon_id', $coupon->id)
                ->where('customer_id', $customer->id)
                ->exists()) {
                throw ValidationException::withMessages(['code' => __('coupons.already_used')]);
            }

            $subtotal = (float) $order->subtotal;

            $discount = $coupon->type === 'percent'
                ? round($subtotal * ($coupon->amount / 100), 2)
                : round($coupon->amount, 2);

            if ($coupon->type === 'fixed' && $subtotal < $discount) {
                throw ValidationException::withMessages([
                    'code' => __('coupons.fixed_requires_min_subtotal'),
                ]);
            }

            $order->update([
                'coupon_id' => $coupon->id,
                'coupon_discount' => $discount,
                'discount_total' => $order->discount_total + $discount,
                'total' => max(0, $subtotal - $discount),
            ]);

            CouponRedemption::create([
                'coupon_id' => $coupon->id,
                'customer_id' => $customer->id,
                'order_id' => $order->id,
                'discount_amount' => $discount,
                'redeemed_at' => now(),
            ]);

            return $order->fresh('coupon');
        });
    }
}
