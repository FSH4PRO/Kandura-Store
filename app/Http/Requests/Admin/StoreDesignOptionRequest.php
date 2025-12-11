<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

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

            'type'      => ['required', 'in:color,dome_type,fabric_type,sleeve_type'],

            'is_active' => ['sometimes', 'boolean'],
        ];
    }
}
