<?php

namespace App\Notifications\Admin;

use App\Models\Design;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class AdminDesignCreatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Design $design) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        return [
            'event' => 'design_created',
            'title_key' => 'notifications.design_created_admin.title',
            'body_key' => 'notifications.design_created_admin.body',
            'params' => [],
            'title' => __('notifications.design_created_admin.title'),
            'body' => __('notifications.design_created_admin.body'),
            'action' => [
                'type' => 'route',
                'name' => 'admin.designs.show',
            ],
            'data' => [
                'design_id' => $this->design->id,
            ],
        ];
    }
}
