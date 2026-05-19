<?php

namespace App\Listeners;

use App\Events\OrderPlaced;
use App\Jobs\UpdateProductStock;

class UpdateProductStockListener
{
    public function handle(OrderPlaced $event): void
    {
        UpdateProductStock::dispatch($event->order);
    }
}
