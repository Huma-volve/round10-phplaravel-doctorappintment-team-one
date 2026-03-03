<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class SearchRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'nullable|string',
            'specialty_id' => 'nullable|integer|exists:specialties,id',
        ];
    }
    public function messages(): array
    {
        return [
            'name.string' => 'Name must be a string.',
            'specialty_id.integer' => 'Specialty not found.',
        ];
    }
}
