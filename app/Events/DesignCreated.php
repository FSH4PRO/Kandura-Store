<?php

namespace App\Events;

use App\Models\Design;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DesignCreated
{
    use Dispatchable, SerializesModels;

    public function __construct(public Design $design) {}
}
