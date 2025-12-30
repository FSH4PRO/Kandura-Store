<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ListUsersRequest extends FormRequest
{
    public function authorize(): bool
    {
        // التحكم مين مسموح يوصل لهالراوت
        // عم نعمله بالميدل وير (check.role / check.permission)
        return true;
    }

    public function rules(): array
    {
        return [
            'search'   => ['nullable', 'string', 'max:255'],
            'status'   => ['nullable', 'in:active,inactive'],
            'sort_by'  => ['nullable', 'in:id,name,created_at'],
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
