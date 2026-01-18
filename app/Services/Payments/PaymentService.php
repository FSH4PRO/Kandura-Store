<?php

namespace App\Services\Payments;

use App\Models\Order;
use App\Models\Customer;
use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Enums\PaymentMethod;
use App\DTO\Payments\PayOrderData;
use App\Payments\PaymentStrategyFactory;

class PaymentService
{

    public function __construct(
        protected PaymentStrategyFactory $factory
    ) {}

    public function pay(Customer $customer, Order $order, PayOrderData $data): array
    {
        // Authorization is handled by the policy in the controller
        // Business logic checks below

        // Validate order amount
        if ((float) $order->total <= 0) {
            throw new \RuntimeException('Order amount must be greater than zero.');
        }

        // إذا مدفوع لا تعيد الدفع
        if ($order->payment_status === PaymentStatus::Paid) {
            return [
                'payment_status' => 'paid',
                'redirect_url' => null,
            ];
        }

        // Allow retry for failed or canceled payments
        if ($order->payment_status === PaymentStatus::Failed || $order->payment_status === PaymentStatus::Canceled) {
            // Reset payment status to allow retry
            $order->update([
                'payment_status' => PaymentStatus::Unpaid,
                'payment_reference' => null,
            ]);
        }

        // Prevent payment if already pending (race condition protection)
        if ($order->payment_status === PaymentStatus::Pending && $data->method === PaymentMethod::Stripe) {
            // If Stripe payment is already pending, return the existing redirect URL
            $checkoutUrl = $order->payment_meta['checkout_url'] ?? null;
            if ($checkoutUrl) {
                return [
                    'payment_method' => 'stripe',
                    'payment_status' => 'pending',
                    'reference' => $order->payment_reference,
                    'redirect_url' => $checkoutUrl,
                ];
            }
        }

        $strategy = $this->factory->make($data->method);

        return $strategy->pay($customer, $order, $data);
    }
}
