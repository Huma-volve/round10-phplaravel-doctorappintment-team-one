<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Clinic extends Model
{
    use HasFactory;

    protected $guarded = [];
    
    public $table = 'clinics';

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

    public function scopeNearby($query, $location){
        if ( !isset($location['lat']) || !isset($location['lng']) ) {
            throw new Exception("Location not found");
        }

        return $query->selectRaw("*, ( 6371 * acos( cos( radians(?) ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians(?) ) + sin( radians(?) ) * sin( radians( latitude ) ) ) ) AS distance", [$location['lat'], $location['lng'], $location['lat']])
            ->orderBy('distance');
    }
}
