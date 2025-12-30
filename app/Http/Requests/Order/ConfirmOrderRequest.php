<?php

namespace App\Http\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;

class ConfirmOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth('customer')->check();
    }

    public function rules(): array
    {
        return [
            'payment_method' => ['required', 'in:cod,stripe,wallet'],
            'success_url' => ['nullable', 'url', 'max:500'],
            'cancel_url' => ['nullable', 'url', 'max:500'],
        ];
    }
}
