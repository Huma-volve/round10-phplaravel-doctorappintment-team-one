<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
        use HasFactory;
        public $table = 'payments';
    protected $guarded = [];

    protected $casts = [
        'meta' => 'array',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
