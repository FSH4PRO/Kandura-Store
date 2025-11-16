<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AddressResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */

    public function toArray(Request $request): array
{
    return [
        'id'            => $this->id,
        'address_title' => $this->address_title, // رح ترجع حسب locale
        'country'       => $this->country,
        'city'          => $this->city,
        'area'          => $this->area,
        'street'        => $this->street,
        'building'      => $this->building,
        'apartment'     => $this->apartment,
        'postal_code'   => $this->postal_code,
        'phone'         => $this->phone,
        'longitude'     => $this->longitude,
        'latitude'      => $this->latitude,
        'is_default'    => (bool) $this->is_default,

        'user' => [
            'id'    => $this->user?->id,
            'name'  => $this->user?->name,
            'email' => $this->user?->email,
        ],

        'created_at'    => $this->created_at?->toIso8601String(),
        'updated_at'    => $this->updated_at?->toIso8601String(),
    ];
}

}
