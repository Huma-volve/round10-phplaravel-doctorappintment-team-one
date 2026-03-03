<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReviewStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'booking_id' => 'required',
            'patient_id' => 'required',
            'doctor_id'  => 'required',
            'comment'    => 'string',
            'rating'     => 'required|integer',
        ];
    }
}
