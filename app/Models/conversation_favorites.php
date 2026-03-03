<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conversation_favorites extends Model
{
        use HasFactory;
    public $timestamps = false;

    protected $fillable = ['user_id', 'conversation_id', 'created_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function conversation()
    {
        return $this->belongsTo(Conversations::class);
    }
}
