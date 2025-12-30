<?php

namespace App\Providers;

use App\Models\User;
use App\Models\Order;
use App\Models\Design;
use App\Models\Address;
use App\Models\Wallet;
use App\Policies\UserPolicy;
use App\Policies\OrderPolicy;
use App\Policies\DesignPolicy;
use App\Policies\WalletPolicy;
use Laravel\Passport\Passport;
use App\Policies\AddressPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        User::class => UserPolicy::class,
        Address::class => AddressPolicy::class,
        Design::class => DesignPolicy::class,
        Order::class => OrderPolicy::class,
        Wallet::class => WalletPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}
