<?php

namespace App\Providers;

use App\Events\DesignCreated;
use Illuminate\Support\ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        DesignCreated::class => [
            \App\Listeners\Admin\NotifyAdminsDesignCreated::class,
        ],

        \App\Events\OrderCreated::class => [
            \App\Listeners\Admin\NotifyAdminsOrderCreated::class,
            \App\Listeners\User\NotifyDesignerOrderCreated::class, // حسب متطلباتك
        ],

        \App\Events\OrderStatusChanged::class => [
            \App\Listeners\User\NotifyUserOrderStatusChanged::class,
        ],


        \Illuminate\Notifications\Events\NotificationFailed::class => [
            \App\Listeners\HandleFcmNotificationFailed::class,
        ],
    ];


    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
