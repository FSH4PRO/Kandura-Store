<?php

namespace App\Http\Requests\Address;

use Illuminate\Foundation\Http\FormRequest;

class AddressIndexRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // API => كل المستخدمين مسموح يستعلموا عن عناوينهم
    }

    public function rules(): array
    {
        return [
            'search'          => 'nullable|string|max:255',
            'city_id'         => 'nullable|integer|exists:cities,id',
            'has_coordinates' => 'nullable|boolean',
            'per_page'        => 'nullable|integer|min:1|max:100',

            'sort_by' => 'nullable|string|in:street,created_at,latitude,longitude',
            'sort_dir' => 'nullable|string|in:asc,desc',
        ];
    }

    public function filters(): array
    {
        // تنظيف القيم من null الفارغ
        return array_filter($this->validated(), function ($value) {
            return $value !== null && $value !== '';
        });
    }
}
