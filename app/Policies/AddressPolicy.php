<?php

namespace App\Policies;

use App\Models\Address;
use App\Models\Customer;

class AddressPolicy
{
    public function view(Customer $customer, Address $address): bool
    {
        return $address->customer_id === $customer->id;
    }

    public function update(Customer $customer, Address $address): bool
    {
        return $address->customer_id === $customer->id;
    }

    public function delete(Customer $customer, Address $address): bool
    {
        return $address->customer_id === $customer->id;
    }
}
