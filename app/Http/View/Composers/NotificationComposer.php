<?php

namespace App\Http\View\Composers;

use App\Models\NotificationLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class NotificationComposer
{
    public function compose(View $view): void
    {
        $user = Auth::user();

        if (!$user) {
            $view->with([
                'notifications' => collect(),
                'unreadCount' => 0,
            ]);
            return;
        }

        $notifications = NotificationLog::where('user_id', $user->id)
            ->latest('created_at')
            ->limit(5)
            ->get();

        $unreadCount = NotificationLog::where('user_id', $user->id)
            ->whereNull('read_at_utc')
            ->count();

        $view->with([
            'notifications' => $notifications,
            'unreadCount' => $unreadCount,
            'loggedInRole' => $user->role,
        ]);
    }
}