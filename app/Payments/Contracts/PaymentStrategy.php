<?php

namespace App\Payments\Contracts;

use App\Models\Order;
use App\Models\Customer;
use App\DTO\Payments\PayOrderData;

interface PaymentStrategy
{
    /**
     * Return payload for API:
     * - Stripe: ['redirect_url' => '...', 'reference' => '...']
     * - Wallet/COD: ['status' => 'paid'|'unpaid' ...]
     */
    public function pay(Customer $customer, Order $order, PayOrderData $data): array;
}