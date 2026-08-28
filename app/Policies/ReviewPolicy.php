<?php


namespace App\Policies;

use App\Models\Customer;
use App\Models\Order;
use App\Models\Review;
use App\Enums\OrderStatus;

class ReviewPolicy
{
    public function update(Customer $customer, Review $review): bool
    {
        return $review->customer_id === $customer->id;
    }

    public function delete(Customer $customer, Review $review): bool
    {
        return $review->customer_id === $customer->id;
    }
}

