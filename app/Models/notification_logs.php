<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification_logs extends Model
{
        use HasFactory;
    protected $fillable = [
        'user_id', 'type', 'channel', 'title', 'body',
        'data', 'provider_message_id', 'sent_at_utc', 'read_at_utc',
    ];

    protected $casts = [
        'data' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
