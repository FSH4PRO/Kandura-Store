<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CouponResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'type' => $this->type,
            'amount' => $this->amount,
            'formatted_amount' => $this->type === 'percent'
                ? $this->amount . '%'
                : number_format($this->amount, 2) . ' ' . __('currency'),
            'starts_at' => $this->starts_at?->format('Y-m-d H:i:s'),
            'ends_at' => $this->ends_at?->format('Y-m-d H:i:s'),
            'max_uses' => $this->max_uses,
            'used_count' => $this->redemptions_count ?? $this->redemptions()->count(),
            'is_active' => $this->is_active,
            'is_valid' => $this->isCurrentlyValid(),
            'created_by_admin' => $this->whenLoaded('createdByAdmin', function () {
                return [
                    'id' => $this->createdByAdmin->id,
                    'name' => $this->createdByAdmin->name,
                ];
            }),
            'allowed_customers' => $this->whenLoaded('allowedCustomers', function () {
                return $this->allowedCustomers->map(function ($customer) {
                    return [
                        'id' => $customer->id,
                        'name' => $customer->name,
                        'email' => $customer->email,
                    ];
                });
            }),
            'redemptions' => $this->whenLoaded('redemptions', function () {
                return $this->redemptions->map(function ($redemption) {
                    return [
                        'id' => $redemption->id,
                        'customer' => [
                            'id' => $redemption->customer->id,
                            'name' => $redemption->customer->name,
                        ],
                        'order' => [
                            'id' => $redemption->order->id,
                            'order_number' => $redemption->order->order_number,
                        ],
                        'discount_amount' => $redemption->discount_amount,
                        'redeemed_at' => $redemption->redeemed_at->format('Y-m-d H:i:s'),
                    ];
                });
            }),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
