<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum Participation: string implements HasLabel
{
    case Contract = 'contrat';
    case GroupPurchase = 'commandes-groupees';
    case Both = 'les-deux';

    public function getLabel(): string
    {
        return match ($this) {
            self::Contract => 'Contrat (paniers réguliers)',
            self::GroupPurchase => 'Commandes groupées',
            self::Both => 'Contrat et commandes groupées',
        };
    }
}
