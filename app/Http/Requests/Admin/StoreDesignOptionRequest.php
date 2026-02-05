<?php

namespace App\Http\Requests\Admin;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use App\Enums\DesignOptionsType;

class StoreDesignOptionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth('admin')->check();
    }

    public function rules(): array
    {
        return [
            'name'      => ['required', 'array'],
            'name.en'   => ['required', 'string', 'max:255'],
            'name.ar'   => ['nullable', 'string', 'max:255'],

            'type'      => ['required', Rule::in(array_column(DesignOptionsType::cases(), 'value'))],

            'is_active' => ['sometimes', 'boolean'],
        ];
    }
}
