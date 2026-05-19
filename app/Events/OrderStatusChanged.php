<?php

namespace App\Events;

use App\Enums\OrderStatus;
use App\Models\Order;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderStatusChanged implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public readonly Order $order,
        public readonly OrderStatus $previousStatus,
    ) {}

    public function broadcastOn(): PrivateChannel
    {
        return new PrivateChannel("orders.{$this->order->id}");
    }

    public function broadcastWith(): array
    {
        return [
            'status' => $this->order->status->value,
            'status_label' => $this->order->status->label(),
            'badge_class' => $this->order->status->badgeClass(),
            'previous_status' => $this->previousStatus->value,
            'previous_status_label' => $this->previousStatus->label(),
        ];
    }
}
