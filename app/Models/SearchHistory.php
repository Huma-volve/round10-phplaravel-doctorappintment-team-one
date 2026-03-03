<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SearchHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'query',
        'specialty',
        'lat',
        'lng',
        'doctor_name',
    ];

    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }
}
