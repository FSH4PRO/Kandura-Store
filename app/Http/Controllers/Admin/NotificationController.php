<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Notifications\DatabaseNotification;

class NotificationController extends Controller
{
    
    public function index(Request $request)
    {
        $admin = auth('admin')->user();

        $notifications = $admin->notifications()
            ->when($request->type, function ($query) use ($request) {
                $query->where('type', 'like', '%' . $request->type . '%');
            })
            ->when($request->read, function ($query) use ($request) {
                if ($request->read === 'unread') {
                    $query->whereNull('read_at');
                } elseif ($request->read === 'read') {
                    $query->whereNotNull('read_at');
                }
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('content.notifications.index', [
            'notifications' => $notifications,
            'filters'       => $request->only(['type', 'read']),
        ]);
    }

    
    public function show(DatabaseNotification $notification)
    {
        $admin = auth('admin')->user();

        // Mark as read if not already
        if (!$notification->read_at) {
            $notification->markAsRead();
        }

        return view('content.notifications.show', [
            'notification' => $notification,
        ]);
    }

    /**
     * Mark notification as read
     */
    public function markRead($id)
    {
        Log::info('Mark read called for ID: ' . $id);

        $notification = DatabaseNotification::find($id);

        if (!$notification) {
            Log::info('Notification not found for ID: ' . $id);
            return response()->json(['success' => false, 'message' => 'Notification not found'], 404);
        }

        $admin = auth('admin')->user();

        if (!$admin) {
            Log::info('Admin not authenticated');
            return response()->json(['success' => false, 'message' => 'Not authenticated'], 401);
        }

        Log::info('Admin ID: ' . $admin->id . ', Notification notifiable_id: ' . $notification->notifiable_id . ', type: ' . $notification->notifiable_type);

        // Ensure the notification belongs to the admin
        if ($notification->notifiable_id !== $admin->id || $notification->notifiable_type !== get_class($admin)) {
            Log::info('Unauthorized access');
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $notification->markAsRead();

        Log::info('Notification marked as read');

        return response()->json(['success' => true]);
    }

   
    public function markAllRead()
    {
        Log::info('Mark all read called');

        $admin = auth('admin')->user();

        if (!$admin) {
            Log::info('Admin not authenticated for mark all');
            return response()->json(['success' => false, 'message' => 'Not authenticated'], 401);
        }

        $admin->unreadNotifications->markAsRead();

        Log::info('All notifications marked as read');

        return response()->json(['success' => true]);
    }
}
