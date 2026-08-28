<?php


namespace App\Services\User;

use App\Models\Order;
use App\Models\Review;
use App\Models\Customer;
use App\Enums\OrderStatus;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ReviewService
{
    public function create(Customer $customer, Order $order, array $data): Review
    {
        // ✅ order belongs to customer
        if ($order->customer_id !== $customer->id) {
            throw ValidationException::withMessages([
                'order' => __('reviews.order_not_found'),
            ]);
        }

        // ✅ order must be completed
        if ($order->status !== OrderStatus::Completed) {
            throw ValidationException::withMessages([
                'order' => __('reviews.order_not_completed'),
            ]);
        }

        // ✅ only one review per order
        if ($order->review()->exists()) {
            throw ValidationException::withMessages([
                'order' => __('reviews.order_already_reviewed'),
            ]);
        }

        return DB::transaction(function () use ($customer, $order, $data) {
            return Review::create([
                'customer_id' => $customer->id,
                'order_id'    => $order->id,
                'rating'      => (int) $data['rating'],
                'comment'     => $data['comment'] ?? null,
            ]);
        });
    }


    public function update(Review $review, array $data): Review
    {
        $review->update([
            'rating'  => (int) $data['rating'],
            'comment' => $data['comment'] ?? null,
        ]);

        return $review->fresh();
    }

    public function delete(Review $review): void
    {
        $review->delete();
    }
}
