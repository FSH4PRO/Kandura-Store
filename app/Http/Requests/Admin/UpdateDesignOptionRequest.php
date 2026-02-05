<?php

namespace App\Http\Requests\Admin;

use Illuminate\Validation\Rule;
use App\Enums\DesignOptionsType;
use Illuminate\Foundation\Http\FormRequest;

class UpdateDesignOptionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth('admin')->check();
    }

    public function rules(): array
    {
        return [
            'name'      => ['sometimes', 'array'],
            'name.en'   => ['sometimes', 'string', 'max:255'],
            'name.ar'   => ['sometimes', 'string', 'max:255'],

            'type'      => ['sometimes', Rule::in(array_column(DesignOptionsType::cases(), 'value'))],

            'is_active' => ['sometimes', 'boolean'],
        ];
    }
}
