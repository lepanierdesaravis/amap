<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum PaymentMethod: string implements HasLabel
{
    case Cheque = 'cheque';
    case Transfer = 'virement';
    case HelloAsso = 'helloasso';
    case OnSite = 'sur-place';

    public function getLabel(): string
    {
        return match ($this) {
            self::Cheque => 'Chèque',
            self::Transfer => 'Virement',
            self::HelloAsso => 'En ligne (HelloAsso)',
            self::OnSite => 'Sur place, à la distribution',
        };
    }
}
