<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    use HasFactory;
    public $table = 'conversations';
    protected $guarded = [];
    protected $casts = [
        'patient_id' => 'integer',
        'doctor_id' => 'integer',
        'is_read' => 'boolean',
        'last_message_at_utc' => 'datetime',
    ];
    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function conversationFavorites()
    {
        return $this->hasMany(ConversationFavorite::class);
    }
}
