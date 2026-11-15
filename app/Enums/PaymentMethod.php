<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum PaymentMethod: string implements HasLabel
{
    case Cheque = 'cheque';
    case Transfer = 'virement';
    case HelloAsso = 'helloasso';

    public function getLabel(): string
    {
        return match ($this) {
            self::Cheque => 'Chèque',
            self::Transfer => 'Virement',
            self::HelloAsso => 'En ligne (HelloAsso)',
        };
    }
}
