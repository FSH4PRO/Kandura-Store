<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Foundation\Console\ClosureCommand;
use App\Services\Order\CancelExpiredOrdersService;

Artisan::command('inspire', function () {
    /** @var ClosureCommand $this */
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');


Artisan::command('orders:cancel-expired', function () {
    $service = app(CancelExpiredOrdersService::class);

    $count = $service->cancel();

    $this->info("Canceled {$count} expired orders.");
})->describe('Cancel unpaid orders older than 3 days');
