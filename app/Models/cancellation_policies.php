<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class cancellation_policies extends Model
{
        use HasFactory;
    protected $fillable = [
        'doctor_id', 'allowed_cancel', 'allowed_cancel_before_minutes',
        'fee_type', 'fee_value', 'allow_reschedule', 'allowed_reschedule_before_minutes',
    ];

    public function doctor()
    {
        return $this->belongsTo(doctor::class);
    }
}
