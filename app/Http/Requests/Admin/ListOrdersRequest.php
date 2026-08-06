<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Enums\OrderStatus;

class ListOrdersRequest extends FormRequest
{
  /**
   * Determine if the user is authorized to make this request.
   */
  public function authorize(): bool
  {
    return auth('admin')->check() && auth('admin')->user()->can('orders.view');
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
   */
  public function rules(): array
  {
    return [
      'search'     => ['nullable', 'string', 'max:255'],
      'status'     => ['nullable', Rule::in(array_column(OrderStatus::cases(), 'value'))],
      'total_min'  => ['nullable', 'numeric', 'min:0'],
      'total_max'  => ['nullable', 'numeric', 'min:0'],
      'per_page'   => ['nullable', 'integer', 'min:1', 'max:100'],
      'sort_by'    => ['nullable', 'in:id,created_at,total'],
      'sort_dir'   => ['nullable', 'in:asc,desc'],
    ];
  }
}
