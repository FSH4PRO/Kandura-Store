<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\User;

class StoreAdminRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('createAdmin', User::class) ?? false;
    }

    public function rules(): array
    {
        return[
            'name.en'    => 'required|string|max:255',
            'name.ar'    => 'required|string|max:255',
            'email'      => 'required|email|unique:admins,email',
            'password'   => 'required|string|min:8|confirmed',
            'is_active'  => 'sometimes|boolean',
        ];
        
    }
}
