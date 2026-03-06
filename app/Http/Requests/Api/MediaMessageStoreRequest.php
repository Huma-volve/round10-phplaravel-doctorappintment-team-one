<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class MediaMessageStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'conversation_id' => ['required', 'integer', 'exists:conversations,id'],
            'media' => [
                'required',
                'file',
                'mimetypes:image/jpeg,image/png,image/webp,video/mp4,video/quicktime',
                'max:51200',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'conversation_id.required' => 'Conversation ID is required.',
            'conversation_id.integer' => 'Conversation ID must be an integer.',
            'conversation_id.exists' => 'Conversation does not exist.',
            'media.required' => 'Media file is required.',
            'media.file' => 'Uploaded media must be a valid file.',
            'media.mimetypes' => 'Only jpg, png, webp, mp4, and mov files are allowed.',
            'media.max' => 'Media size must not exceed 50 MB.',
        ];
    }
}
