<?php

namespace App\Http\Requests\Design;

use Illuminate\Foundation\Http\FormRequest;

class StoreDesignRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth('customer')->check();
    }

    public function rules(): array
    {
        return [
            // name (translatable)
            'name'        => ['required', 'array'],
            'name.en'     => ['required', 'string', 'max:255'],
            'name.ar'     => ['nullable', 'string', 'max:255'],

            // description (translatable)
            'description'    => ['nullable', 'array'],
            'description.en' => ['nullable', 'string'],
            'description.ar' => ['nullable', 'string'],

            // price
            'price'      => ['required', 'numeric', 'min:0'],

            // sizes
            'size_ids'   => ['required', 'array', 'min:1'],
            'size_ids.*' => ['integer', 'exists:sizes,id'],

            // 🟢 design_options: [{ id, value }]
            'design_options'               => ['nullable', 'array'],
            'design_options.*.id'          => ['required', 'integer', 'exists:design_options,id'],
            'design_options.*.value'       => ['nullable', 'array'],
            'design_options.*.value.en'    => ['nullable', 'string'],
            'design_options.*.value.ar'    => ['nullable', 'string'],                                                                                       

            // images
            'images'       => ['required', 'array', 'min:1'],
            'images.*'     => ['file', 'image', 'mimes:jpeg,png,webp', 'max:5120'],
        ];
    }
}