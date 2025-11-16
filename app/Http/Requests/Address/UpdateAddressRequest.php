<?php

namespace App\Http\Requests\Address;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAddressRequest extends FormRequest
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
            'street'       => 'sometimes|required|string|max:255',
            'city'         => 'sometimes|required|string|max:100',
            'area'         => 'sometimes|required|string|max:100',
            'postal_code'  => 'sometimes|required|string|max:20',
            'country'      => 'sometimes|required|string|max:100',
            'address_title' => 'sometimes|required|string|max:255',
            'building'     => 'nullable|string|max:100',
            'apartment'    => 'nullable|string|max:100',
            'phone'        => 'sometimes|required|string|max:20',
            'longitude'    => 'nullable|numeric|between:-180,180',
            'latitude'     => 'nullable|numeric|between:-90,90',
            'is_default'   => 'sometimes|boolean',
        ];
    }
}
