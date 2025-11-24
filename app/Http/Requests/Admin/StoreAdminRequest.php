<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreAdminRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name.en'  => 'required|string|max:255',
            'name.ar'  => 'nullable|string|max:255',

            'email'    => 'required|email|unique:admins,email',
            'phone'    => 'nullable|string|max:50',

            'password' => 'required|string|min:8|confirmed',

            'is_active' => 'sometimes|boolean',

            // الرولات الإضافية
            'roles'   => 'nullable|array',
            'roles.*' => 'string|exists:roles,name',
        ];
    }

    public function messages(): array
    {
        return [
            'name_en.required' => 'English name is required.',
            'email.required'   => 'Email is required.',
            'email.email'      => 'Email must be a valid email address.',
            'email.unique'     => 'This email is already used for another admin.',
            'password.required' => 'Password is required.',
            'password.confirmed' => 'Password confirmation does not match.',
            'roles.array'      => 'Roles must be an array of role names.',
            'roles.*.exists'   => 'One of the selected roles does not exist.',
        ];
    }
}
