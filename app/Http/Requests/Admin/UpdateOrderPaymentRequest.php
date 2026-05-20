<?php
namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderPaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role === 'admin';
    }

    public function rules(): array
    {
        return [
            'payment_status' => ['required','in:paid,not_paid'],
            'paid_amount' => ['required_if:payment_status,paid','numeric','min:0'],
            'note' => ['nullable','string'],
        ];
    }
}