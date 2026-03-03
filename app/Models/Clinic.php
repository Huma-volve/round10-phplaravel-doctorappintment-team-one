<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Clinic extends Model
{
    use HasFactory;

    protected $fillable = [
        'doctor_id', 
        'name', 
        'address', 
        'lat', 
        'lng',
        'session_duration_minutes', 
        'session_price_cents', 
        'currency',
    ];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function timeSlots()
    {
        return $this->hasMany(DoctorTimeSlot::class);
    }

    public function medicalRecords()
    {
        return $this->hasMany(MedicalRecord::class);
    }
}
