<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $design = $this->design;
        $size   = $this->size;

        return [
            'id'         => $this->id,
            'quantity'   => (int) $this->quantity,
            'unit_price' => (float) $this->unit_price,
            'line_total' => (float) $this->line_total,

            
            'design' => $design ? [
                'id'          => $design->id,
                'name'        => $design->getTranslations('name'),
                'description' => $design->getTranslations('description'),
                'price'       => (float) $design->price,
                'main_image'  => $design->first_image_url ?? $design->getFirstMediaUrl('images'),
            ] : null,

           
            'size' => $size ? [
                'id'   => $size->id,
                'code' => $size->code,
                'name' => $size->getTranslations('name'),
            ] : null,

           
            'options' => ItemOptionResource::collection($this->whenLoaded('options')),

            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
