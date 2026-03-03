<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
        use HasFactory;
    protected $fillable = [
        'conversation_id', 'sender_user_id', 'type', 'body',
        'media_url', 'media_size_bytes', 'media_mime', 'sent_at_utc', 'read_at_utc',
    ];

    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }

    public function sender()
    {
        return $this->belongsTo(User::class);
    }
}
