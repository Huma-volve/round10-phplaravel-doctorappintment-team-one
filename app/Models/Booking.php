<?php

namespace App\Models;

use Faker\Provider\ar_EG\Payment;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
        use HasFactory;
    protected $fillable = [
        'patient_id', 
        'doctor_id', 
        'time_slot_id', 
        'starts_at_utc', 
        'ends_at_utc',
        'status', 
        'payment_method', 
        'payment_status', 
        'amount_cents', 
        'currency',
    ];

    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function timeSlot()
    {
        return $this->belongsTo(DoctorTimeSlot::class, 'time_slot_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function review()
    {
        return $this->hasOne(Review::class);
    }

    public function medicalRecord()
    {
        return $this->hasOne(MedicalRecord::class, 'appointment_id');
    }
}
