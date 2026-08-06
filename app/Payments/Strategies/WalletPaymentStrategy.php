<?php

namespace App\Payments\Strategies;

use App\DTO\Payments\PayOrderData;
use App\Enums\OrderStatus;
use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Wallet;
use App\Payments\Contracts\PaymentStrategy;
use Illuminate\Support\Facades\DB;

class WalletPaymentStrategy implements PaymentStrategy
{
  public function pay(Customer $customer, Order $order, PayOrderData $data): array
  {
    try {
      return DB::transaction(function () use ($customer, $order) {
        // Ensure wallet exists first, then lock it
        $wallet = Wallet::where('customer_id', $customer->id)
          ->lockForUpdate()
          ->first();

        if (! $wallet) {
          $wallet = Wallet::create([
            'customer_id' => $customer->id,
            'balance' => 0,
          ]);
          // Relock after creation
          $wallet = Wallet::where('customer_id', $customer->id)
            ->lockForUpdate()
            ->firstOrFail();
        }

        $amount = (float) $order->total;

        // Validate amount
        if ($amount < 0) {
          throw new \RuntimeException('Invalid order amount.');
        }

        // Check if wallet is active
        if (!$wallet->is_active) {
          throw new \RuntimeException('Wallet is deactivated. Please contact support.');
        }

        if (! $wallet->hasEnough($amount)) {
          throw new \RuntimeException('Insufficient wallet balance.');
        }

        // خصم
        $wallet->decrement('balance', $amount);

        // سجل حركة
        $wallet->transactions()->create([
          'type'   => 'debit',
          'amount' => $amount,
          'meta'   => ['order_id' => $order->id],
          'description' => 'payment for Order'.' '.$order->id,
        ]);

        // تحديث الطلب
        $order->update([
          'payment_method'    => PaymentMethod::Wallet,
          'payment_status'    => PaymentStatus::Paid,
          'status' => OrderStatus::Processing,
          'paid_at'           => now(),
          'payment_reference' => 'wallet:' . $wallet->id,
          'payment_meta'      => ['wallet_id' => $wallet->id],
        ]);

        return [
          'payment_method' => 'wallet',
          'payment_status' => 'paid',
          'redirect_url'   => null,
        ];
      });
    } catch (\RuntimeException $e) {
      // Mark order as failed outside transaction if balance is insufficient
      if (str_contains($e->getMessage(), 'Insufficient wallet balance')) {
        $wallet = Wallet::where('customer_id', $customer->id)->first();
        $order->update([
          'payment_status' => PaymentStatus::Failed,
          'payment_meta' => array_merge($order->payment_meta ?? [], [
            'failure_reason' => 'insufficient_balance',
            'required_amount' => (float) $order->total,
            'available_balance' => $wallet ? (float) $wallet->balance : 0,
          ]),
        ]);
      }
      throw $e;
    }
  }
}
