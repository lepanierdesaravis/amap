<?php

namespace App\Mail;

use App\Models\GroupPurchase;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class GroupPurchaseOpened extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public GroupPurchase $purchase) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: ($this->purchase->contract_offer_id ? 'Commandes ouvertes : ' : 'Commande groupée ouverte : ').$this->purchase->title);
    }

    public function content(): Content
    {
        return new Content(markdown: 'mail.group-purchase-opened');
    }
}
