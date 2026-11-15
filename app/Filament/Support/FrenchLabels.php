<?php

namespace App\Filament\Support;

use Illuminate\Support\Str;

/** En français, seul le premier mot d'un titre prend une majuscule (« Offres de contrat »). */
trait FrenchLabels
{
    public static function getTitleCaseModelLabel(): string
    {
        return Str::ucfirst(static::getModelLabel());
    }

    public static function getTitleCasePluralModelLabel(): string
    {
        return Str::ucfirst(static::getPluralModelLabel());
    }
}
