<?php

namespace App\Listeners;

use App\Events\OrderPlaced;
use App\Events\OrderStatusChanged;
use App\Jobs\AuditLogJob;

class AuditOrderEventListener
{
    public function handleOrderPlaced(OrderPlaced $event): void
    {
        AuditLogJob::dispatch('order.placed', [
            'order_id' => $event->order->id,
            'customer_id' => $event->order->customer_id,
            'total' => $event->order->total,
        ]);
    }

    public function handleOrderStatusChanged(OrderStatusChanged $event): void
    {
        AuditLogJob::dispatch('order.status_changed', [
            'order_id' => $event->order->id,
            'from' => $event->previousStatus->value,
            'to' => $event->order->status->value,
        ]);
    }
}
