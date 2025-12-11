<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ItemOptionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $option = $this->option; // DesignOption

        return [
            'id'    => $this->id,
            'option' => $option ? [
                'id'   => $option->id,
                'type' => $option->type,
                'name' => $option->getTranslations('name'), 
            ] : null,

           
            'value' => $this->value, 
        ];
    }
}
