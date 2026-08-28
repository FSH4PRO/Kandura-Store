<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SizeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'code' => $this->code?->value ?? $this->code,
            'name' => [
                'en' => $this->getTranslation('name', 'en'),
                'ar' => $this->getTranslation('name', 'ar'),
            ],
            'sort_order' => $this->sort_order,
        ];
    }
}
