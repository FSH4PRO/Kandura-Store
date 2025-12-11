<?php

namespace App\Http\Requests\Design;

use Illuminate\Foundation\Http\FormRequest;

class DesignIndexRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth('customer')->check();
    }

    public function rules(): array
    {
        return [
            'search'       => ['nullable', 'string', 'max:255'],
            'size_id'      => ['nullable', 'integer', 'exists:sizes,id'],
            'price_min'    => ['nullable', 'numeric', 'min:0'],
            'price_max'    => ['nullable', 'numeric', 'min:0'],
            'option_id'    => ['nullable', 'integer', 'exists:design_options,id'],
            'creator_id'   => ['nullable', 'integer'],

            'per_page'     => ['nullable', 'integer', 'min:1', 'max:100'],
            'sort_by'      => ['nullable', 'in:created_at,price'],
            'sort_dir'     => ['nullable', 'in:asc,desc'],
            'mode'         => ['nullable', 'in:my,browse'],
        ];
    }

    public function filters(): array
    {
        $data = $this->validated();

        return array_filter($data, fn($value) => $value !== null && $value !== '');
    }
}
