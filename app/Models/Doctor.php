<?php

namespace App\Models;

use App\Http\Enums\DoctorVerificationStatus;
use App\Http\Traits\HasProfileImage;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    use HasFactory, HasProfileImage;

    public $table = 'doctors';

    protected $guarded = [];

    protected $casts = [
        'verification_status' => DoctorVerificationStatus::class
    ];

    protected $appends = [
        'profile_image',
        'avg_rating'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function specialties(){
        return $this->belongsToMany(Specialty::class, "doctor_specialty", 'doctor_id', 'specialty_id');
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

    public function favorites(){
        return $this->hasMany(Favorite::class);
    }


    public function getAvgRatingAttribute(){
        return $this->reviews()->avg('rating');
    }
}