<?php

namespace App\Services\User;

use App\Models\Order;
use App\Models\Design;
use App\Models\Customer;
use Illuminate\Support\Facades\DB;

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
        return DB::transaction(function () use ($customer, $data) {

            $subtotal = 0;

           
            $order = Order::create([
                'customer_id' => $customer->id,
                'subtotal'    => 0,
                'discount'    => 0,
                'total'       => 0,
            ]);

            foreach ($data['items'] as $itemData) {

                $design = Design::findOrFail($itemData['design_id']);

                $unitPrice = (float) $design->price;
                $qty       = (int) $itemData['quantity'];

                $lineTotal = $unitPrice * $qty;
                $subtotal += $lineTotal;

               
                $item = $order->items()->create([
                    'design_id'  => $design->id,
                    'size_id'    => $itemData['size_id'],
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

           
            $discount = 0;

            $order->update([
                'subtotal' => $subtotal,
                'discount' => $discount,
                'total'    => $subtotal - $discount,
            ]);

            return $order->load([
                'items.design',
                'items.size',
                'items.options.option',
            ]);
        });
    }
}
