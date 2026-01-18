<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class TopupWalletRequest extends FormRequest
{
  /**
   * Determine if the user is authorized to make this request.
   */
  public function authorize(): bool
  {
    return auth('admin')->check() && auth('admin')->user()->can('wallets.topup');
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
   */
  public function rules(): array
  {
    return [
      'amount' => ['required', 'numeric', 'min:0.01'],
      'reference' => ['nullable', 'string', 'max:255'],
      'note' => ['nullable', 'string', 'max:500'],
    ];
  }
}
