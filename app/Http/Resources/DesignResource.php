<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DesignResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $customer = $this->customer;
        $user     = $customer?->user; // polymorphic user

        $locale = app()->getLocale();

        return [
            'id'    => $this->id,

            'name' => [
                'en' => $this->getTranslation('name', 'en'),
                'ar' => $this->getTranslation('name', 'ar'),
                'current' => $this->getTranslation('name', $locale),
            ],

            'description' => [
                'en' => $this->getTranslation('description', 'en'),
                'ar' => $this->getTranslation('description', 'ar'),
                'current' => $this->getTranslation('description', $locale),
            ],

            'price' => (float) $this->price,

            'customer' => [
                'id' => $customer?->id,
                'name' => $user
                    ? $user->getTranslation('name', $locale)
                    : null,
            ],

            'sizes' => $this->whenLoaded('sizes', function () {
                return $this->sizes->map(function ($size) {
                    return [
                        'id'   => $size->id,
                        'code' => $size->code,
                        'name' => [
                            'en' => $size->getTranslation('name', 'en'),
                            'ar' => $size->getTranslation('name', 'ar'),
                        ],
                    ];
                })->values();
            }),


            'options' => $this->whenLoaded('options', function () {
                return $this->options->map(function ($opt) {
                    return [
                        'id'   => $opt->id,
                        'type' => $opt->type,
                        'name' => [
                            'en' => $opt->getTranslation('name', 'en'),
                            'ar' => $opt->getTranslation('name', 'ar'),
                        ],
                    ];
                })->values();
            }),


            'main_image_url' => $this->first_image_url ?: $this->getFirstMediaUrl('images'),
            'images' => $this->getMedia('images')
                ->map(fn($m) => [
                    'url'   => $m->getUrl(),
                    'thumb' => $m->getUrl('thumb'),
                ])->values(),

            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
