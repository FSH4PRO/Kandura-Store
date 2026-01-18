<?php

namespace App\Http\Requests\dashboard;

use Illuminate\Foundation\Http\FormRequest;

class TransactionIndexRequest extends FormRequest
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
      'type' => 'nullable|string|in:credit,debit',
      'date_from' => 'nullable|date|before_or_equal:date_to',
      'date_to' => 'nullable|date|after_or_equal:date_from',
      'search' => 'nullable|string|max:255',
      'page' => 'nullable|integer|min:1',
    ];
  }

  /**
   * Get custom messages for validator errors.
   */
  public function messages(): array
  {
    return [
      'type.in' => __('dashboard.transactions.validation.type_invalid'),
      'status.in' => __('dashboard.transactions.validation.status_invalid'),
      'date_from.before_or_equal' => __('dashboard.transactions.validation.date_from_invalid'),
      'date_to.after_or_equal' => __('dashboard.transactions.validation.date_to_invalid'),
      'search.max' => __('dashboard.transactions.validation.search_too_long'),
    ];
  }
}
