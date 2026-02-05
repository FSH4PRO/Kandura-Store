<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Exception\Messaging\NotFound;
use Illuminate\Notifications\Events\NotificationFailed;

class HandleFcmNotificationFailed
{
    public function handle(NotificationFailed $event): void
    {
        // نهتم فقط بـ FCM
        if ($event->channel !== 'fcm') {
            return;
        }

        $admin = $event->notifiable;

        // احذف التوكن الفاسد
        if (method_exists($admin, 'update')) {
            $admin->update(['fcm_token' => null]);
        }

        Log::warning('FCM token removed due to failure', [
            'admin_id' => $admin->id ?? null,
        ]);
    }
}
