<?php

namespace App\Listeners\Admin;

use App\Models\Admin;
use App\Events\DesignCreated;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\InteractsWithQueue;
use NotificationChannels\Fcm\FcmMessage;
use Illuminate\Contracts\Queue\ShouldQueue;
use Kreait\Laravel\Firebase\Facades\Firebase;
use NotificationChannels\Fcm\Resources\Notification as FcmNotification;

class NotifyAdminsDesignCreated implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(DesignCreated $event): void
    {
        $admins = Admin::whereNotNull('fcm_token')->get();

        if ($admins->isEmpty()) {
            return;
        }

        $messaging = Firebase::project('app')->messaging();

        foreach ($admins as $admin) {
            $message = FcmMessage::create()
                ->notification(
                    FcmNotification::create()
                        ->title('New Design Created')
                        ->body('A new design has been created. Tap to review it.')
                )
                ->data([
                    'event' => 'design_created',
                    'design_id' => (string) $event->design->id,
                ]);

            try {
                $messaging->sendMulticast($message, [$admin->fcm_token]);
            } catch (\Throwable $e) {
                // token فاسد → نحذفه
                $admin->update(['fcm_token' => null]);

                Log::error('FCM design notification failed', [
                    'admin_id' => $admin->id,
                    'design_id' => $event->design->id,
                    'error' => $e->getMessage(),
                ]);
            }
            $admin->notify(new \App\Notifications\Admin\AdminDesignCreatedNotification($event->design));
        }

    }
}