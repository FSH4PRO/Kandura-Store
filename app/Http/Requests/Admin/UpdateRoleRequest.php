<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRoleRequest extends FormRequest
{
  /**
   * Determine if the user is authorized to make this request.
   */
  public function authorize(): bool
  {
    $admin = auth('admin')->user();

    if (! $admin) {
      return false;
    }

    return $admin->hasRole('super_admin')
      || $admin->can('system.manage_roles');
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
   */
  public function rules(): array
  {
    return [
      'name' => [
        'required',
        'string',
        'max:255',
        Rule::unique('roles', 'name')
          ->where('guard_name', 'admin')
          ->ignore($this->route('role')->id),
      ],
      'permissions'   => ['nullable', 'array'],
      'permissions.*' => ['string', 'exists:permissions,name'],
    ];
  }
}
