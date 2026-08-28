<?php

namespace App\Services\User;

use App\Models\Order;
use App\Models\Admin;
use App\Models\Design;
use App\Models\Customer;
use Illuminate\Support\Facades\DB;
use App\Notifications\Admin\AdminOrderCreatedNotification;
use App\Notifications\User\DesignerOrderCreatedNotification;

class OrderService
{
    public function orderList(Customer $customer)
    {
        return Order::where('customer_id', $customer->id)
            ->with([
                'items.design',
                'items.size',
                'items.options.option',
            ])
            ->latest()
            ->paginate(10);
    }

    public function createOrder(Customer $customer, array $data): Order
    {
        $order = DB::transaction(function () use ($customer, $data) {

            $subtotal = 0.0;

            $order = Order::create([
                'customer_id'     => $customer->id,
                'subtotal'        => 0,
                'discount_total'  => 0,
                'total'           => 0,
                'address_id'      => $data['address_id'] ?? null,
                'status' => \App\Enums\OrderStatus::Pending,
                'payment_status' => \App\Enums\PaymentStatus::Unpaid,
            ]);

            foreach ($data['items'] as $itemData) {
                $design = Design::findOrFail($itemData['design_id']);

                $unitPrice = (float) $design->price;
                $qty       = (int) ($itemData['quantity'] ?? 1);

                $lineTotal = $unitPrice * $qty;
                $subtotal += $lineTotal;

                $item = $order->items()->create([
                    'design_id'  => $design->id,
                    'size_id'    => $itemData['size_id'] ?? null,
                    'quantity'   => $qty,
                    'unit_price' => $unitPrice,
                    'line_total' => $lineTotal,

                ]);

                if (! empty($itemData['options'] ?? [])) {
                    foreach ($itemData['options'] as $opt) {
                        $item->options()->create([
                            'design_option_id' => $opt['option_id'],
                            'value'            => $opt['value'] ?? null,
                        ]);
                    }
                }

            }



            $order->update([
                'subtotal'       => $subtotal,
                'total'          => $subtotal
            ]);

            return $order->load([
                'items.design',
                'items.size',
                'items.options.option',
                'coupon',
                'address',
            ]);

        });

        $this->notifyOrderCreated($order, $customer);

        return $order;
    }

    /**
     * Notify admins that a new order came in, and separately notify the
     * owner(s) of any ordered design(s) — but only if the design owner is
     * NOT the customer who placed the order (a customer ordering their
     * own design shouldn't get a "someone ordered your design" ping).
     * Dispatched after the transaction commits so a queued job never
     * looks up a row that hasn't been committed yet.
     */
    private function notifyOrderCreated(Order $order, Customer $buyer): void
    {
        Admin::query()->get()->each(
            fn (Admin $admin) => $admin->notify(new AdminOrderCreatedNotification($order))
        );

        $order->items
            ->pluck('design')
            ->filter()
            ->unique('id')
            ->reject(fn (Design $design) => $design->customer_id === $buyer->id)
            ->each(function (Design $design) use ($order) {
                $owner = Customer::find($design->customer_id);
                $owner?->notify(new DesignerOrderCreatedNotification($order, $design));
            });
    }
}
