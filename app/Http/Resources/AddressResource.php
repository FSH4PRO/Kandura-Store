<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AddressResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'        => $this->id,
            'street'    => $this->street,
            'latitude'  => $this->latitude,
            'longitude' => $this->longitude,
            'details'   => $this->details,

            'city' => [
                'id'   => $this->city?->id,
                'name' => $this->city?->name, 
            ],

            'user' => [
                'id'    => $this->customer?->id,
                'name'  => $this->customer->user?->name,
                'phone' => $this->customer?->phone,
            ],

            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
