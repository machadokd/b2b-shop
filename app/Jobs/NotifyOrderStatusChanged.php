<?php

namespace App\Jobs;

use App\Enums\OrderStatus;
use App\Mail\OrderStatusChangedMail;
use App\Models\Customer;
use App\Models\Order;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class NotifyOrderStatusChanged implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly Order $order,
        public readonly OrderStatus $previousStatus,
    ) {}

    public function handle(): void
    {
        /** @var Customer $customer */
        $customer = $this->order->customer;
        /** @var User $user */
        $user = $customer->user;
        Mail::to($user->email)->send(new OrderStatusChangedMail($this->order, $this->previousStatus));
    }
}
