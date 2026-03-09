<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NotificationLog;
use App\Models\User;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    
    public function index()
    {
        $admin = User::where('role', 'admin')->first();

        if (!$admin) {
            return redirect()->back()->with('error', 'Admin not found');
        }

        $notifications = NotificationLog::where('user_id', $admin->id)
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('admin.notifications.index', compact('notifications'));
    }

    
    public function unreadCount()
    {
        $admin = User::where('role', 'admin')->first();

        if (!$admin) {
            return 0;
        }

        $unreadCount = NotificationLog::where('user_id', $admin->id)
            ->whereNull('read_at_utc')
            ->count();

        return response()->json(['unread_count' => $unreadCount]);
    }

  
    public function getByType($type)
    {
        $admin = User::where('role', 'admin')->first();

        if (!$admin) {
            return redirect()->back()->with('error', 'Admin not found');
        }

        $notifications = NotificationLog::where('user_id', $admin->id)
            ->where('type', $type)
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('admin.notifications.by-type', compact('notifications', 'type'));
    }

    
    public function unread()
    {
        $admin = User::where('role', 'admin')->first();

        if (!$admin) {
            return redirect()->back()->with('error', 'Admin not found');
        }

        $notifications = NotificationLog::where('user_id', $admin->id)
            ->whereNull('read_at_utc')
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('admin.notifications.unread', compact('notifications'));
    }

   
    public function markAsRead($id)
    {
        $admin = User::where('role', 'admin')->first();

        if (!$admin) {
            return response()->json(['message' => 'Admin not found'], 404);
        }

        $notification = NotificationLog::where('id', $id)
            ->where('user_id', $admin->id)
            ->firstOrFail();

        if ($notification->read_at_utc === null) {
            $notification->read_at_utc = now();
            $notification->save();
        }

        return response()->json(['message' => 'Notification marked as read']);
    }

   
    public function markAllAsRead()
    {
        $admin = User::where('role', 'admin')->first();

        if (!$admin) {
            return response()->json(['message' => 'Admin not found'], 404);
        }

        NotificationLog::where('user_id', $admin->id)
            ->whereNull('read_at_utc')
            ->update(['read_at_utc' => now()]);

        return response()->json(['message' => 'All notifications marked as read']);
    }

   
    public function delete($id)
    {
        $admin = User::where('role', 'admin')->first();

        if (!$admin) {
            return response()->json(['message' => 'Admin not found'], 404);
        }

        $notification = NotificationLog::where('id', $id)
            ->where('user_id', $admin->id)
            ->firstOrFail();

        $notification->delete();

        return response()->json(['message' => 'Notification deleted']);
    }
}
   
//     public function statistics()
//     {
//         $admin = User::where('role', 'admin')->first();

//         if (!$admin) {
//             return response()->json(['message' => 'Admin not found'], 404);
//         }

//         $stats = [
//             'total' => NotificationLog::where('user_id', $admin->id)->count(),
//             'unread' => NotificationLog::where('user_id', $admin->id)->whereNull('read_at_utc')->count(),
//             'by_type' => NotificationLog::where('user_id', $admin->id)
//                 ->selectRaw('type, count(*) as count')
//                 ->groupBy('type')
//                 ->get()
//                 ->pluck('count', 'type'),
//             'by_channel' => NotificationLog::where('user_id', $admin->id)
//                 ->selectRaw('channel, count(*) as count')
//                 ->groupBy('channel')
//                 ->get()
//                 ->pluck('count', 'channel'),
//         ];

//         return response()->json($stats);
//     }
// }
