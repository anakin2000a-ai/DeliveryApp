<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'first_name' => ['sometimes', 'required', 'string', 'max:255'],
            'last_name' => ['sometimes', 'required', 'string', 'max:255'],
            'phone' => ['sometimes', 'required', 'string', 'max:30'],
            'birthday' => [
                'sometimes',
                'required',
                'date',
                'before_or_equal:' . now()->subYears(18)->format('Y-m-d'),
            ],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],

            'addresses' => ['sometimes', 'array', 'min:1'],
            'addresses.*.id' => ['nullable', 'exists:customer_addresses,id'],
            'addresses.*.label' => ['nullable', 'string', 'max:100'],
            'addresses.*.full_address' => ['required_with:addresses', 'string'],
            'addresses.*.city' => ['required_with:addresses', 'string', 'max:150'],
            'addresses.*.postal_code' => ['required_with:addresses', 'string', 'max:20'],
            'addresses.*.country' => ['nullable', 'string', 'max:100'],
            'addresses.*.latitude' => ['required_with:addresses', 'numeric', 'between:-90,90'],
            'addresses.*.longitude' => ['required_with:addresses', 'numeric', 'between:-180,180'],
            'addresses.*.is_default' => ['nullable', 'boolean'],
        ];
    }
}