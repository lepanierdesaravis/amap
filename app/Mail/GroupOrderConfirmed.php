<?php

namespace App\Mail;

use App\Models\GroupOrder;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class GroupOrderConfirmed extends Mailable
{
    public function __construct(public GroupOrder $order) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Votre commande « '.$this->order->groupPurchase->title.' » est enregistrée');
    }

    public function content(): Content
    {
        return new Content(markdown: 'mail.group-order-confirmed');
    }
}
