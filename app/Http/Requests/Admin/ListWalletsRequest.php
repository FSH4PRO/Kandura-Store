<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ListWalletsRequest extends FormRequest
{
  /**
   * Determine if the user is authorized to make this request.
   */
  public function authorize(): bool
  {
    return auth('admin')->check() && auth('admin')->user()->can('wallets.view');
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
   */
  public function rules(): array
  {
    return [
      'search' => ['nullable', 'string', 'max:255'],
      'is_active' => ['nullable', 'boolean'],
      'balance_min' => ['nullable', 'numeric', 'min:0'],
      'balance_max' => ['nullable', 'numeric', 'min:0'],
      'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
    ];
  }
}
