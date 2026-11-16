<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

/** Destinataires de l'annonce automatique d'ouverture d'un contrat. */
enum Audience: string implements HasLabel
{
    case AllMembers = 'tous';
    case CurrentSubscribers = 'en-cours';

    public function getLabel(): string
    {
        return match ($this) {
            self::AllMembers => 'Tous les adhérents à jour de cotisation',
            self::CurrentSubscribers => 'Les adhérents qui ont un contrat en cours avec ce producteur',
        };
    }
}
