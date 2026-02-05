<?php

namespace App\Http\Controllers\Webhook;

use Stripe\Webhook;
use App\Models\Order;
use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\Exception\UnexpectedValueException;
use Stripe\Exception\SignatureVerificationException;

class WebhookController
{
    public function handle(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $secret = config('stripe.webhook.secret');

        try {
            $event = Webhook::constructEvent($payload, $sigHeader, $secret);
        } catch (UnexpectedValueException $e) {
            Log::warning('Stripe webhook invalid payload', ['err' => $e->getMessage()]);
            return response('Invalid payload', 400);
        } catch (SignatureVerificationException $e) {
            Log::warning('Stripe webhook invalid signature', ['err' => $e->getMessage()]);
            return response('Invalid signature', 400);
        }

        Log::info('Stripe webhook received', ['type' => $event->type]);

        if ($event->type === 'checkout.session.completed') {
            $session = $event->data->object;

            $orderId = $session->metadata->order_id ?? null;

            if (! $orderId) {
                Log::warning('Stripe webhook: missing order_id', ['session_id' => $session->id ?? null]);
                return response('Missing order_id', 200);
            }

            $order = Order::find($orderId);

            if (! $order) {
                Log::warning('Stripe webhook: order not found', ['order_id' => $orderId]);
                return response('Order not found', 200);
            }

            // Only update if payment was successful
            if ($session->payment_status === 'paid' && $order->payment_status !== PaymentStatus::Paid) {
                $order->update([
                    'payment_status' => PaymentStatus::Paid,
                    'paid_at' => now(),
                    'payment_reference' => $session->id ?? null,
                    'payment_meta' => array_merge($order->payment_meta ?? [], [
                        'stripe_session_id' => $session->id ?? null,
                        'stripe_payment_intent_id' => $session->payment_intent ?? null,
                    ]),
                ]);

                // Perhaps also update order status if needed
                if ($order->status === OrderStatus::Accepted) {
                    $order->update(['status' => OrderStatus::Processing]);
                }
            } elseif ($session->payment_status === 'unpaid') {
                // Payment was not completed
                $order->update([
                    'payment_status' => PaymentStatus::Failed,
                    'payment_meta' => array_merge($order->payment_meta ?? [], [
                        'stripe_session_id' => $session->id ?? null,
                        'payment_failed' => true,
                    ]),
                ]);
            }
        } elseif ($event->type === 'checkout.session.async_payment_failed') {
            $session = $event->data->object;
            $orderId = $session->metadata->order_id ?? null;

            if ($orderId) {
                $order = Order::find($orderId);
                if ($order && $order->payment_status !== PaymentStatus::Paid) {
                    $order->update([
                        'payment_status' => PaymentStatus::Failed,
                        'payment_meta' => array_merge($order->payment_meta ?? [], [
                            'stripe_session_id' => $session->id ?? null,
                            'payment_failed' => true,
                            'failure_reason' => 'async_payment_failed',
                        ]),
                    ]);
                }
            }
        }

        return response('OK', 200);
    }
}
