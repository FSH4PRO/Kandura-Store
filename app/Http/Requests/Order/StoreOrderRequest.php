<?php

namespace App\Http\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    public function authorize()
    {
        return auth('customer')->check();
    }

    public function rules()
    {
        return [
            'items' => ['required', 'array', 'min:1'],

            'items.*.design_id' => ['required', 'integer', 'exists:designs,id'],
            'items.*.size_id'   => ['required', 'integer', 'exists:sizes,id'],
            'items.*.quantity'  => ['required', 'integer', 'min:1'],

            'items.*.options'   => ['nullable', 'array'],
            'items.*.options.*.option_id' => ['required_with:items.*.options', 'integer', 'exists:design_options,id'],
            'items.*.options.*.value'     => ['nullable', 'string'],
        ];
    }
}
