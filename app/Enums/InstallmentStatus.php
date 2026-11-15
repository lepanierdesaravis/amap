<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum InstallmentStatus: string implements HasColor, HasLabel
{
    case Expected = 'attendu';
    case Received = 'recu';
    case Cashed = 'encaisse';

    public function getLabel(): string
    {
        return match ($this) {
            self::Expected => 'Attendu',
            self::Received => 'Reçu',
            self::Cashed => 'Encaissé',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::Expected => 'gray',
            self::Received => 'warning',
            self::Cashed => 'success',
        };
    }
}
