<?php

namespace App\Support;

use Illuminate\Support\Facades\File;

/**
 * Banque d'icônes des produits (config/icones.php) : une icône de la banque et une couleur
 * distinguent chaque produit dans le calendrier, par exemple une chèvre noire et une chèvre blanche.
 */
final class Icons
{
    /** @var array<string, string> */
    private static array $svg = [];

    public static function exists(?string $key): bool
    {
        return $key !== null && array_key_exists($key, config('icones.icons'));
    }

    public static function label(?string $key): string
    {
        return config("icones.icons.$key.0", '');
    }

    /** Dessin SVG, coloré par la propriété CSS `color` du parent. */
    public static function svg(?string $key): string
    {
        $key = self::exists($key) ? $key : config('icones.default.0');

        return self::$svg[$key] ??= str_replace('<svg ', '<svg aria-hidden="true" focusable="false" width="100%" height="100%" ',
            trim(File::get(resource_path("icons/produits/$key.svg"))));
    }

    /** @return array{0: string, 1: string} couleur du dessin, couleur du fond */
    public static function colors(?string $color): array
    {
        $palette = config('icones.colors');
        [, $fg, $bg] = $palette[$color] ?? $palette[config('icones.default.1')];

        return [$fg, $bg];
    }

    /**
     * Pastille HTML (dessin coloré sur fond rond). Styles en ligne : elle s'affiche
     * aussi dans le back-office Filament, qui n'a pas les classes du thème.
     */
    public static function badge(?string $key, ?string $color, string $size = '2rem', string $class = '', bool $variable = false): string
    {
        [$fg, $bg] = self::colors($color);
        if ($variable) {
            [$fg, $bg] = ["var(--icone-fg, $fg)", "var(--icone-bg, $bg)"];
        }
        $style = 'display:inline-flex;flex-shrink:0;align-items:center;justify-content:center;border-radius:9999px;box-sizing:border-box;'
            ."width:$size;height:$size;padding:calc($size * .14);color:$fg;background:$bg";

        return '<span class="'.e($class).'" style="'.$style.'">'.self::svg($key).'</span>';
    }

    /**
     * Options groupées par catégorie, pour le sélecteur du back-office. Leurs couleurs viennent
     * des variables CSS de cssVariables() : l'icône choisie change de couleur avec la couleur.
     *
     * @return array<string, array<string, string>>
     */
    public static function groupedOptions(): array
    {
        $groups = [];
        foreach (config('icones.icons') as $key => [$label, $category]) {
            $groups[config("icones.categories.$category", $category)][$key] = self::badge($key, null, '1.75rem', variable: true).' <span style="margin-left:.4rem">'.e($label).'</span>';
        }

        return $groups;
    }

    public static function cssVariables(?string $color): string
    {
        [$fg, $bg] = self::colors($color);

        return "--icone-fg:$fg;--icone-bg:$bg";
    }

    /** @return array<string, string> */
    public static function colorOptions(): array
    {
        return collect(config('icones.colors'))->map(fn ($c, $key) => self::badge('panier', $key, '1.5rem').' <span style="margin-left:.4rem">'.e($c[0]).'</span>')->all();
    }

    public static function colorLabel(?string $color): string
    {
        return mb_strtolower(config("icones.colors.$color.0", ''));
    }
}
