<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\Message;
use App\Events\MessageSent; // تضمين الحدث
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class MessageController extends Controller
{
    public function index($conversationId)
    {
        $messages = Message::where('conversation_id', $conversationId)
            ->orderBy('id', 'asc')
            ->get();

        return response()->json($messages);
    }
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'conversation_id' => 'required|exists:conversations,id',
                'sender_user_id' => 'required|exists:users,id',
                'type' => 'required|in:text,image,video',
                'body' => 'required|string',
                'media_url' => 'nullable|string',
                'media_size_bytes' => 'nullable|integer',
                'media_mime' => 'nullable|string',
            ]);

            $message = Message::create([
                'conversation_id' => $validated['conversation_id'],
                'sender_user_id' => $validated['sender_user_id'],
                'type' => $validated['type'],
                'body' => $validated['body'],
                'media_url' => $validated['media_url'] ?? null,
                'media_size_bytes' => $validated['media_size_bytes'] ?? null,
                'media_mime' => $validated['media_mime'] ?? null,
                'sent_at_utc' => now(),
            ]);

            Conversation::whereKey($validated['conversation_id'])
                ->update(['last_message_at_utc' => now()]);

            // Notify the recipient of the message
            $conversation = Conversation::findOrFail($validated['conversation_id']);
            $recipientId = $conversation->patient_id == $validated['sender_user_id'] ? $conversation->doctor->user_id : $conversation->patient_id;
            
            app(NotificationService::class)->notify(
                $recipientId,
                'chat',
                'in_app',
                'New Message',
                'You have a new message: ' . substr($validated['body'], 0, 50),
                ['message_id' => $message->id, 'conversation_id' => $conversation->id]
            );

            broadcast(new MessageSent($message));

            return response()->json($message, 201);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function sendMediaMessage(Request $request)
    {
        $request->validate([
            'conversation_id' => 'required|exists:conversations,id',
            'sender_user_id'  => 'required|exists:users,id',
            'media'           => 'required|file|mimetypes:image/jpeg,image/png,image/webp,video/mp4,video/quicktime|max:51200',
        ]);

        $file = $request->file('media');
        $mime = $file->getMimeType();
        $size = $file->getSize();

        $type = str_starts_with($mime, 'video/') ? 'video' : 'image';

        $path = $file->store('chat', 'public');
        $url  = asset('storage/' . $path);

        $message = Message::create([
            'conversation_id'   => $request->conversation_id,
            'sender_user_id'    => $request->sender_user_id,
            'type'              => $type,                 // ✅ type not message_type
            'body'              => '',                    // أو null لو تخليها nullable
            'media_url'         => $url,
            'media_size_bytes'  => $size,
            'media_mime'        => $mime,
            'sent_at_utc'       => now(),
        ]);

        Conversation::where('id', $request->conversation_id)
            ->update(['last_message_at_utc' => now()]);

        // Notify the recipient of the media message
        $conversation = Conversation::find($request->conversation_id);
        $recipientId = $conversation->patient_id == $request->sender_user_id ? $conversation->doctor->user_id : $conversation->patient_id;
        
        app(NotificationService::class)->notify(
            $recipientId,
            'chat',
            'in_app',
            'New Message',
            'You received a ' . $type . ' message',
            ['message_id' => $message->id, 'conversation_id' => $conversation->id]
        );

        broadcast(new \App\Events\MessageSent($message))->toOthers();

        return response()->json($message, 201);
    }
}
