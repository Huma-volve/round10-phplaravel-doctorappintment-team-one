<?php

namespace App\Http\Controllers\Api;

use App\Events\MessageSent;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\MediaMessageStoreRequest;
use App\Http\Requests\Api\MessageStoreRequest;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class MessageController extends Controller
{
    public function index(Request $request, int $conversationId): JsonResponse
    {

        $userId = $request->user()->id;

        // Ensure user is part of the conversation
        if (!$this->ensureUserBelongsToConversation($userId, $conversationId)) {
            return $this->forbiddenResponse();
        }

        $messages = Message::query()
            ->where('conversation_id', $conversationId)
            ->orderBy('id', 'desc')
            ->paginate(20);

        return response()->json($messages);
    }
    public function store(MessageStoreRequest $request, int $conversation): JsonResponse
    {
        $user = $request->user();

        // Get conversation from route parameter
        $conversationModel = Conversation::findOrFail($conversation);

        // Verify user is part of the conversation
        if ($conversationModel->patient_id !== $user->id && $conversationModel->doctor_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $message = DB::transaction(function () use ($request, $conversationModel, $user) {

            $message = Message::create([
                'conversation_id'  => $conversationModel->id,
                'sender_user_id'   => $user->id,
                'type'             => 'text',
                'body'             => $request->string('body')->toString(),
                'media_url'        => null,
                'media_size_bytes' => null,
                'media_mime'       => null,
                'sent_at_utc'      => now(),
            ]);

            $this->updateConversationLastMessageAt(
                $conversationModel->id,
                $message->sent_at_utc
            );

            return $message;
        });

        broadcast(new MessageSent($message))->toOthers();

        return response()->json($message, 201);
    }

    public function sendMediaMessage(MediaMessageStoreRequest $request): JsonResponse
    {
        $patientId = $request->user()->id;
        $doctorId = $request->doctor_id;

        // تحقق من أن المريض هو جزء من المحادثة
        $conversation = Conversation::where(function ($query) use ($patientId, $doctorId) {
            $query->where('patient_id', $patientId)
                ->where('doctor_id', $doctorId);
        })->first();

        if (!$conversation) {
            return response()->json([
                'message' => 'Unauthorized, conversation does not exist or you do not have permission to send messages.'
            ], 403);
        }

        $file = $request->file('media');
        $mime = $file->getMimeType();
        $size = $file->getSize();

        $type = str_starts_with($mime, 'video/') ? 'video' : 'image';
        $path = $file->store('chat', 'public');

        $message = DB::transaction(function () use ($conversation, $patientId, $path, $mime, $size, $type) {

            $message = Message::create([
                'conversation_id'  => $conversation->id,
                'sender_user_id'   => $patientId,
                'type'             => $type,
                'body'             => null,
                'media_url'        => Storage::disk('public')->url($path),
                'media_size_bytes' => $size,
                'media_mime'       => $mime,
                'sent_at_utc'      => now(),
            ]);

            $this->updateConversationLastMessageAt(
                $conversation->id,
                $message->sent_at_utc
            );

            return $message;
        });

        broadcast(new MessageSent($message))->toOthers();

        return response()->json($message, 201);
    }
    private function updateConversationLastMessageAt(int $conversationId, $sentAt): void
    {
        Conversation::query()
            ->whereKey($conversationId)
            ->update([
                'last_message_at_utc' => $sentAt,
            ]);
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

    private function forbiddenResponse(): JsonResponse
    {
        return response()->json([
            'status'  => false,
            'message' => 'You are not allowed to access this conversation.'
        ], 403);
    }

    public function fetchMessages(Request $request, int $conversationId)
    {
        $userId = $request->user()->id;

        if (!$this->ensureUserBelongsToConversation($userId, $conversationId)) {
            return $this->forbiddenResponse();
        }

        $messages = Message::query()
            ->where('conversation_id', $conversationId)
            ->orderBy('sent_at_utc', 'asc')
            ->paginate(20);

        return response()->json($messages);
    }
}
