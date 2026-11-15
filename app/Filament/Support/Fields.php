<?php

namespace App\Filament\Support;

use App\Models\Distribution;
use App\Models\Producer;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;

/** Champs réutilisés dans plusieurs formulaires du back-office. */
final class Fields
{
    /** Montant saisi en euros, stocké en centimes. */
    public static function money(string $name, string $label = 'Prix'): TextInput
    {
        return TextInput::make($name)
            ->label($label)
            ->suffix('€')
            ->inputMode('decimal')
            ->required()
            ->formatStateUsing(fn ($state) => $state === null ? null : number_format($state / 100, 2, ',', ''))
            ->dehydrateStateUsing(fn ($state) => (int) round((float) str_replace([',', ' '], ['.', ''], (string) $state) * 100))
            ->rule('regex:/^\d+([.,]\d{1,2})?$/')
            ->validationMessages(['regex' => 'Indiquez un montant, par exemple 12,50.']);
    }

    /** Liste des producteurs : tous pour l'administrateur, les siens pour un référent. */
    public static function producer(): Select
    {
        return Select::make('producer_id')
            ->label('Producteur')
            ->relationship('producer', 'name', fn (Builder $query) => self::scopeToReferent($query))
            ->searchable()
            ->preload()
            ->required();
    }

    public static function distribution(string $name = 'distribution_id', string $label = 'Distribution'): Select
    {
        return Select::make($name)
            ->label($label)
            ->options(fn () => Distribution::upcoming()->get()->mapWithKeys(fn (Distribution $d) => [$d->id => $d->label()]))
            ->searchable()
            ->required();
    }

    /** @param  Builder<Producer>  $query */
    public static function scopeToReferent(Builder $query, string $column = 'referent_id'): Builder
    {
        $user = auth()->user();

        return $user?->isAdmin() ? $query : $query->where($column, $user?->id);
    }
}
