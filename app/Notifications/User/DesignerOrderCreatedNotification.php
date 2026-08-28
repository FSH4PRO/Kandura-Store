<?php

namespace App\Notifications\User;

use App\Models\Order;
use App\Models\Design;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class DesignerOrderCreatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Order $order,
        public Design $design
    ) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        return [
            'event' => 'order_created_for_design',
            'title_key' => 'notifications.order_created_for_design.title',
            'body_key' => 'notifications.order_created_for_design.body',
            'params' => [],
            'title' => __('notifications.order_created_for_design.title'),
            'body' => __('notifications.order_created_for_design.body'),
            'action' => [
                'type' => 'order_details',
                'order_id' => $this->order->id,
            ],
            'data' => [
                'order_id' => $this->order->id,
                'design_id' => $this->design->id,
                'customer_id' => $this->design->customer_id,
            ],
        ];
    }
}
