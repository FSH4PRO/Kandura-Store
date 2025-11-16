<?php

namespace App\Http\Requests\Address;

use Illuminate\Foundation\Http\FormRequest;

class StoreAddressRequest extends FormRequest
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
        'street'       => 'required|string|max:255',
        'city'         => 'required|string|max:100',
        'area'         => 'required|string|max:100',
        'postal_code'  => 'required|string|max:20',
        'country'      => 'required|string|max:100',
        'address_title'=> 'required|string|max:255',
        'building'     => 'nullable|string|max:100',
        'apartment'    => 'nullable|string|max:100',
        'phone'        => 'required|string|max:20',
        'longitude'    => 'nullable|numeric|between:-180,180',
        'latitude'     => 'nullable|numeric|between:-90,90',
        'is_default'   => 'sometimes|boolean',
    ];
}

}
