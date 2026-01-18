<?php

namespace App\Policies;

use App\Models\Admin;
use App\Models\Order;
use App\Models\Customer;
use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;

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
        // Check ownership
        if ($customer->id !== $order->customer_id) {
            return false;
        }

        // Can only cancel orders that are pending
        return $order->status === OrderStatus::Pending;
    }

    /**
     * Determine if the customer can pay for the order.
     */
    public function pay(Customer $customer, Order $order): bool
    {
        // Check ownership
        if ($customer->id !== $order->customer_id) {
            return false;
        }

        // Order must be accepted before payment
        if ($order->status !== OrderStatus::Accepted) {
            return false;
        }

        // If already paid, cannot pay again (but this is handled in service logic)
        // Allow payment if unpaid, pending (for retry), failed, or canceled
        return in_array($order->payment_status, [
            PaymentStatus::Unpaid,
            PaymentStatus::Pending,
            PaymentStatus::Failed,
            PaymentStatus::Canceled,
        ], true);
    }

    /**
     * Determine if the customer can view payment details for the order.
     */
    public function viewPayment(Customer $customer, Order $order): bool
    {
        return $customer->id === $order->customer_id;
    }

    /**
     * Determine if the customer can retry payment for a failed order.
     */
    public function retryPayment(Customer $customer, Order $order): bool
    {
        // Check ownership
        if ($customer->id !== $order->customer_id) {
            return false;
        }

        // Order must still be accepted
        if ($order->status !== OrderStatus::Accepted) {
            return false;
        }

        // Can retry if payment failed or was canceled
        return in_array($order->payment_status, [
            PaymentStatus::Failed,
            PaymentStatus::Canceled,
        ], true);
    }

    // ==================== Admin Methods ====================

    /**
     * Determine if the admin can view any orders.
     */
    public function viewAny(Admin $admin): bool
    {
        // Admins with order management permissions can view all orders
        return $admin->hasRole(['super_admin', 'manage_orders'])
            || $admin->can('orders.view');
    }

    /**
     * Determine if the admin can view a specific order.
     */
    public function viewAsAdmin(Admin $admin, Order $order): bool
    {
        // Admins with order management permissions can view any order
        return $admin->hasRole(['super_admin', 'manage_orders'])
            || $admin->can('orders.view');
    }

    /**
     * Determine if the admin can update order status.
     */
    public function updateStatus(Admin $admin, Order $order): bool
    {
        // Admins with order management permissions can update order status
        return $admin->can('orders.change_status');
    }





    public function applyCoupon(Customer $customer, Order $order): bool
    {
        if ($customer->id !== $order->customer_id) {
            return false;
        }

        // لا تطبق كوبون إذا الدفع pending أو paid
        if (in_array($order->payment_status, [PaymentStatus::Pending, PaymentStatus::Paid], true)) {
            return false;
        }

        // لا تطبق كوبون إذا الطلب canceled/rejected (حسب عندك)
        if (in_array($order->status, [OrderStatus::Canceled, OrderStatus::Rejected], true)) {
            return false;
        }

        // لا تطبق كوبون إذا في كوبون أصلاً
        return $order->coupon_id === null;
    }

    public function removeCoupon(Customer $customer, Order $order): bool
    {
        if ($customer->id !== $order->customer_id) {
            return false;
        }

        // لا تحذف كوبون إذا الدفع pending أو paid
        if (in_array($order->payment_status, [PaymentStatus::Pending, PaymentStatus::Paid], true)) {
            return false;
        }

        // لا تحذف كوبون إذا الطلب canceled/rejected
        if (in_array($order->status, [OrderStatus::Canceled, OrderStatus::Rejected], true)) {
            return false;
        }

        // يمكن حذف كوبون إذا في كوبون مطبق
        return $order->coupon_id !== null;
    }
}
