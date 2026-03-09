<?php

namespace App\Http\Requests\Web;

use Illuminate\Foundation\Http\FormRequest;

class CreateDoctorRequest extends FormRequest
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
            'name' => 'required|string|min:3|max:50',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|min:10|unique:users',
            'bio' => 'required|string|min:10',
            'years_of_experience' => 'required|integer|min:1',
            'license_number' => 'required|string|min:10|unique:doctors',
        ];
    }
    public function messages(): array
    {
        return [
            'name.required' => 'Name is required',
            'name.min' => 'Name must be at least 3 characters',
            'name.max' => 'Name must be less than 50 characters',
            'email.required' => 'Email is required',
            'email.email' => 'Invalid email format',
            'email.unique' => 'Email already exists',
            'phone.required' => 'Phone is required',
            'phone.unique' => 'Phone already exists',
            'bio.required' => 'Bio is required',
            'years_of_experience.required' => 'Year of experience is required',
            'license_number.required' => 'License number is required',
            'license_number.unique' => 'License number already exists',
            'years_of_experience.integer' => 'Year of experience must be integer',
            'years_of_experience.min' => 'Year of experience must be 1',
        ];
    }
}
