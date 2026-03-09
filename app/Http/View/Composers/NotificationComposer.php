<?php

namespace App\Http\View\Composers;

use App\Models\NotificationLog;
use App\Models\User;
use Illuminate\View\View;

class NotificationComposer
{
    /**
     * Bind data to the view.
     */
    public function compose(View $view): void
    {
        try {
            $admin = User::where('role', 'admin')->first();

            $notifications = [];
            $unreadCount = 0;
            
            if ($admin) {
                $notifications = NotificationLog::where('user_id', $admin->id)
                    ->orderByDesc('created_at')
                    ->limit(5)
                    ->get();
                
                $unreadCount = NotificationLog::where('user_id', $admin->id)
                    ->whereNull('read_at_utc')
                    ->count();
            }

            $view->with('notifications', $notifications);
            $view->with('unreadCount', $unreadCount);
        } catch (\Exception $e) {
            $view->with('notifications', []);
            $view->with('unreadCount', 0);
        }
    }
}
