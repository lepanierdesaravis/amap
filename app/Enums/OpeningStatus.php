<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

/**
 * État d'une offre de contrat ou d'une commande groupée, calculé à partir
 * des dates d'ouverture et de clôture : aucune tâche planifiée n'est
 * nécessaire pour ouvrir ou fermer.
 */
enum OpeningStatus: string implements HasColor, HasLabel
{
    case Upcoming = 'a-venir';
    case Open = 'ouverte';
    case Closed = 'cloturee';

    public function getLabel(): string
    {
        return match ($this) {
            self::Upcoming => 'À venir',
            self::Open => 'Ouverte',
            self::Closed => 'Clôturée',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::Upcoming => 'info',
            self::Open => 'success',
            self::Closed => 'gray',
        };
    }
}
