<?php

namespace App\Notifications\Admin;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use NotificationChannels\Fcm\FcmMessage;

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
            'title' => 'New Order Created',
            'body'  => 'A new order has been placed. Tap to view.',
            'data' => [
                'order_id' => $this->order->id,
            ],
        ];
    }

    
}
