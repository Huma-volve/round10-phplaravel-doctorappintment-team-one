<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medical_records extends Model
{
        use HasFactory;
    protected $fillable = [
        'patient_id', 'doctor_id', 'clinic_id', 'appointment_id',
        'diagnosis', 'notes', 'recommendations',
        'follow_up_required', 'follow_up_after_days',
    ];

    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function clinic()
    {
        return $this->belongsTo(Clinics::class);
    }

    public function appointment()
    {
        return $this->belongsTo(Bookings::class, 'appointment_id');
    }
}
