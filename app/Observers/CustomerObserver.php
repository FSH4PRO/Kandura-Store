<?php

namespace App\Observers;

use App\Models\Customer;
use App\Models\Wallet;

class CustomerObserver
{
  public function created(Customer $customer): void
  {
    Wallet::create([
      'customer_id' => $customer->id,
      'balance' => 0,
    ]);
  }
}
