<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OtpCode extends Model
{

    use HasFactory;
protected $fillable = [
        'user_id', 'channel', 'destination', 'purpose', 'code_hash',
        'expires_at_utc', 'attempts', 'max_attempts', 'send_count',
        'last_sent_at_utc', 'consumed_at_utc',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
