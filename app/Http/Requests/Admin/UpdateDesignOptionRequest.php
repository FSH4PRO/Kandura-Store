<?php

namespace App\Http\Requests\Admin;

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

            'type'      => ['sometimes', 'in:color,dome_type,fabric_type,sleeve_type'],

            'is_active' => ['sometimes', 'boolean'],
        ];
    }
}
