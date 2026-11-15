<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum DistributionStatus: string implements HasColor, HasLabel
{
    case Planned = 'prevue';
    case Closed = 'cloturee';
    case SummariesSent = 'recaps-envoyes';

    public function getLabel(): string
    {
        return match ($this) {
            self::Planned => 'Prévue',
            self::Closed => 'Clôturée',
            self::SummariesSent => 'Récapitulatifs envoyés',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::Planned => 'gray',
            self::Closed => 'warning',
            self::SummariesSent => 'success',
        };
    }
}
