<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'status' => $this->status,
            'payment_method' => $this->payment_method,
            'payment_status' => $this->payment_status,
            'amount_cents' => $this->amount_cents,
            'currency' => $this->currency,
            'starts_at' => $this->starts_at_utc,
            'ends_at' => $this->ends_at_utc,

            // Patient Information
            'patient' => $this->whenLoaded('patient', function () {
                return [
                    'id' => $this->patient->id,
                    'name' => $this->patient->name,
                    'email' => $this->patient->email,
                    'phone' => $this->patient->phone,
                    'photo_url' => $this->patient->photo_url,
                ];
            }),

            // Doctor Information
            'doctor' => $this->whenLoaded('doctor', function () {
                return [
                    'id' => $this->doctor->id,
                    'user_id' => $this->doctor->user_id,
                    'license_number' => $this->doctor->license_number,
                    'bio' => $this->doctor->bio,
                    'years_of_experience' => $this->doctor->years_of_experience,
                    'verification_status' => $this->doctor->verification_status,
                    'name' => $this->doctor->user->name,
                    'email' => $this->doctor->user->email,
                    'phone' => $this->doctor->user->phone,
                    'photo_url' => $this->doctor->user->photo_url,
                ];
            }),

            // Time Slot Information
            'time_slot' => $this->whenLoaded('timeSlot', function () {
                if ($this->timeSlot) {
                    return [
                        'id' => $this->timeSlot->id,
                        'clinic_id' => $this->timeSlot->clinic_id,
                        'status' => $this->timeSlot->status,
                        'capacity' => $this->timeSlot->capacity,
                    ];
                }
                return null;
            }),

            // Review Information
            'review' => $this->whenLoaded('review', function () {
                if ($this->review) {
                    return [
                        'id' => $this->review->id,
                        'rating' => $this->review->rating,
                        'comment' => $this->review->comment,
                        'created_at' => $this->review->created_at,
                    ];
                }
                return null;
            }),

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
