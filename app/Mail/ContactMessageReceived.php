<?php

namespace App\Mail;

use App\Models\ContactMessage;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class ContactMessageReceived extends Mailable
{
    public function __construct(public ContactMessage $contactMessage, public bool $acknowledgement = false) {}

    public function envelope(): Envelope
    {
        $subject = config("amap.contact_subjects.{$this->contactMessage->subject}.label", 'Message');

        return new Envelope(
            subject: $this->acknowledgement ? 'Nous avons bien reçu votre message' : "[Site] {$subject} — {$this->contactMessage->first_name} {$this->contactMessage->last_name}",
            replyTo: $this->acknowledgement ? [] : [new Address($this->contactMessage->email, "{$this->contactMessage->first_name} {$this->contactMessage->last_name}")],
        );
    }

    public function content(): Content
    {
        return new Content(markdown: 'mail.contact-message');
    }
}
