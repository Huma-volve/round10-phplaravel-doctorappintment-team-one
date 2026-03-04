<?php

namespace App\Http\Resources\Doctor;

use App\Http\Enums\DoctorVerificationStatus;
use App\Http\Traits\HasProfileImage;
use Carbon\Carbon;
use Illuminate\Container\Attributes\Storage;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DoctorHomeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $userDoctor = $this->user;
        
        return [
            "id" => $this->id,
            "name" => $userDoctor->name,
            "profile_image" => $this->profile_image,
            "specialties" => $this->specialties->first()->name ?? null,
            "clinic" => $this->clinics->first()->name ?? null,
            "is_verified" => $this->verification_status == DoctorVerificationStatus::Approved,
            "is_favorite" => $this->is_favorite,
            "availability" => [
                "from" => Carbon::parse($this->start_time)->format('g:ia'),
                "to" => Carbon::parse($this->end_time)->format('g:ia'),
            ],
            "avg_rating" => $this->avg_rating ?? 0,
        ];
    }
}
