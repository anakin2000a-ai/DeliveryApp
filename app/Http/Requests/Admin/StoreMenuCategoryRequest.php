<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreMenuCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role === 'admin';
    }

    public function rules(): array
    {
        return [
            'restaurant_id' => [
                'required',
                'integer',
                'exists:restaurants,id',
            ],

            'name' => [
                'required',
                'string',
                'max:150',
                Rule::unique('menu_categories', 'name')
                    ->where('restaurant_id', $this->input('restaurant_id')),
            ],

            'sort_order' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'is_active' => [
                'nullable',
                'boolean',
            ],

            'items' => [
                'required',
                'array',
                'min:1',
            ],

            'items.*.name' => [
                'required',
                'string',
                'max:150',
                'distinct',
                Rule::unique('menu_items', 'name')
                    ->where('restaurant_id', $this->input('restaurant_id')),
            ],

            'items.*.description' => [
                'nullable',
                'string',
            ],

            'items.*.price' => [
                'required',
                'numeric',
                'min:0',
                'max:99999999.99',
            ],

            'items.*.status' => [
                'nullable',
                'string',
                'in:active,inactive',
            ],

            'items.*.images' => [
                'nullable',
                'array',
            ],

            'items.*.images.*' => [
                'file',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:4096',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'restaurant_id.required' => 'Restaurant is required.',
            'restaurant_id.exists' => 'Selected restaurant does not exist.',

            'name.required' => 'Category name is required.',
            'name.unique' => 'This category name already exists for this restaurant.',

            'items.required' => 'At least one menu item is required.',
            'items.array' => 'Menu items must be an array.',
            'items.min' => 'At least one menu item is required.',

            'items.*.name.required' => 'Menu item name is required.',
            'items.*.name.unique' => 'This menu item name already exists for this restaurant.',
            'items.*.name.distinct' => 'Menu item names must be unique in the same request.',

            'items.*.price.required' => 'Menu item price is required.',
            'items.*.price.numeric' => 'Menu item price must be a number.',

            'items.*.images.*.image' => 'Each uploaded file must be an image.',
            'items.*.images.*.mimes' => 'Images must be jpg, jpeg, png, or webp.',
            'items.*.images.*.max' => 'Each image must not be larger than 4MB.',
        ];
    }
}