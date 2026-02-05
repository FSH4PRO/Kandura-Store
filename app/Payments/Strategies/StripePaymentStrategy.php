<?php

namespace App\Payments\Strategies;

use Stripe\Stripe;
use Stripe\Checkout\Session;
use Stripe\Exception\ApiErrorException;
use App\Models\Order;
use App\Models\Customer;
use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\DTO\Payments\PayOrderData;
use App\Payments\Contracts\PaymentStrategy;
use Illuminate\Support\Facades\Log;

class StripePaymentStrategy implements PaymentStrategy
{
  public function pay(Customer $customer, Order $order, PayOrderData $data): array
  {
    try {
      if ((float) $order->total < 0) {
        throw new \RuntimeException('Invalid order amount.');
      }

      Stripe::setApiKey(config('services.stripe.secret'));

      $session = Session::create([
        'mode' => 'payment',
        'customer_email' => $customer->email,

        'line_items' => [
          [
            'price_data' => [
              'currency' => 'usd',
              'product_data' => [
                'name' => 'Order #' . $order->id,
              ],
              'unit_amount' => (int) round($order->total * 100),
            ],
            'quantity' => 1,
          ],
        ],

        'metadata' => [
          'order_id' => $order->id,
          'customer_id' => $customer->id,
        ],

        'success_url' => $data->successUrl
          ?? route('stripe.success', ['order' => $order->id]),

        'cancel_url' => $data->cancelUrl
          ?? route('stripe.cancel', ['order' => $order->id]),
      ]);

      $order->update([
        'payment_method'    => PaymentMethod::Stripe,
        'payment_status'    => PaymentStatus::Pending,
        'payment_reference' => $session->id,
        'payment_meta'      => [
          'checkout_url' => $session->url,
        ],
      ]);

      return [
        'payment_method' => 'stripe',
        'payment_status' => 'pending',
        'reference'      => $session->id,
        'redirect_url'   => $session->url,
      ];
    } catch (ApiErrorException $e) {
      Log::error('Stripe payment error', [
        'order_id' => $order->id,
        'customer_id' => $customer->id,
        'error' => $e->getMessage(),
      ]);

      $order->update([
        'payment_status' => PaymentStatus::Failed,
        'payment_meta' => array_merge($order->payment_meta ?? [], [
          'error' => $e->getMessage(),
        ]),
      ]);

      throw new \RuntimeException('Payment processing failed. Please try again.', 0, $e);
    }
  }
}
