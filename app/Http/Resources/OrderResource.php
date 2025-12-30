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

            'subtotal'  => (float) $this->subtotal,
            'discount'  => (float) $this->discount,
            'total'     => (float) $this->total,

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
