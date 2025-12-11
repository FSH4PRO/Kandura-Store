<?php

namespace App\Http\Requests\Design;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDesignRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth('customer')->check();
    }

    public function rules(): array
    {
        return [
            'name'        => ['sometimes', 'array'],
            'name.en'     => ['sometimes', 'string', 'max:255'],
            'name.ar'     => ['sometimes', 'string', 'max:255'],

            'description'    => ['sometimes', 'array'],
            'description.en' => ['sometimes', 'string'],
            'description.ar' => ['sometimes', 'string'],

            'price'      => ['sometimes', 'numeric', 'min:0'],

            'size_ids'   => ['sometimes', 'array', 'min:1'],
            'size_ids.*' => ['integer', 'exists:sizes,id'],

            // 🟢 design_options في التعديل
            'design_options'               => ['nullable', 'array'],
            'design_options.*.id'          => ['required', 'integer', 'exists:design_options,id'],
            'design_options.*.value'       => ['nullable', 'array'],
            'design_options.*.value.en'    => ['nullable', 'string'],
            'design_options.*.value.ar'    => ['nullable', 'string'],

            'images'       => ['sometimes', 'array', 'min:1'],
            'images.*'     => ['file', 'image', 'mimes:jpeg,png,webp', 'max:5120'],
        ];
    }
}