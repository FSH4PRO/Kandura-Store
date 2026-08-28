<?php

namespace App\Notifications\Admin;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class AdminOrderCreatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public $order) {}

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'event' => 'order_created',
            'title_key' => 'notifications.order_created_admin.title',
            'body_key' => 'notifications.order_created_admin.body',
            'params' => [],
            'title' => __('notifications.order_created_admin.title'),
            'body' => __('notifications.order_created_admin.body'),
            'data' => [
                'order_id' => $this->order->id,
            ],
        ];
    }
}
