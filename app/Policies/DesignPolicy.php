<?php

namespace App\Policies;

use App\Models\Customer;
use App\Models\Design;

class DesignPolicy
{

    public function view(Customer $customer, Design $design): bool
    {
        return true;
    }


    public function update(Customer $customer, Design $design): bool
    {
        return $design->customer_id === $customer->id;
    }


    public function delete(Customer $customer, Design $design): bool
    {
        return $design->customer_id === $customer->id;
    }


    public function create(Customer $customer): bool
    {
        return true;
    }
}
