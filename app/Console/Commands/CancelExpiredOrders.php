<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\Order\CancelExpiredOrdersService;

class CancelExpiredOrders extends Command
{
    protected $signature = 'orders:cancel-expired';
    protected $description = 'Cancel unpaid orders older than 3 days';

    public function __construct(
        protected CancelExpiredOrdersService $service
    ) {
        parent::__construct();
    }
    public function handle(): int
    {
        $count = $this->service->cancel();

        $this->info("Canceled {$count} expired orders.");

        return Command::SUCCESS;
    }
}
