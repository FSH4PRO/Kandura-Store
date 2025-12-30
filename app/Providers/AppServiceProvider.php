<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use App\Models\Customer;
use App\Observers\CustomerObserver;


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
    }
}
