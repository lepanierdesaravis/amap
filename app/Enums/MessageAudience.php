<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

/** À qui un adhérent écrit depuis « Échanges entre adhérents ». */
enum MessageAudience: string implements HasLabel
{
    case Members = 'adherents';
    case Referent = 'referent';
    case Board = 'bureau';

    public function getLabel(): string
    {
        return match ($this) {
            self::Members => 'Tous les adhérents',
            self::Referent => 'Le référent d\'un producteur',
            self::Board => 'Le bureau de l\'association',
        };
    }
}
