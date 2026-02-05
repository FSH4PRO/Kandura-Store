<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\CouponType;

class StoreCouponRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth('admin')->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'code' => 'required|string|unique:coupons,code|max:50|regex:/^[A-Z0-9_-]+$/i',
            'type' => 'required|in:' . implode(',', array_column(CouponType::cases(), 'value')),
            'amount' => 'required|numeric|min:0',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after:starts_at',
            'max_uses' => 'nullable|integer|min:1',
            'is_active' => 'boolean',
            'allowed_customers' => 'nullable|array|min:1',
            'allowed_customers.*' => 'exists:customers,id',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'code' => __('Coupon Code'),
            'type' => __('Discount Type'),
            'amount' => __('Discount Amount'),
            'starts_at' => __('Start Date'),
            'ends_at' => __('End Date'),
            'max_uses' => __('Maximum Uses'),
            'is_active' => __('Active Status'),
            'allowed_customers' => __('Allowed Customers'),
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'code.regex' => __('Coupon code can only contain letters, numbers, hyphens, and underscores.'),
            'ends_at.after' => __('End date must be after start date.'),
            'starts_at.after' => __('Start date must be in the future.'),
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_active' => $this->boolean('is_active', false),
        ]);
    }
}
