<?php

namespace App\Providers;

use App\Models\Customer;
use App\Models\Design;
use App\Models\Order;
use App\Models\Wallet;
use App\Observers\CustomerObserver;
use App\Observers\DesignObserver;
use App\Observers\OrderObserver;
use App\Observers\WalletObserver;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;


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

        // Security hardening: these were previously unset, so customer
        // Bearer tokens (created via createToken() in User\AuthController)
        // fell back to Passport's own defaults — access tokens valid for
        // 1 year, refresh tokens for 15 years, with no forced rotation.
        // Logout already revokes all of a customer's tokens server-side,
        // but a lost/leaked token would otherwise stay valid indefinitely.
        Passport::personalAccessTokensExpireIn(Date::now()->addDays(30));
        Passport::refreshTokensExpireIn(Date::now()->addDays(60));
        Passport::tokensExpireIn(Date::now()->addDays(30));
        if (App::environment('production')) {
            URL::forceScheme('https');
        }
    }
}
