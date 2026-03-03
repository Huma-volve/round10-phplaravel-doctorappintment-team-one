<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CancellationPolicy extends Model
{
        use HasFactory;

    public $table = 'cancellation_policies';
    protected $guarded = [];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }
}
