<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ListUsersRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // سيتم التحقق عبر Policies لاحقًا
    }

    public function rules(): array
    {
        return [

           
            'search'   => ['nullable', 'string', 'max:255'],

           
            'status'   => ['nullable', 'in:active,inactive'],

            
            'role'     => ['nullable', 'string', 'max:50'],

            
            'sort_by'  => ['nullable', 'in:id,name,email,created_at'],
            'sort_dir' => ['nullable', 'in:asc,desc'],

           
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
        ];
    }

    public function messages(): array
    {
        return [
            'status.in'   => 'Invalid status filter.',
            'sort_by.in'  => 'Invalid sort field.',
            'sort_dir.in' => 'Sort direction must be asc or desc.',
        ];
    }
}
