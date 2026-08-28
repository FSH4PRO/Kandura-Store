<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Notifications\DatabaseNotification;
use App\Http\Resources\NotificationResource;

class NotificationController extends Controller
{
    /**
     * GET /api/notifications — doc: notification center (§12/§16).
     * Notifications are attached to the Customer model (the same
     * identity every other customer-facing policy/service already keys
     * on — see DesignPolicy, OrderService) — NOT the Admin model and NOT
     * the polymorphic User wrapper, so a customer can never see an
     * admin's notifications or vice versa.
     */
    public function index(Request $request)
    {
        $customer = $request->user('customer');

        $notifications = $customer->notifications()
            ->when($request->query('read'), function ($query) use ($request) {
                if ($request->query('read') === 'unread') {
                    $query->whereNull('read_at');
                } elseif ($request->query('read') === 'read') {
                    $query->whereNotNull('read_at');
                }
            })
            ->latest()
            ->paginate(15);

        return $this->success(
            NotificationResource::collection($notifications)->response()->getData(true),
            __('messages.success_operation')
        );
    }

    /**
     * GET /api/notifications/unread-count
     */
    public function unreadCount(Request $request)
    {
        $customer = $request->user('customer');

        return $this->success(
            ['count' => $customer->unreadNotifications()->count()],
            __('messages.success_operation')
        );
    }

    /**
     * POST /api/notifications/{id}/read — ownership is checked
     * explicitly (matching the same principle as design/order
     * ownership elsewhere in the app): a customer must not be able to
     * mark another customer's notification as read by guessing its id.
     */
    public function markRead(Request $request, string $id)
    {
        $customer = $request->user('customer');

        $notification = $customer->notifications()->whereKey($id)->first();

        if (! $notification) {
            return $this->failed(__('messages.not_found'), null, 404);
        }

        if (! $notification->read_at) {
            $notification->markAsRead();
        }

        return $this->success(new NotificationResource($notification->fresh()), __('messages.success_operation'));
    }

    /**
     * POST /api/notifications/read-all
     */
    public function markAllRead(Request $request)
    {
        $customer = $request->user('customer');

        $customer->unreadNotifications()->update(['read_at' => now()]);

        return $this->success(null, __('messages.success_operation'));
    }
}
