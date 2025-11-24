<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'nullable|array',
            'name.en' => 'nullable|string|max:255',
            'name.ar' => 'nullable|string|max:255',

            'phone' => 'nullable|string|max:20',

            'password' => 'nullable|string|min:8|confirmed',

            'profile_image' => 'nullable|image|max:2048',
        ];
    }
}
