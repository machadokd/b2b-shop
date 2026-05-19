<?php

namespace App\Jobs;

use App\Mail\OrderConfirmationMail;
use App\Models\Customer;
use App\Models\Order;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendOrderConfirmationEmail implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public readonly Order $order) {}

    public function handle(): void
    {
        /** @var Customer $customer */
        $customer = $this->order->customer;
        /** @var User $user */
        $user = $customer->user;
        Mail::to($user->email)->send(new OrderConfirmationMail($this->order));
    }
}
