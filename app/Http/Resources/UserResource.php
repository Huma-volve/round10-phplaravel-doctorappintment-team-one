<?php

namespace App\Http\Resources;

use App\Http\Resources\Doctor\DoctorDetailsResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'email_verified_at' => $this->email_verified_at,
            'phone' => $this->phone,
            'birthdate' => $this->birthdate,
            
            // Account Information
            'role' => $this->role,
            'status' => $this->status,
            'photo_url' => $this->photo_url ? asset('storage/' . $this->photo_url) : null,
            
            // Account Status
            'is_active' => $this->status === 'active',
            'is_verified' => $this->email_verified_at !== null,
            
            // Doctor Details (if user is a doctor)
            'doctor' => $this->when($this->role === 'doctor' && $this->relationLoaded('doctor'), function () {
                return $this->doctor ? new DoctorDetailsResource($this->doctor) : null;
            }),
            
            'social_id' => $this->whenNotNull($this->social_id),
            'social_type' => $this->whenNotNull($this->social_type),
            
            
            'total_bookings' => $this->whenLoaded('bookings', fn() => $this->bookings->count()),
            'total_reviews' => $this->whenLoaded('reviews', fn() => $this->reviews->count()),
            'total_favorites' => $this->whenLoaded('favorites', fn() => $this->favorites->count()),
            
           
            'recent_bookings' => $this->whenLoaded('bookings', function () {
                return $this->bookings()
                    ->orderBy('created_at', 'desc')
                    ->take(5)
                    ->with(['doctor', 'doctor.user'])
                    ->get()
                    ->map(fn($booking) => [
                        'id' => $booking->id,
                        'status' => $booking->status,
                        'starts_at' => $booking->starts_at_utc,
                        'ends_at' => $booking->ends_at_utc,
                        'doctor_name' => $booking->doctor->user->name,
                        'doctor_id' => $booking->doctor->id,
                    ]);
            }),
            
        
            // Timestamps
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}