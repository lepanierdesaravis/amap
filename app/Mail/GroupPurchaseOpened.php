<?php

namespace App\Mail;

use App\Models\GroupPurchase;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class GroupPurchaseOpened extends Mailable
{
    public function __construct(public GroupPurchase $purchase) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Commande groupée ouverte : '.$this->purchase->title);
    }

    public function content(): Content
    {
        return new Content(markdown: 'mail.group-purchase-opened');
    }
}
