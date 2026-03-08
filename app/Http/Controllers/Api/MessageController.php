<?php

namespace App\Http\Controllers\Api;

use App\Events\MessageSent;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\MediaMessageStoreRequest;
use App\Http\Requests\Api\MessageStoreRequest;
use App\Models\Conversation;
use App\Models\Message;
use App\Services\NotificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class MessageController extends Controller
{
    public function index(Request $request, int $conversationId): JsonResponse
    {
        $userId = $request->user()->id;

        if (!$this->ensureUserBelongsToConversation($userId, $conversationId)) {
            return $this->forbiddenResponse();
        }

        $messages = Message::query()
            ->where('conversation_id', $conversationId)
            ->orderBy('id', 'asc')
            ->paginate(20);

        return response()->json($messages);
    }

    public function store(MessageStoreRequest $request): JsonResponse
    {
        $conversationId = $request->route('conversation');
        $userId = $request->user()->id;

        if (!$this->ensureUserBelongsToConversation($userId, $conversationId)) {
            return $this->forbiddenResponse();
        }

        $message = DB::transaction(function () use ($request, $conversationId, $userId) {

            $message = Message::create([
                'conversation_id'   => $conversationId,
                'sender_user_id'    => $userId,
                'type'              => 'text',
                'body'              => $request->body,
                'media_url'         => null,
                'media_size_bytes'  => null,
                'media_mime'        => null,
                'sent_at_utc'       => now(),
            ]);

            Conversation::whereKey($conversationId)
                ->update(['last_message_at_utc' => now()]);

            return $message;
        });

        $conversation = Conversation::findOrFail($conversationId);

        $recipientId = $conversation->patient_id == $userId
            ? $conversation->doctor->user_id
            : $conversation->patient_id;

        app(NotificationService::class)->notify(
            $recipientId,
            'chat',
            'in_app',
            'New Message',
            'You have a new message: ' . substr($request->body, 0, 50),
            [
                'message_id' => $message->id,
                'conversation_id' => $conversationId
            ]
        );

        broadcast(new MessageSent($message))->toOthers();

        return response()->json($message, 201);
    }

    public function sendMediaMessage(MediaMessageStoreRequest $request): JsonResponse
    {
        $conversationId = $request->route('conversation');
        $userId = $request->user()->id;

        if (!$this->ensureUserBelongsToConversation($userId, $conversationId)) {
            return $this->forbiddenResponse();
        }

        $file = $request->file('media');
        $mime = $file->getMimeType();
        $size = $file->getSize();
        $type = str_starts_with($mime, 'video/') ? 'video' : 'image';

        $path = $file->store('chat', 'public');

        try {

            $message = DB::transaction(function () use ($conversationId, $userId, $path, $mime, $size, $type) {

                $message = Message::create([
                    'conversation_id'   => $conversationId,
                    'sender_user_id'    => $userId,
                    'type'              => $type,
                    'body'              => null,
                    'media_url'         => Storage::disk('public')->url($path),
                    'media_size_bytes'  => $size,
                    'media_mime'        => $mime,
                    'sent_at_utc'       => now(),
                ]);

                Conversation::whereKey($conversationId)
                    ->update(['last_message_at_utc' => now()]);

                return $message;
            });

            $conversation = Conversation::findOrFail($conversationId);

            $recipientId = $conversation->patient_id == $userId
                ? $conversation->doctor->user_id
                : $conversation->patient_id;

            app(NotificationService::class)->notify(
                $recipientId,
                'chat',
                'in_app',
                'New Media Message',
                'You received a ' . $type . ' message',
                [
                    'message_id' => $message->id,
                    'conversation_id' => $conversationId
                ]
            );

            broadcast(new MessageSent($message))->toOthers();

            return response()->json($message, 201);

        } catch (\Throwable $e) {

            Storage::disk('public')->delete($path);

            throw $e;
        }
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
