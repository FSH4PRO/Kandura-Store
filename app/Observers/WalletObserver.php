<?php

namespace App\Observers;

use App\Models\Wallet;
use App\Events\Wallet\WalletBalanceChanged;
use App\Events\Wallet\WalletBalanceDeducted;
use App\Events\Wallet\WalletBalanceIncreased;

class WalletObserver
{
  public function updated(Wallet $wallet)
  {
    if ($wallet->wasChanged('balance')) {
      $diff = $wallet->balance - $wallet->getOriginal('balance');

      if ($diff > 0) {
        event(new WalletBalanceIncreased($wallet, $diff));
      } else {
        event(new WalletBalanceDeducted($wallet, abs($diff)));
      }
    }
  }
}

