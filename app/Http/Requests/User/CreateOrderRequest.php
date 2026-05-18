<?php
namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class CreateOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role === 'customer';
    }

    public function rules(): array
    {
        return [
            'restaurant_id' => ['required', 'exists:restaurants,id'],

            // Saved address OR manual address
            'customer_address_id' => ['nullable', 'exists:customer_addresses,id'],

            'service_area_id' => [
                'required_without:customer_address_id',
                'nullable',
                'exists:service_areas,id',
            ],

            'delivery_address' => [
                'required_without:customer_address_id',
                'nullable',
                'string',
            ],

            'delivery_latitude' => [
                'required_without:customer_address_id',
                'nullable',
                'numeric',
                'between:-90,90',
            ],

            'delivery_longitude' => [
                'required_without:customer_address_id',
                'nullable',
                'numeric',
                'between:-180,180',
            ],

            'customer_note' => ['nullable', 'string'],

            'items' => ['required', 'array', 'min:1'],
            'items.*.menu_item_id' => ['required', 'exists:menu_items,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'items.*.customer_note' => ['nullable', 'string'],
        ];
    }
}