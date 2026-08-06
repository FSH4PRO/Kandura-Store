<?php

namespace App\Services\Admin;

use App\Models\User;
use App\Models\Admin;
use App\Models\Order;
use App\Models\Design;
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

            // Bug fix: this previously counted `accepted` orders as
            // "completed" (the two are distinct statuses on the
            // OrderStatus enum — accepted orders are still awaiting
            // payment/fulfillment). Now correctly counts `completed`.
            $completedOrders = Order::where('status', OrderStatus::Completed)->count();

            $ordersThisMonth = Order::whereYear('created_at', $now->year)
                ->whereMonth('created_at', $now->month)
                ->count();

            // Design catalog — there is no "pending approval" workflow on
            // designs in the current schema (no status column), so this
            // reports overall catalog activity instead of a literal
            // "pending" count. Flag to product if an approval workflow is
            // actually wanted — it would need a new migration.
            $totalDesigns = Design::count();
            $designsThisMonth = Design::whereYear('created_at', $now->year)
                ->whereMonth('created_at', $now->month)
                ->count();

            // Wallets
            $totalWallets = Wallet::count();
            $activeWallets = Wallet::where('is_active', true)->count();
            $totalWalletBalance = (float) Wallet::sum('balance');

            $recentTransactions = WalletTransaction::with(['wallet.customer.user.media'])
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
                'totalDesigns',
                'designsThisMonth',
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

    /**
     * Bucket rows created within the last 12 months into a fixed set of
     * calendar-month slots. Extracted so each *Growth() method below can
     * run a single query instead of looping 12 separate COUNT/SUM queries
     * (36 queries total across the three charts, previously).
     *
     * @param  \Illuminate\Support\Collection<int, \Carbon\Carbon>  $createdAts
     * @param  callable(\Illuminate\Support\Collection):mixed  $reducer  Reduces the rows in a single month bucket to the chart value.
     */
    private function bucketByMonth($createdAts, callable $reducer): array
    {
        $buckets = $createdAts->groupBy(fn ($date) => $date->format('Y-m'));

        $data = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $key = $date->format('Y-m');
            $data[] = [
                'month' => $date->format('M Y'),
                'value' => $reducer($buckets->get($key, collect())),
            ];
        }

        return $data;
    }

    private function userGrowth(): array
    {
        $start = Carbon::now()->subMonths(11)->startOfMonth();

        $dates = User::query()
            ->whereHasMorph('usable', [Customer::class])
            ->where('created_at', '>=', $start)
            ->pluck('created_at');

        return collect($this->bucketByMonth($dates, fn ($rows) => $rows->count()))
            ->map(fn ($row) => ['month' => $row['month'], 'count' => $row['value']])
            ->all();
    }

    private function orderGrowth(): array
    {
        $start = Carbon::now()->subMonths(11)->startOfMonth();

        $dates = Order::where('created_at', '>=', $start)->pluck('created_at');

        return collect($this->bucketByMonth($dates, fn ($rows) => $rows->count()))
            ->map(fn ($row) => ['month' => $row['month'], 'count' => $row['value']])
            ->all();
    }

    private function walletGrowth(): array
    {
        $start = Carbon::now()->subMonths(11)->startOfMonth();

        $rows = WalletTransaction::where('created_at', '>=', $start)
            ->get(['created_at', 'amount']);

        $buckets = $rows->groupBy(fn ($row) => $row->created_at->format('Y-m'));

        $data = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $key = $date->format('Y-m');
            $data[] = [
                'month' => $date->format('M Y'),
                'balance' => (float) $buckets->get($key, collect())->sum('amount'),
            ];
        }

        return $data;
    }
}
