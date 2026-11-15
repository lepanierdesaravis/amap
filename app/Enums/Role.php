<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum Role: string implements HasLabel
{
    case Admin = 'admin';
    case Referent = 'referent';
    case Producer = 'producteur';
    case Member = 'adherent';

    public function getLabel(): string
    {
        return match ($this) {
            self::Admin => 'Administrateur',
            self::Referent => 'Référent',
            self::Producer => 'Producteur',
            self::Member => 'Adhérent',
        };
    }
}
