<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

/**
 * Fréquence de livraison d'un produit sous contrat.
 *
 * Les adhérents ne voient jamais de « semaines paires / impaires » :
 * le calendrier affiche directement les dates retenues.
 */
enum Frequency: string implements HasLabel
{
    case Every = 'toutes';
    case Alternate = 'une-sur-deux';
    case Custom = 'dates-choisies';

    public function getLabel(): string
    {
        return match ($this) {
            self::Every => 'À chaque distribution',
            self::Alternate => 'Une distribution sur deux',
            self::Custom => 'Je choisis mes dates',
        };
    }

    public function shortLabel(): string
    {
        return match ($this) {
            self::Every => 'Toutes',
            self::Alternate => '1 sur 2',
            self::Custom => 'Au choix',
        };
    }
}
