<?php
namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ApproveOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role === 'admin';
    }

    public function rules(): array
    {
        return [
            'delivery_cost' => ['required', 'numeric', 'min:0'],
            'estimated_delivery_time' => ['nullable', 'date'],
            'note' => ['nullable', 'string'],
        ];
    }
}