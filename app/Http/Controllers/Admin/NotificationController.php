<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserNotification;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class NotificationController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Display the notification form
     */
    public function index()
    {
        $users = User::where('role', 'user')
            ->select('id', 'name', 'email', 'status')
            ->orderBy('name')
            ->get();

        return view('admin.notifications.index', compact('users'));
    }

    /**
     * Send notification to selected users
     */
    public function sendNotification(Request $request)
    {
        $request->validate([
            'recipient_type' => 'required|in:selected,all',
            'user_ids' => 'required_if:recipient_type,selected|array',
            'user_ids.*' => 'exists:users,id',
            'title' => 'required|string|max:255',
            'message' => 'required|string|max:1000',
            'type' => 'required|in:system,announcement,maintenance,security,update'
        ]);

        try {
            $recipientType = $request->recipient_type;
            $title = $request->title;
            $message = $request->message;
            $type = $request->type;
            $adminId = (int) auth()->id();

            if ($recipientType === 'all') {
                // Send to all users
                $users = User::where('role', 'user')->get();
                $result = $this->notificationService->sendBulkNotifications(
                    $users,
                    $type,
                    $title,
                    $message,
                    $adminId
                );
                $sentCount = $result['success_count'];
            } else {
                // Send to selected users
                $userIds = $request->user_ids;
                $users = User::whereIn('id', $userIds)->get();
                $result = $this->notificationService->sendBulkNotifications(
                    $users,
                    $type,
                    $title,
                    $message,
                    $adminId
                );
                $sentCount = $result['success_count'];
            }

            Log::info('Admin notification sent', [
                'admin_id' => $adminId,
                'recipient_type' => $recipientType,
                'user_count' => $sentCount,
                'title' => $title,
                'type' => $type
            ]);

            return redirect()->back()->with('success', "Notification sent successfully to {$sentCount} user(s).");

        } catch (\Exception $e) {
            Log::error('Failed to send admin notification: ' . $e->getMessage(), [
                'admin_id' => (int) auth()->id(),
                'request_data' => $request->all()
            ]);

            return redirect()->back()->with('error', 'Failed to send notification. Please try again.');
        }
    }

    /**
     * Get user details for AJAX requests
     */
    public function getUserDetails(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id'
        ]);

        $user = User::findOrFail($request->user_id);
        
        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'status' => $user->status,
            'created_at' => $user->created_at->format('M d, Y')
        ]);
    }

    /**
     * Get notification statistics
     */
    public function getStats()
    {
        $totalUsers = User::where('role', 'user')->count();
        $activeUsers = User::where('role', 'user')->where('status', 'active')->count();
        $inactiveUsers = User::where('role', 'user')->where('status', 'inactive')->count();

        return response()->json([
            'total_users' => $totalUsers,
            'active_users' => $activeUsers,
            'inactive_users' => $inactiveUsers
        ]);
    }

    /**
     * Get notification history
     */
    public function getHistory(Request $request)
    {
        $query = UserNotification::with('user')
            ->where('data->sent_by', 'admin')
            ->orderBy('created_at', 'desc');

        // Filter by type if provided
        if ($request->has('type') && $request->type) {
            $query->where('type', $request->type);
        }

        // Filter by date range if provided
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $notifications = $query->paginate(20);

        return response()->json($notifications);
    }

    /**
     * Update a notification
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string|max:1000',
            'type' => 'required|in:system,announcement,maintenance,security,update'
        ]);

        try {
            $notification = UserNotification::findOrFail($id);
            
            // Check if it's an admin notification
            if (!isset($notification->data['sent_by']) || $notification->data['sent_by'] !== 'admin') {
                return response()->json(['error' => 'Only admin notifications can be edited'], 403);
            }

            $notification->update([
                'title' => $request->title,
                'message' => $request->message,
                'type' => $request->type,
                'data' => array_merge($notification->data, [
                    'type' => $request->type,
                    'edited_by' => auth()->id(),
                    'edited_at' => now()->toISOString()
                ])
            ]);

            Log::info('Admin notification updated', [
                'notification_id' => $id,
                'admin_id' => auth()->id(),
                'title' => $request->title
            ]);

            return response()->json(['success' => true, 'message' => 'Notification updated successfully']);

        } catch (\Exception $e) {
            Log::error('Failed to update notification: ' . $e->getMessage(), [
                'notification_id' => $id,
                'admin_id' => auth()->id()
            ]);

            return response()->json(['error' => 'Failed to update notification'], 500);
        }
    }

    /**
     * Delete a notification
     */
    public function destroy($id)
    {
        try {
            $notification = UserNotification::findOrFail($id);
            
            // Check if it's an admin notification
            if (!isset($notification->data['sent_by']) || $notification->data['sent_by'] !== 'admin') {
                return response()->json(['error' => 'Only admin notifications can be deleted'], 403);
            }

            $notification->delete();

            Log::info('Admin notification deleted', [
                'notification_id' => $id,
                'admin_id' => auth()->id()
            ]);

            return response()->json(['success' => true, 'message' => 'Notification deleted successfully']);

        } catch (\Exception $e) {
            Log::error('Failed to delete notification: ' . $e->getMessage(), [
                'notification_id' => $id,
                'admin_id' => auth()->id()
            ]);

            return response()->json(['error' => 'Failed to delete notification'], 500);
        }
    }

    /**
     * Get notification details for editing
     */
    public function show($id)
    {
        try {
            $notification = UserNotification::with('user')->findOrFail($id);
            
            // Check if it's an admin notification
            if (!isset($notification->data['sent_by']) || $notification->data['sent_by'] !== 'admin') {
                return response()->json(['error' => 'Only admin notifications can be viewed'], 403);
            }

            return response()->json([
                'id' => $notification->id,
                'title' => $notification->title,
                'message' => $notification->message,
                'type' => $notification->type,
                'user_name' => $notification->user->name,
                'user_email' => $notification->user->email,
                'created_at' => $notification->created_at->format('M d, Y H:i'),
                'read_at' => $notification->read_at ? $notification->read_at->format('M d, Y H:i') : null
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Notification not found'], 404);
        }
    }
}
