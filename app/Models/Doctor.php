<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    use HasFactory;
    public $table = 'doctors';

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function specialties(){
        return $this->hasMany(DoctorSpecialties::class);
    }
    public function clinics()
    {
        return $this->hasMany(Clinic::class);
    }

    public function cancellationPolicy()
    {
        return $this->hasOne(CancellationPolicy::class);
    }

    public function timeSlots()
    {
        return $this->hasMany(DoctorTimeSlot::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }


    public function conversations()
    {
        return $this->hasMany(Conversation::class);
    }

    public function medicalRecords()
    {
        return $this->hasMany(MedicalRecord::class);
    }

    public function favorite(){
        return $this->hasMany(Favorite::class);
    }
}