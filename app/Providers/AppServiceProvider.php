<?php

namespace App\Providers;

use App\Models\Order;
use App\Models\Design;
use App\Models\Wallet;
use App\Models\Customer;
use App\Observers\OrderObserver;
use App\Observers\WalletObserver;
use App\Observers\CustomerObserver;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\ServiceProvider;
use App\Observers\DesignObserver;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $locale = Session::get('app_locale', config('app.locale'));
        App::setLocale($locale);

        Customer::observe(CustomerObserver::class);
        Order::observe(OrderObserver::class);
        Wallet::observe(WalletObserver::class);
        Design::observe(DesignObserver::class);
    }
}
