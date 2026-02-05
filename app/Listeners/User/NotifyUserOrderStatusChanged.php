<?php

namespace App\Listeners\User;

use App\Events\OrderStatusChanged;
use App\Notifications\User\UserOrderStatusChangedNotification;

class NotifyUserOrderStatusChanged
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(OrderStatusChanged $event): void
    {
        $event->order->customer->user->notify(new UserOrderStatusChangedNotification($event->order, $event->to));
    }
}
