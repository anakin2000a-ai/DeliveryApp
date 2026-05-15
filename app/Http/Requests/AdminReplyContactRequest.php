<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminReplyContactRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null && $this->user()->role === 'admin';
    }

    public function rules(): array
    {
        return [
            'reply_message' => ['required', 'string', 'max:10000'],
        ];
    }
}