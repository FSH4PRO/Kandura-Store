<?php

namespace App\Http\Requests\Order;

use Illuminate\Validation\Rule;
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
            'items.*.size_id'   => ['nullable', 'integer', 'exists:sizes,id'],
            'items.*.quantity'  => ['required', 'integer', 'min:1'],

            'items.*.options'   => ['nullable', 'array'],
            //option only if they are exists in the design options table and they are related to the design
            'items.*.options.*.option_id' => [
                'required',
                'integer',
                Rule::exists('design_options', 'id')->where(function ($query) {
                    $query->where('id', request()->input('items.*.design_id'));
                }),
            ],
            'items.*.options.*.value'     => ['nullable'],

            'address_id' => [
                'nullable',
                'integer',
                Rule::exists('addresses', 'id')->where(
                    'customer_id',
                    auth('customer')->user()?->id
                ),
            ],

        ];
    }
}
