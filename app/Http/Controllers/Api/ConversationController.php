<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\ConversationFavorite;
use App\Models\Message;
use Illuminate\Http\Request;

class ConversationController extends Controller
{
    public function unreadCount(Request $request, $conversationId)
    {
        $userId = $request->user_id;

        $count = Message::where('conversation_id', $conversationId)
            ->where('sender_user_id', '!=', $userId)
            ->whereNull('read_at_utc')
            ->count();

        return response()->json([
            'unread_count' => $count
        ]);
    }
    public function markAsRead(Request $request, $conversationId)
    {
        $userId = $request->user_id;

        Message::where('conversation_id', $conversationId)
            ->where('sender_user_id', '!=', $userId)
            ->whereNull('read_at_utc')
            ->update([
                'read_at_utc' => now()
            ]);

        return response()->json([
            'status' => 'messages marked as read'
        ]);
    }
    public function toggleFavorite(Request $request, $conversation_id)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $fav = ConversationFavorite::where('user_id', $request->user_id)
            ->where('conversation_id', $conversation_id)
            ->first();

        if ($fav) {
            $fav->delete();

            return response()->json([
                'conversation_id' => (int) $conversation_id,
                'user_id' => (int) $request->user_id,
                'is_favorite' => false,
            ]);
        }

        ConversationFavorite::create([
            'user_id' => $request->user_id,
            'conversation_id' => $conversation_id,
            'created_at' => now(),
        ]);

        return response()->json([
            'conversation_id' => (int) $conversation_id,
            'user_id' => (int) $request->user_id,
            'is_favorite' => true,
        ], 201);
    }
    public function archiveConversation(Request $request, $conversation_id)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $arch = \App\Models\ConversationArchive::where('user_id', $request->user_id)
            ->where('conversation_id', $conversation_id)
            ->first();

        if ($arch) {
            $arch->delete();
            return response()->json([
                'conversation_id' => (int)$conversation_id,
                'user_id' => (int)$request->user_id,
                'is_archived' => false,
            ]);
        }

        \App\Models\ConversationArchive::create([
            'user_id' => $request->user_id,
            'conversation_id' => $conversation_id,
            'created_at' => now(),
        ]);

        return response()->json([
            'conversation_id' => (int)$conversation_id,
            'user_id' => (int)$request->user_id,
            'is_archived' => true,
        ], 201);
    }
}
