<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ReviewStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'booking_id' => ['required' ,
                    Rule::unique('reviews', 'booking_id')
                        ->where(fn ($q) => $q->where('doctor_id', $this->input('doctor_id'))),
               ], 
               'doctor_id' => 'required' ,
               'rating'     => 'required|integer|between:1,5'
        ];
    }
}
