<?php

namespace App\Notifications\User;

use App\Models\Order;
use Illuminate\Notifications\Notification;

class UserOrderStatusChangedNotification extends Notification
{
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
        return [
            'event' => 'order_status_changed',
            'title' => 'Order Status Updated',
            'body'  => "the status of your order #{$this->order->id} has been updated to {$this->newStatus}. Tap to view details",
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
