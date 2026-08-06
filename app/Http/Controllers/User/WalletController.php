<?php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Services\Wallet\WalletService;

class WalletController extends Controller
{
  public function show(WalletService $wallets)
  {
    $customer = auth('customer')->user();

    $wallet = $wallets->getOrCreate($customer);

    return $this->success([
      'id' => $wallet->id,
      'balance' => (float)$wallet->balance,
      'currency' => $wallet->currency,
    ], __('messages.wallet_details'));
  }

  public function transactions(WalletService $wallets)
  {
    $customer = auth('customer')->user();
    $wallet = $wallets->getOrCreate($customer);

    $tx = $wallet->transactions()->latest()->paginate(15);

    return $this->success($tx, __('messages.wallet_transactions'));
  }
}