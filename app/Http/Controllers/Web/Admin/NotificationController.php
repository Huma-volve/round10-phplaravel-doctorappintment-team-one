<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\NotificationLog;
use App\Models\User;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    
 public function index()
{
    $user = auth()->user();

    if (!$user) {
        return redirect()->back()->with('error', 'Unauthorized');
    }

    $notifications = NotificationLog::where('user_id', $user->id)
        ->orderByDesc('created_at')
        ->paginate(15);

    return view('admin.notifications.index', compact('notifications'));
}
    
    // public function unreadCount()
    // {
    //     $admin = User::where('role', 'admin')->first();

    //     if (!$admin) {
    //         return 0;
    //     }

    //     $unreadCount = NotificationLog::where('user_id', $admin->id)
    //         ->whereNull('read_at_utc')
    //         ->count();

    //     return response()->json(['unread_count' => $unreadCount]);
    // }

  
    // public function getByType($type)
    // {
    //     $admin = User::where('role', 'admin')->first();

    //     if (!$admin) {
    //         return redirect()->back()->with('error', 'Admin not found');
    //     }

    //     $notifications = NotificationLog::where('user_id', $admin->id)
    //         ->where('type', $type)
    //         ->orderByDesc('created_at')
    //         ->paginate(15);

    //     return view('admin.notifications.by-type', compact('notifications', 'type'));
    // }

    
    // public function unread()
    // {
    //     $admin = User::where('role', 'admin')->first();

    //     if (!$admin) {
    //         return redirect()->back()->with('error', 'Admin not found');
    //     }

    //     $notifications = NotificationLog::where('user_id', $admin->id)
    //         ->whereNull('read_at_utc')
    //         ->orderByDesc('created_at')
    //         ->paginate(15);

    //     return view('admin.notifications.unread', compact('notifications'));
    // }

   
    public function markAsRead($id)
    {
         $user = auth()->user();


        $notification = NotificationLog::where('id', $id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        if ($notification->read_at_utc === null) {
            $notification->read_at_utc = now();
            $notification->save();
        }

        
         return redirect()->back()->with('success', 'Notification marked as read');
    }

   
    public function markAllAsRead()
    {
         $user = auth()->user();


        NotificationLog::where('user_id', $user->id)
            ->whereNull('read_at_utc')
            ->update(['read_at_utc' => now()]);

               return redirect()->back()->with('success', 'Notifications marked as read');

    }
   
    public function delete($id)
    {
         $user = auth()->user();


        $notification = NotificationLog::where('id', $id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        $notification->delete();

                return redirect()->back()->with('success', 'Notification deleted');

    }
}
   
