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

        
        $notification = NotificationLog::create([
            'user_id' => $userId,
            'type' => $event,
            'channel' => $channel,
            'title' => $title,
            'body' => $body,
            'data' => $data,
            'sent_at_utc' => now(),
        ]);


    }

    public function notifyAdmin(
        string $event,
        string $channel,
        string $title,
        string $body,
        array $data = []
    ): void {
        $admin = User::where('role', 'admin')->first();

        if ($admin) {
            $this->notify(
                $admin->id,
                $event,
                $channel,
                $title,
                $body,
                $data
            );
        }
    }
}