<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConversationArchive extends Model
{
    protected $table = 'archived_conversations';

    protected $fillable = ['user_id', 'conversation_id'];

    // لأنك في المايجريشن مستخدم timestamps()
    public $timestamps = true;

    public function conversation()
    {
        return $this->belongsTo(Conversation::class, 'conversation_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function scopeArchivedByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }
}
