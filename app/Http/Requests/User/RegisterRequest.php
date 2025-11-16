<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
  /**
   * Determine if the user is authorized to make this request.
   */
  public function authorize(): bool
  {
    return true;
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
   */
  public function rules(): array
  {
    return [
      'name.en' => 'required|string|max:255',
      'name.ar' => 'required|string|max:255',
      'email' => 'required|string|email|unique:users,email',
      'phone' => 'nullable|string|max:20',
      'password' => 'required|string|min:8|confirmed',
    ];
  }
}
