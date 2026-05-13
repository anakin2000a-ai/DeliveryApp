<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class RegisterUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'phone' => ['required', 'string', 'max:30'],
            'birthday' => [
                'required',
                'date',
                'before_or_equal:' . now()->subYears(18)->format('Y-m-d'),
            ],
            'password' => ['required', 'string', 'min:8', 'confirmed'],

            'addresses' => ['required', 'array', 'min:1'],
            'addresses.*.label' => ['nullable', 'string', 'max:100'],
            'addresses.*.full_address' => ['required', 'string'],
            'addresses.*.city' => ['required', 'string', 'max:150'],
            'addresses.*.postal_code' => ['required', 'string', 'max:20'],
            'addresses.*.country' => ['nullable', 'string', 'max:100'],
            'addresses.*.latitude' => ['required', 'numeric', 'between:-90,90'],
            'addresses.*.longitude' => ['required', 'numeric', 'between:-180,180'],
            'addresses.*.is_default' => ['nullable', 'boolean'],
        ];
    }
}