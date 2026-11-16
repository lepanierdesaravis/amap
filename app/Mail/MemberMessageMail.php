<?php

namespace App\Mail;

use App\Enums\MessageAudience;
use App\Models\MemberMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/** Message d'un adhérent ; « Répondre » écrit directement à l'expéditeur. */
class MemberMessageMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public MemberMessage $memberMessage) {}

    public function envelope(): Envelope
    {
        $prefix = match ($this->memberMessage->audience) {
            MessageAudience::Members => '[Entre adhérents]',
            MessageAudience::Referent => '[Référent '.$this->memberMessage->producer?->name.']',
            MessageAudience::Board => '[Bureau]',
        };
        $sender = $this->memberMessage->sender;

        return new Envelope(
            subject: "{$prefix} {$this->memberMessage->subject}",
            replyTo: [new Address($sender->email, $sender->name)],
        );
    }

    public function content(): Content
    {
        return new Content(markdown: 'mail.member-message');
    }
}
