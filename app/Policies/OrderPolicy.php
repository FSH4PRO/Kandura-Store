<?php

namespace App\Policies;

use App\Models\Admin;
use App\Models\Order;
use App\Models\Customer;

class OrderPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function view(Customer $customer, Order $order): bool
    {
        return $customer->id === $order->customer_id;
    }

    public function cancel(Customer $customer, Order $order): bool
    {
        return $customer->id === $order->customer_id;
    }
}
