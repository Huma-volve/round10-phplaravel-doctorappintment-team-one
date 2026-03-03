<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
<<<<<<< HEAD
    use HasFactory;
    
    protected $fillable = [
        'booking_id', 
        'provider', 
        'provider_payment_id', 
        'provider_customer_id',
        'status', 
        'amount_cents', 
        'refunded_cents', 
        'currency', 
        'meta',
    ];
=======
        use HasFactory;
        public $table = 'payments';
    protected $guarded = [];
>>>>>>> 9540379c13524afa56bec0bfe002ffa727584cc4

    protected $casts = [
        'meta' => 'array',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
