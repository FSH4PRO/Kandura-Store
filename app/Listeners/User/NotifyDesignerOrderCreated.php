<?php

namespace App\Listeners\User;

use App\Events\OrderCreated;
use App\Notifications\User\DesignerOrderCreatedNotification;

class NotifyDesignerOrderCreated
{
    public function handle(OrderCreated $event): void
    {
        $order = $event->order->loadMissing([
            'items.design.customer.user',
        ]);

        // أول تصميم بالطلب (إذا بتعامل طلب متعدد التصاميم)
        $design = $order->items->first()?->design;
        $designerUser = $design?->customer?->user;

        if (! $designerUser) return;

        $designerUser->notify(new DesignerOrderCreatedNotification($order, $design));
    }
}
