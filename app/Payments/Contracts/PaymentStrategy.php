<?php

namespace App\Payments\Contracts;

use App\Models\Order;
use App\Models\Customer;
use App\DTO\Payments\PayOrderData;

interface PaymentStrategy
{
    public function pay(Customer $customer, Order $order, PayOrderData $data): array;
}