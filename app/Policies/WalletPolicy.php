<?php

namespace App\Policies;

use App\Models\Wallet;
use App\Models\Admin;

class WalletPolicy
{
    /**
     * Determine whether the admin can view any wallets.
     */
    public function viewAny(Admin $admin): bool
    {
        return  $admin->can('wallets.view');
    }

    /**
     * Determine whether the admin can view a specific wallet.
     */
    public function view(Admin $admin, Wallet $wallet): bool
    {
        return  $admin->can('wallets.view');
    }

    /**
     * Determine whether the admin can add credit to a wallet.
     */
    public function addCredit(Admin $admin, Wallet $wallet): bool
    {
        return $admin->can('wallets.add');
    }

    /**
     * Determine whether the admin can activate/deactivate wallets.
     */
    public function manageStatus(Admin $admin, Wallet $wallet): bool
    {
        return $admin->can('wallets.view');
    }

    /**
     * Determine whether the admin can add credit to all wallets.
     */
    public function bulkAddCredit(Admin $admin): bool
    {
        return $admin->can('wallets.add');
    }
}
