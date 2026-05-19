<?php

namespace App\Listeners;

use App\Events\OrderStatusChanged;
use App\Jobs\NotifyOrderStatusChanged;

class NotifyOrderStatusChangedListener
{
    public function handle(OrderStatusChanged $event): void
    {
        NotifyOrderStatusChanged::dispatch($event->order, $event->previousStatus);
    }
}
