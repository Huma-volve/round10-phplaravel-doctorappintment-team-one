<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
        use HasFactory;
   protected $fillable = [
        'user_id', 'specialties', 'license_number', 'bio',
        'years_of_experience', 'verification_status', 'verification_notes',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function clinics()
    {
        return $this->hasMany(Clinics::class);
    }

    public function cancellationPolicy()
    {
        return $this->hasOne(Cancellation_policies::class);
    }

    public function timeSlots()
    {
        return $this->hasMany(Doctor_time_slots::class);
    }

    public function bookings()
    {
        return $this->hasMany(Bookings::class);
    }

    public function reviews()
    {
        return $this->hasMany(Reviews::class);
    }

    public function favorites()
    {
        return $this->hasMany(Favorites::class);
    }

    public function conversations()
    {
        return $this->hasMany(Conversations::class);
    }

    public function medicalRecords()
    {
        return $this->hasMany(Medical_records::class);
    }
}
