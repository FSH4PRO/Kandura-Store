<?php

namespace App\Notifications\User;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class UserOrderStatusChangedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Order $order,
        public string $newStatus
    ) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        // Prefer a dedicated key for the two statuses customers care
        // about most (accepted/rejected); fall back to the generic
        // "status changed to :status" copy for every other transition
        // (processing/completed/canceled), reusing the same
        // orders.status.* labels the frontend uses (t("orders.status.*")).
        $specificKeys = [
            'accepted' => 'notifications.order_accepted',
            'rejected' => 'notifications.order_rejected',
        ];

        $base = $specificKeys[$this->newStatus] ?? 'notifications.order_status_changed';
        $params = ['status' => __('orders.status.' . $this->newStatus)];

        return [
            'event' => 'order_status_changed',
            'title_key' => $base . '.title',
            'body_key' => $base . '.body',
            'params' => $params,
            'title' => __($base . '.title', $params),
            'body' => __($base . '.body', $params),
            'action' => [
                'type' => 'order_details',
                'order_id' => $this->order->id,
            ],
            'data' => [
                'order_id' => $this->order->id,
                'status' => $this->newStatus,
            ],
        ];
    }
}
