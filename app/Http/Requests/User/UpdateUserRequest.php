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
        $userId = $this->route('id'); // من /api/users/{id}

        return [
            'name'          => 'sometimes|string|max:255',
            'email'         => 'sometimes|string|email|unique:users,email,' . $userId,
            'phone'         => 'sometimes|nullable|string|max:20',
            'password'      => 'sometimes|nullable|string|min:8|confirmed',
            // الصورة
            'profile_image' => 'sometimes|nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ];
    }
}
