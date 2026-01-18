<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Address;
use App\Models\Admin;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use App\Enums\OrderStatus;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Analytics extends Controller
{
  public function index()
  {
    // Users Statistics
    $totalUsers = User::query()
      ->whereHasMorph('usable', [Customer::class])
      ->count();

    $totalActiveUsers = User::query()
      ->where('is_active', true)
      ->whereHasMorph('usable', [Customer::class])
      ->count();

    $totalAdmins = User::query()
      ->whereHasMorph('usable', [Admin::class])
      ->count();

    $totalAddresses = Address::count();

    // Orders Statistics
    $totalOrders = Order::count();
    $completedOrders = Order::where('status', OrderStatus::Accepted)->count();
    $canceledOrders = Order::where('status', OrderStatus::Canceled)->count();
    $pendingOrders = Order::where('status', OrderStatus::Pending)->count();

    // Orders this month
    $ordersThisMonth = Order::whereMonth('created_at', Carbon::now()->month)
      ->whereYear('created_at', Carbon::now()->year)
      ->count();

    // Wallets Statistics
    $totalWallets = Wallet::count();
    $activeWallets = Wallet::where('is_active', true)->count();
    $totalWalletBalance = Wallet::sum('balance');

    // Recent Transactions
    $recentTransactions = WalletTransaction::with(['wallet.customer.user'])
      ->latest()
      ->take(5)
      ->get();

    // Growth Data (Last 12 months)
    $userGrowth = $this->getUserGrowthData();
    $orderGrowth = $this->getOrderGrowthData();
    $walletGrowth = $this->getWalletGrowthData();

    // Today's Statistics
    $todayOrders = Order::whereDate('created_at', today())->count();
    $todayUsers = User::whereDate('created_at', today())
      ->whereHasMorph('usable', [Customer::class])
      ->count();
    $todayTransactions = WalletTransaction::whereDate('created_at', today())->count();

    return view('content.dashboard.dashboards-analytics', compact(
      'totalUsers',
      'totalActiveUsers',
      'totalAdmins',
      'totalAddresses',
      'totalOrders',
      'completedOrders',
      'canceledOrders',
      'pendingOrders',
      'ordersThisMonth',
      'totalWallets',
      'activeWallets',
      'totalWalletBalance',
      'recentTransactions',
      'userGrowth',
      'orderGrowth',
      'walletGrowth',
      'todayOrders',
      'todayUsers',
      'todayTransactions'
    ));
  }

  private function getUserGrowthData()
  {
    $data = [];
    for ($i = 11; $i >= 0; $i--) {
      $date = Carbon::now()->subMonths($i);
      $count = User::whereHasMorph('usable', [Customer::class])
        ->whereYear('created_at', $date->year)
        ->whereMonth('created_at', $date->month)
        ->count();
      $data[] = [
        'month' => $date->format('M Y'),
        'count' => $count
      ];
    }
    return $data;
  }

  private function getOrderGrowthData()
  {
    $data = [];
    for ($i = 11; $i >= 0; $i--) {
      $date = Carbon::now()->subMonths($i);
      $count = Order::whereYear('created_at', $date->year)
        ->whereMonth('created_at', $date->month)
        ->count();
      $data[] = [
        'month' => $date->format('M Y'),
        'count' => $count
      ];
    }
    return $data;
  }

  private function getWalletGrowthData()
  {
    $data = [];
    for ($i = 11; $i >= 0; $i--) {
      $date = Carbon::now()->subMonths($i);
      $balance = WalletTransaction::whereYear('created_at', $date->year)
        ->whereMonth('created_at', $date->month)
        ->sum('amount');
      $data[] = [
        'month' => $date->format('M Y'),
        'balance' => (float) $balance
      ];
    }
    return $data;
  }
}
