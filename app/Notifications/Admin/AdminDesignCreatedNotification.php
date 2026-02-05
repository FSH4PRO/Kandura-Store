<?php

namespace App\Notifications\Admin;

use App\Models\Design;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use NotificationChannels\Fcm\FcmMessage;
use NotificationChannels\Fcm\Resources\Notification as FcmNotification;

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
            'title' => 'New Design Created',
            'body'  => 'A new design has been created by a user. Tap to review it in the design list',
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
