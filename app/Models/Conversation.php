<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
        use HasFactory;
    protected $fillable = [
        'patient_id', 
        'doctor_id', 
        'last_message_at_utc', 
        'patient_archived', 
        'doctor_archived',
    ];

    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    public function doctor()
    {
        return $this->belongsTo(doctor::class);
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
