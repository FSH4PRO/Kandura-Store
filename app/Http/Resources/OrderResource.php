<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $customer = $this->customer;
        $user     = $customer?->user;

        return [
            'id'        => $this->id,
            'status'    => $this->status,

            'subtotal'       => (float) $this->subtotal,
            'discount_total' => (float) $this->discount_total,
            'total'          => (float) $this->total,

            'payment_method' => $this->payment_method,
            'payment_status' => $this->payment_status,
            'paid_at'        => $this->paid_at?->toIso8601String(),

            'coupon' => $this->whenLoaded('coupon', fn() => [
                'id'     => $this->coupon?->id,
                'code'   => $this->coupon?->code,
                'type'   => $this->coupon?->type,
                'amount' => (float) $this->coupon?->amount,
            ]),

            'coupon_discount' => (float) ($this->coupon_discount ?? 0),

            'customer' => $customer ? [
                'id'   => $customer->id,
                'name' => $user?->name,
            ] : null,

            'items' => ItemResource::collection($this->whenLoaded('items')),

            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
