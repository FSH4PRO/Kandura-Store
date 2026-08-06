<?php

namespace App\Payments\Strategies;

use App\DTO\Payments\PayOrderData;
use App\Enums\OrderStatus;
use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Models\Customer;
use App\Models\Order;
use App\Payments\Contracts\PaymentStrategy;

class CodPaymentStrategy implements PaymentStrategy
{
    public function pay(Customer $customer, Order $order, PayOrderData $data): array
    {

        $order->update([
            'payment_method' => PaymentMethod::COD,
            'payment_status' => PaymentStatus::Unpaid,
            'payment_reference' => null,
            'payment_meta' => null,
            'status' => OrderStatus::Processing,
        ]);

        return [
            'payment_method' => 'cod',
            'payment_status' => 'unpaid',
            'redirect_url'   => null,
        ];
    }
}
