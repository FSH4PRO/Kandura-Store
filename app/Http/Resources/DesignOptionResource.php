<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DesignOptionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'type' => $this->type?->value ?? $this->type,
            'name' => [
                'en' => $this->getTranslation('name', 'en'),
                'ar' => $this->getTranslation('name', 'ar'),
            ],
            'is_active' => $this->is_active,
        ];
    }
}
