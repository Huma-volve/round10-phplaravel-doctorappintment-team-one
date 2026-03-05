<?php

namespace App\Services;

use App\Models\NotificationLog;
use App\Models\NotificationPreference;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class NotificationService
{
    public function notify(
        int $userId,
        string $event,
        string $channel,
        string $title,
        string $body,
        array $data = []
    ): void {

        $user = User::find($userId);

        if (!$user) return;

        // Check preferences
        $preference = NotificationPreference::where('user_id', $userId)
            ->where('event', $event)
            ->where('channel', $channel)
            ->first();

        if ($preference && !$preference->enabled) {
            return;
        }

        // Store in DB (always)
        $notification = NotificationLog::create([
            'user_id' => $userId,
            'type' => $event,
            'channel' => $channel,
            'title' => $title,
            'body' => $body,
            'data' => $data,
            'sent_at_utc' => now(),
        ]);

        // Send email if needed
        if ($channel === 'email' && $user->email) {
            Mail::raw($body, function ($message) use ($user, $title) {
                $message->to($user->email)
                        ->subject($title);
            });

            $notification->update([
                'provider_message_id' => 'email_sent'
            ]);
        }
    }
}