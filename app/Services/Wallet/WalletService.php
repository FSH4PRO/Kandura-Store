<?php

namespace App\Services\Wallet;

use App\Models\Order;
use RuntimeException;
use App\Models\Wallet;
use App\Models\Customer;
use App\Models\WalletTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WalletService
{
    public function getOrCreate(Customer $customer): Wallet
    {
        return Wallet::firstOrCreate(
            ['customer_id' => $customer->id],
            ['balance' => 0, 'is_active' => true]
        );
    }

    public function credit(Wallet $wallet, float $amount, string $description = 'Top up', array $meta = []): WalletTransaction
    {
        if ($amount <= 0) throw new RuntimeException('Amount must be > 0');

        return DB::transaction(function () use ($wallet, $amount, $description, $meta) {
            $before = (float) $wallet->balance;
            $after  = $before + (float) $amount;

            $wallet->balance = $after;
            $wallet->save();

            return $wallet->transactions()->create([
                'type' => WalletTransaction::TYPE_CREDIT,
                'amount' => $amount,
                'description' => $description,
                'meta' => $meta,
            ]);
        });
    }

    public function debitForOrder(Customer $customer, Order $order, float $amount): WalletTransaction
    {
        if ($amount <= 0) throw new RuntimeException('Amount must be > 0');

        return DB::transaction(function () use ($customer, $order, $amount) {
            $wallet = Wallet::where('customer_id', $customer->id)->lockForUpdate()->first();
            if (! $wallet) throw new RuntimeException('Wallet not found.');

            $before = (float) $wallet->balance;
            if ($before < $amount) throw new RuntimeException('Insufficient wallet balance.');

            $after = $before - $amount;

            $wallet->balance = $after;
            $wallet->save();

            return $wallet->transactions()->create([
                'type' => WalletTransaction::TYPE_DEBIT,
                'amount' => $amount,
                'order_id' => $order->id,
                'description' => 'Order payment',
                'meta' => [
                    'order_id' => $order->id,
                ],
            ]);
        });
    }

    /**
     * Add credit to all active wallets
     */
    public function bulkCredit(float $amount, string $description = 'Bulk credit', array $meta = []): int
    {
        if ($amount <= 0) throw new RuntimeException('Amount must be > 0');

        $wallets = Wallet::where('is_active', true)->get();
        $count = 0;

        foreach ($wallets as $wallet) {
            try {
                $this->credit($wallet, $amount, $description, $meta);
                $count++;
            } catch (\Exception $e) {
                // Log error but continue with other wallets
                Log::error('Bulk credit failed for wallet', [
                    'wallet_id' => $wallet->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return $count;
    }

    /**
     * Activate a wallet
     */
    public function activate(Wallet $wallet): bool
    {
        $wallet->is_active = true;
        return $wallet->save();
    }

    /**
     * Deactivate a wallet
     */
    public function deactivate(Wallet $wallet): bool
    {
        $wallet->is_active = false;
        return $wallet->save();
    }
}
