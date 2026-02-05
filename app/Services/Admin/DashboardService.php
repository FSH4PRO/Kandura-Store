<?php

namespace App\Services\Admin;

use App\Models\User;
use App\Models\Admin;
use App\Models\Order;
use App\Models\Wallet;
use App\Models\Address;
use App\Models\Customer;
use App\Models\WalletTransaction;
use App\Enums\OrderStatus;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class DashboardService
{
    public function getData(): array
    {
        return Cache::remember('admin_dashboard:data', 60, function () {
            $now = Carbon::now();

            // Users
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

            // Orders
            $totalOrders = Order::count();
            $pendingOrders = Order::where('status', OrderStatus::Pending)->count();
            $canceledOrders = Order::where('status', OrderStatus::Canceled)->count();

            // ⚠️ انتبه: أنت تعتبر Accepted = completed حاليا
            $completedOrders = Order::where('status', OrderStatus::Accepted)->count();

            $ordersThisMonth = Order::whereYear('created_at', $now->year)
                ->whereMonth('created_at', $now->month)
                ->count();

            // Wallets
            $totalWallets = Wallet::count();
            $activeWallets = Wallet::where('is_active', true)->count();
            $totalWalletBalance = (float) Wallet::sum('balance');

            $recentTransactions = WalletTransaction::with(['wallet.customer.user'])
                ->latest()
                ->take(5)
                ->get();

            // Growth charts (last 12 months)
            $userGrowth = $this->userGrowth();
            $orderGrowth = $this->orderGrowth();
            $walletGrowth = $this->walletGrowth();

            // Today stats
            $todayOrders = Order::whereDate('created_at', today())->count();
            $todayUsers = User::whereDate('created_at', today())
                ->whereHasMorph('usable', [Customer::class])
                ->count();

            $todayTransactions = WalletTransaction::whereDate('created_at', today())->count();

            return compact(
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
            );
        });
    }

    private function userGrowth(): array
    {
        $data = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $count = User::whereHasMorph('usable', [Customer::class])
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();

            $data[] = ['month' => $date->format('M Y'), 'count' => $count];
        }
        return $data;
    }

    private function orderGrowth(): array
    {
        $data = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $count = Order::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();

            $data[] = ['month' => $date->format('M Y'), 'count' => $count];
        }
        return $data;
    }
    private function walletGrowth(): array
    {
        $data = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $balance = WalletTransaction::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->sum('amount');

            $data[] = ['month' => $date->format('M Y'), 'balance' => (float) $balance];
        }
        return $data;
    }
}
