<?php

namespace App\Http\Resources\Doctor;

use App\Http\Enums\DoctorVerificationStatus;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DoctorDetailsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $userDoctor = $this->user;
        $clinic = $this->clinics->first();

        return [
            "id" => $this->id,
            "name" => $userDoctor->name,
            "profile_image" => $this->profile_image,
            "specialties" => $this->specialties->first()->name ?? null,
            "address" => $clinic->address ?? null,
            "bio" => $this->bio ?? null,
            "year_of_experience" => $this->year_of_experience ?? 0,
            "patient_count" => $this->bookings_count,
            
            "pricing" => [
                "base_price" => $clinic->session_price_cents / 100,
                "currency" => $clinic->currency,
                "per" => "session",
            ],
            "availability" => [
                "from" => Carbon::parse($this->start_time)->format('g:ia'),
                "to" => Carbon::parse($this->end_time)->format('g:ia'),
            ],
            "reviews" => [
                "average" => $this->avgRating,
                "count" => $this->reviews_count,
                "latest_reviews" => $this->reviews->take(3)->map(function ($review) {
                    return [
                        "id" => $review->id,
                        "patient_name" => $review->patient->name,
                        "patient_image" => $review->patient->profile_image,
                        "rating" => $review->rating,
                        "comment" => $review->comment,
                        "created_at" => Carbon::parse($review->created_at)->format('M d, Y')
                    ];
                })
            ], //TODO: Waiting Reviews Resource
            
            "is_verified" => $this->verification_status == DoctorVerificationStatus::Approved,
            "is_favorite" => $this->is_favorite,
        ];
    }
}
