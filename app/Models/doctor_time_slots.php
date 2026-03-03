<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor_time_slots extends Model
{

    use HasFactory;
    protected $fillable = [
        'doctor_id',
        'clinic_id',
        'starts_at_utc',
        'ends_at_utc',
        'status',
        'capacity',
    ];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function clinic()
    {
        return $this->belongsTo(Clinics::class);
    }

    public function bookings()
    {
        return $this->hasMany(Bookings::class, 'time_slot_id');
    }
}
