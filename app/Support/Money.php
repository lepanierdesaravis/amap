<?php

namespace App\Support;

final class Money
{
    public static function format(int $cents): string
    {
        return number_format($cents / 100, 2, ',', "\u{202F}").' €';
    }

    /**
     * Répartit un montant en n échéances. Les centimes restants vont sur la dernière,
     * comme sur les contrats papier (262,51 / 262,51 / 262,52).
     *
     * @return list<int>
     */
    public static function split(int $cents, int $parts): array
    {
        $base = intdiv($cents, $parts);
        $amounts = array_fill(0, $parts, $base);
        $amounts[$parts - 1] += $cents - $base * $parts;

        return $amounts;
    }
}
