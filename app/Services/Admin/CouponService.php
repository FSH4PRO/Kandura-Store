<?php

namespace App\Services\Admin;

use App\Models\Coupon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CouponService
{
    public function create(array $data, int $adminId): Coupon
    {
        return DB::transaction(function () use ($data, $adminId) {

            $coupon = Coupon::create([
                'code' => strtoupper(trim($data['code'])),
                'type' => $data['type'], // percent | fixed
                'amount' => $data['amount'],
                'max_uses' => $data['max_uses'] ?? null,
                'starts_at' => $data['starts_at'] ?? now(),
                'ends_at' => $data['ends_at'] ?? null,
                'is_active' => (bool) ($data['is_active'] ?? true),
                'created_by_admin_id' => $adminId,
            ]);

            if (!empty($data['allowed_customers'])) {
                $coupon->allowedCustomers()->sync($data['allowed_customers']);
            }

            return $coupon->fresh(['allowedCustomers']);
        });
    }

    public function update(Coupon $coupon, array $data): Coupon
    {
        return DB::transaction(function () use ($coupon, $data) {

            $coupon->update([
                'code' => strtoupper(trim($data['code'])),
                'type' => $data['type'],
                'amount' => $data['amount'],
                'max_uses' => $data['max_uses'] ?? null,
                'starts_at' => $data['starts_at'] ?? $coupon->starts_at,
                'ends_at' => $data['ends_at'] ?? null,
                'is_active' => (bool) ($data['is_active'] ?? false),
            ]);

            if (array_key_exists('allowed_customers', $data)) {
                $coupon->allowedCustomers()->sync($data['allowed_customers'] ?? []);
            }

            return $coupon->fresh(['allowedCustomers']);
        });
    }

    public function toggle(Coupon $coupon): Coupon
    {
        $coupon->update([
            'is_active' => ! $coupon->is_active,
        ]);

        return $coupon->fresh();
    }

    public function delete(Coupon $coupon): void
    {
        if ($coupon->redemptions()->exists()) {
            throw ValidationException::withMessages([
                'coupon' => __('coupons.cannot_delete_used_coupon'),
            ]);
        }

        DB::transaction(function () use ($coupon) {
            $coupon->allowedCustomers()->detach();
            $coupon->delete();
        });
    }
}
