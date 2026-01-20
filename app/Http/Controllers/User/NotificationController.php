<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\UserNotification;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class NotificationController extends Controller
{
    /**
     * Display the notifications page.
     */
    public function index(Request $request): View
    {
        $notifications = UserNotification::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($notification) {
                return [
                    'id' => $notification->id,
                    'type' => $notification->type,
                    'title' => $notification->title,
                    'message' => $notification->message,
                    'data' => $notification->data,
                    'read_at' => $notification->read_at ? $notification->read_at->toISOString() : null,
                    'created_at' => $notification->created_at->toISOString(),
                ];
            });

        $reactProps = [
            'notifications' => $notifications,
            'csrfToken' => csrf_token(),
        ];

        return view('dashboard.notifications.new', compact('reactProps'));
    }

    /**
     * Mark a notification as read.
     */
    public function markAsRead(Request $request, $id): JsonResponse
    {
        $notification = UserNotification::where('user_id', auth()->id())
            ->where('id', $id)
            ->firstOrFail();

        $notification->markAsRead();

        return response()->json(['success' => true]);
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllAsRead(Request $request): JsonResponse
    {
        UserNotification::where('user_id', auth()->id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return response()->json(['success' => true]);
    }

    /**
     * Delete a notification.
     */
    public function destroy(Request $request, $id): JsonResponse
    {
        $notification = UserNotification::where('user_id', auth()->id())
            ->where('id', $id)
            ->firstOrFail();

        $notification->delete();

        return response()->json(['success' => true]);
    }

    /**
     * Clear all notifications.
     */
    public function clearAll(Request $request): JsonResponse
    {
        UserNotification::where('user_id', auth()->id())->delete();

        return response()->json(['success' => true]);
    }

    /**
     * Get unread notifications count (for AJAX).
     */
    public function getUnreadCount(): JsonResponse
    {
        $count = UserNotification::where('user_id', auth()->id())
            ->whereNull('read_at')
            ->count();

        return response()->json(['count' => $count]);
    }

    /**
     * Get recent notifications (for AJAX).
     */
    public function getRecent(): JsonResponse
    {
        $notifications = UserNotification::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return response()->json(['notifications' => $notifications]);
    }
}
