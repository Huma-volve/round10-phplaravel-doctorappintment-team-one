<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public Message $message) {}

    public function broadcastOn()
    {
        return new \Illuminate\Broadcasting\Channel('conversation.' . $this->message->conversation_id);
    }

    public function broadcastAs(): string
    {
        return 'message.sent';
    }

    public function broadcastWith(): array
    {
        return [
            'id' => $this->message->id,
            'conversation_id' => $this->message->conversation_id,
            'sender_id' => $this->message->sender_user_id,
            'body' => $this->message->body,
            'type' => $this->message->type,
            'media_url' => $this->message->media_path ? url('storage/' . $this->message->media_path) : null,
            'sent_at_utc' => optional($this->message->sent_at_utc)->toISOString(),
        ];
    }
}
