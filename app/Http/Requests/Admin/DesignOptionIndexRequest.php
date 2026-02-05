<?php

namespace App\Http\Requests\Admin;

use Illuminate\Validation\Rule;
use App\Enums\DesignOptionsType;
use Illuminate\Foundation\Http\FormRequest;

class DesignOptionIndexRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth('admin')->check();
    }

    public function rules(): array
    {
        return [
            'search'    => ['nullable', 'string', 'max:255'],
            'type'      => ['nullable', Rule::in(array_column(DesignOptionsType::cases(), 'value'))],
            'is_active' => ['nullable', 'in:0,1'],
            'per_page'  => ['nullable', 'integer', 'min:1', 'max:100'],
            'sort_by'   => ['nullable', 'in:id,name,created_at'],
            'sort_dir'  => ['nullable', 'in:asc,desc'],
        ];
    }
}
