<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
<<<<<<< HEAD
    use HasFactory;

    protected $fillable = [
        'conversation_id', 
        'sender_user_id', 
        'type', 
        'body',
        'media_url', 
        'media_size_bytes', 
        'media_mime', 
        'sent_at_utc', 
        'read_at_utc',
    ];
=======
        use HasFactory;
    public $table = 'messages';
    protected $guarded = [];
>>>>>>> 9540379c13524afa56bec0bfe002ffa727584cc4

    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }

    public function sender()
    {
        return $this->belongsTo(User::class);
    }
}
