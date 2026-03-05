<?php

namespace App\Models;

use App\Exceptions\LocationNotFoundException;
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
            throw new LocationNotFoundException();
        }

        $radius = $location['radius'];
        
        $lat = $location['lat'];
        $lng = $location['lng'];

        return $query->selectRaw("
            (6371 * acos(
                cos(radians(?)) *
                cos(radians(clinics.lat)) *
                cos(radians(clinics.lng) - radians(?)) +
                sin(radians(?)) *
                sin(radians(clinics.lat))
            )) AS distance
        ", [$lat, $lng, $lat])
        ->having('distance', '<=', $radius)
        ->orderBy('distance');
    }
}
