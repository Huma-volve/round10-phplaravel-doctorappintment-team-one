<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DoctorSpecialties extends Model
{
    protected $table = 'doctor_specialties';
    protected $guarded = [];

    public function doctor(){
        return $this->belongsToMany(Doctor::class);
    }
}
