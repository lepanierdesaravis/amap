<?php

namespace App\Mail;

use App\Models\ContractOffer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContractOfferOpened extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public ContractOffer $offer) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Souscriptions ouvertes : '.$this->offer->producer->name.' – '.$this->offer->title);
    }

    public function content(): Content
    {
        return new Content(markdown: 'mail.contract-offer-opened');
    }
}
