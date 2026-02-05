<?php

namespace App\Services\Order;

use App\Models\Order;
use Illuminate\Support\Facades\DB;
use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;

class CancelExpiredOrdersService
{
    public function cancel(): int
    {
        return DB::transaction(function () {

            $expiredOrders = Order::query()
                ->whereNotIn('status', [OrderStatus::Canceled])
                ->whereIn('payment_status', [
                    PaymentStatus::Unpaid,
                    PaymentStatus::Pending,
                ])
                ->where('created_at', '<=', now()->subDays(3))
                ->lockForUpdate()
                ->get();

            foreach ($expiredOrders as $order) {
                $order->update([
                    'status' => OrderStatus::Canceled,
                ]);

                // optional: fire event for notifications
                event(new \App\Events\Orders\OrderCancelled($order));
            }

            return $expiredOrders->count();
        });
    }
}
