<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Payment;
class Booking extends Model
{
    use HasFactory;

    protected $guarded = [];
    
    public $table = 'bookings';
    
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

    public function payment()
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
    public function clinic()
{
    return $this->belongsTo(Clinic::class,'clinic_id');
}
}
