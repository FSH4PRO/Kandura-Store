<?php

namespace App\Events\Wallet;

use App\Models\Wallet;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class WalletBalanceChanged
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Wallet $wallet,
        public float $oldBalance,
        public float $newBalance
    ) {}
}
