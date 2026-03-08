<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\ConversationArchive;
use App\Models\ConversationFavorite;
use App\Models\Message;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ConversationController extends Controller
{

    public function unreadCount(Request $request, int $conversationId): JsonResponse
    {
        $userId = $request->user()->id;

        if (!$this->ensureUserBelongsToConversation($userId, $conversationId)) {
            return $this->forbiddenResponse();
        }
        $unreadCount = Message::query()
            ->where('conversation_id', $conversationId)
            ->whereNull('read_at_utc')
            ->where('sender_user_id', '<>', $userId)
            ->count();

        return response()->json([
            'unread_count' => $unreadCount,
        ]);
    }

    public function markAsRead(Request $request, int $conversationId): JsonResponse
    {
        $userId = $request->user()->id;

        if (!$this->ensureUserBelongsToConversation($userId, $conversationId)) {
            return $this->forbiddenResponse();
        }
        Message::where('conversation_id', $conversationId)
            ->where('sender_user_id', '!=', $userId)
            ->whereNull('read_at_utc')
            ->update([
                'read_at_utc' => now(),
            ]);

        return response()->json([
            'status' => 'messages marked as read',
        ]);
    }

    public function toggleFavorite(Request $request, int $conversationId): JsonResponse
    {
        $userId = $request->user()->id;

        if (!$this->ensureUserBelongsToConversation($userId, $conversationId)) {
            return $this->forbiddenResponse();
        }
        $favorite = ConversationFavorite::where('user_id', $userId)
            ->where('conversation_id', $conversationId)
            ->first();

        if ($favorite) {
            $favorite->delete();

            return response()->json([
                'is_favorite' => false,
            ]);
        }

        ConversationFavorite::create([
            'user_id' => $userId,
            'conversation_id' => $conversationId,
            'created_at' => now(),
        ]);

        return response()->json([
            'is_favorite' => true,
        ], 201);
    }

    public function archiveConversation(Request $request, int $conversationId): JsonResponse
    {
        $userId = $request->user()->id;

        if (!$this->ensureUserBelongsToConversation($userId, $conversationId)) {
            return $this->forbiddenResponse();
        }
        $archive = ConversationArchive::where('user_id', $userId)
            ->where('conversation_id', $conversationId)
            ->first();

        if ($archive) {
            $archive->delete();

            return response()->json([
                'is_archived' => false,
            ]);
        }

        ConversationArchive::create([
            'user_id' => $userId,
            'conversation_id' => $conversationId,
            'created_at' => now(),
        ]);

        return response()->json([
            'is_archived' => true,
        ], 201);
    }

    private function ensureUserBelongsToConversation(int $userId, int $conversationId): bool
    {
        return Conversation::query()
            ->whereKey($conversationId)
            ->where(function ($query) use ($userId) {
                $query->where('patient_id', $userId)
                    ->orWhere('doctor_id', $userId);
            })
            ->exists();
    }

    private function forbiddenResponse()
    {
        return response()->json([
            'status' => false,
            'message' => 'You are not allowed to access this conversation.'
        ], 403);
    }
}
