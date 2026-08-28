<?php

namespace App\Observers;

class DesignObserver
{
    public function created($design)
    {
        event(new \App\Events\DesignCreated($design));
    }                  
}
