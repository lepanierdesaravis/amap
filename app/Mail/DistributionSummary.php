<?php

namespace App\Mail;

use App\Models\Distribution;
use App\Models\Producer;
use App\Services\DistributionDocuments;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class DistributionSummary extends Mailable
{
    public function __construct(public Distribution $distribution, public Producer $producer) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Récapitulatif de la distribution du '.$this->distribution->date->translatedFormat('j F Y'));
    }

    public function content(): Content
    {
        return new Content(markdown: 'mail.distribution-summary');
    }

    public function attachments(): array
    {
        $documents = app(DistributionDocuments::class);

        return [
            Attachment::fromData(
                fn () => $documents->summaryPdf($this->distribution, $this->producer)->output(),
                $documents->filename('recapitulatif', $this->distribution, $this->producer),
            )->withMime('application/pdf'),
        ];
    }
}
