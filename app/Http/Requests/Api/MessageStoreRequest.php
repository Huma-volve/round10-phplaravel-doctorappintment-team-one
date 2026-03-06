<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class MessageStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'conversation_id' => ['required', 'integer', 'exists:conversations,id'],
            'body' => ['required', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'conversation_id.required' => 'Conversation ID is required.',
            'conversation_id.integer' => 'Conversation ID must be an integer.',
            'conversation_id.exists' => 'Conversation does not exist.',
            'body.required' => 'Message body is required.',
            'body.string' => 'Message body must be a string.',
        ];
    }
}
