<?php

namespace App\Http\Controllers\Notification;

use App\Http\Controllers\Controller;
use App\Models\NotificationLog;
use App\Models\NotificationPreference;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class NotificationController extends Controller
{
    // ✅ GET /api/notifications?unread=1&limit=20
    public function index(Request $request)
    {
        $user = $request->user();

        $q = NotificationLog::query()
            ->where('user_id', $user->id);

        if ($request->boolean('unread')) {
            $q->whereNull('read_at_utc');
        }

        $limit = min((int) $request->input('limit', 20), 100);

        return response()->json(
            $q->orderByDesc('id')->paginate($limit)
        );
    }

    // ✅ PATCH /api/notifications/{id}/read
    public function markRead(Request $request, int $id)
    {
        $user = $request->user();

        $notification = NotificationLog::where('id', $id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        if ($notification->read_at_utc === null) {
            $notification->read_at_utc = now();
            $notification->save();
        }

        return response()->json([
            'message' => 'Notification marked as read',
        ]);
    }

    // ✅ PATCH /api/notifications/read-all
    public function markAllRead(Request $request)
    {
        $user = $request->user();

        NotificationLog::where('user_id', $user->id)
            ->whereNull('read_at_utc')
            ->update(['read_at_utc' => now()]);

        return response()->json([
            'message' => 'All notifications marked as read',
        ]);
    }

    // ✅ GET /api/notification-preferences
    public function preferences(Request $request)
    {
        $user = $request->user();

        $prefs = NotificationPreference::where('user_id', $user->id)
            ->orderBy('event')
            ->orderBy('channel')
            ->get();

        return response()->json($prefs);
    }

    // ✅ PATCH /api/notification-preferences
    public function updatePreference(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'event' => ['required', Rule::in(['booking','cancellation','chat','review','payment','system'])],
            'channel' => ['required', Rule::in(['email','in_app'])],
            'enabled' => ['required', 'boolean'],
        ]);

        $pref = NotificationPreference::updateOrCreate(
            [
                'user_id' => $user->id,
                'event' => $validated['event'],
                'channel' => $validated['channel'],
            ],
            [
                'enabled' => $validated['enabled'],
            ]
        );

        return response()->json([
            'message' => 'Preference updated',
            'data' => $pref,
        ]);
    }
}