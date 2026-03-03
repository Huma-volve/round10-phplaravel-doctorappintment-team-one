<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DoctorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "specialty" => $this->specialty,
            "phone" => $this->phone,
            "email" => $this->email,
            "address" => $this->address,
            "is_favorite" => $this->favorites->exits(),
            "created_at" => $this->created_at,
        ];
    }
}
