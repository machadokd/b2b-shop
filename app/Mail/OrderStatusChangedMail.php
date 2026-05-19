<?php

namespace App\Mail;

use App\Enums\OrderStatus;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderStatusChangedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly Order $order,
        public readonly OrderStatus $previousStatus,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: "Encomenda #{$this->order->id} — Estado Atualizado");
    }

    public function content(): Content
    {
        return new Content(view: 'emails.order-status-changed');
    }
}
