<?php

namespace App\Mail;

use App\Models\Contract;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class ContractConfirmed extends Mailable
{
    public function __construct(public Contract $contract) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Votre contrat '.$this->contract->offer->producer->name.' est enregistré');
    }

    public function content(): Content
    {
        return new Content(markdown: 'mail.contract-confirmed');
    }
}
