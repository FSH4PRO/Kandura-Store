<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreRoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        $admin = auth('admin')->user();

        if (! $admin) {
            return false;
        }

        return $admin->hasRole('super_admin')
            || $admin->can('system.manage_roles');
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:50',
                'unique:roles,name',
            ],

            'permissions'   => ['nullable', 'array'],
            'permissions.*' => ['string', 'exists:permissions,name'],
        ];
    }
}
