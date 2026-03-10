<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorTimeSlot extends Model
{

    use HasFactory;

    public $table = 'doctor_time_slots';

    protected $guarded = [];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }

    public function bookings()
    {
        return $this->hasOne(Booking::class, 'time_slot_id');
    }
}
