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
    public function index(Request $request): JsonResponse
    {
        $userId = $request->user()->id;

        // جلب المحادثات بناءً على المريض أو الطبيب، مع ترتيبها حسب آخر رسالة
        $conversations = Conversation::query()
            ->where(function ($query) use ($userId) {
                $query->where('patient_id', $userId)
                    ->orWhere('doctor_id', $userId);
            })
            ->with(['patient:id,name', 'doctor:id,name'])  // تأكد من تحميل الاسم بجانب الـ ID
            ->orderByDesc('last_message_at_utc')  // ترتيب المحادثات بناءً على تاريخ آخر رسالة
            ->get()
            ->map(function ($conversation) use ($userId) {
                // تحديد المستخدم الآخر في المحادثة (المريض أو الطبيب)
                $otherUser = $conversation->patient_id == $userId
                    ? $conversation->doctor
                    : $conversation->patient;

                // جلب آخر رسالة في المحادثة
                $lastMessage = Message::query()
                    ->where('conversation_id', $conversation->id)
                    ->latest('id')  // ترتيب الرسائل حسب تاريخ آخر رسالة
                    ->first();

                // عدد الرسائل الغير مقروءة
                $unreadCount = Message::query()
                    ->where('conversation_id', $conversation->id)
                    ->whereNull('read_at_utc')
                    ->where('sender_user_id', '<>', $userId)
                    ->count();

                // إرجاع البيانات المطلوبة للمحادثة
                return [
                    'id' => $conversation->id,
                    'user_name' => $otherUser->name ?? 'Unknown', // إظهار الاسم
                    'last_message' => $lastMessage->body ?? 'No messages',  // آخر رسالة في المحادثة
                    'unread_count' => $unreadCount,  // عدد الرسائل الغير مقروءة
                    'last_message_at' => $conversation->last_message_at_utc, // آخر وقت للرسالة
                ];
            });

        // إذا كانت المحادثات فارغة، إرجاع رسالة
        if ($conversations->isEmpty()) {
            return response()->json(['message' => 'لا توجد محادثات حالياً.'], 404);
        }

        // إرجاع المحادثات مع ترتيبها
        return response()->json($conversations);
    }

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
        // تغيير حالة الرسائل الغير مقروءة إلى مقروءة
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
