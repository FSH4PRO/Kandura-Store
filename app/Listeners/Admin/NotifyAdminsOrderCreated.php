<?php

namespace App\Listeners\Admin;

use App\Models\Admin;
use App\Events\OrderCreated;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\InteractsWithQueue;
use NotificationChannels\Fcm\FcmMessage;
use Illuminate\Contracts\Queue\ShouldQueue;
use Kreait\Laravel\Firebase\Facades\Firebase;
use App\Notifications\Admin\AdminOrderCreatedNotification;
use NotificationChannels\Fcm\Resources\Notification as FcmNotification;

class NotifyAdminsOrderCreated implements ShouldQueue
{
    use InteractsWithQueue;


    public function handle(OrderCreated $event): void
    {
        $admins = Admin::whereNotNull('fcm_token')->get();

        $messaging = Firebase::project('app')->messaging();

        foreach ($admins as $admin) {
            $message = FcmMessage::create()
                ->data([
                    'title' => 'New Order Created',
                    'body'  => 'A new order has been placed.',
                    'url'   => route('admin.orders.show', $event->order->id),
                    'type'  => 'order',
                    'id'    => (string) $event->order->id,
                ]);

            try {
                $messaging->sendMulticast($message, [$admin->fcm_token]);
            } catch (\Throwable $e) {
                // احذف التوكن الفاسد
                $admin->update(['fcm_token' => null]);

                Log::error('FCM send failed', [
                    'admin_id' => $admin->id,
                    'error' => $e->getMessage(),
                ]);
            }
            $admin->notify(new AdminOrderCreatedNotification($event->order));
        }
    }
}
