<?php

namespace App\Filament\Resources\ContractOffers\Tables;

use App\Models\ContractOffer;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\DB;

class ContractOffersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('opens_at', 'desc')
            ->columns([
                TextColumn::make('producer.name')->label('Producteur')->searchable()->sortable(),
                TextColumn::make('title')->label('Contrat')->searchable(),
                TextColumn::make('starts_on')->label('Saison')
                    ->formatStateUsing(fn (ContractOffer $o) => $o->starts_on->translatedFormat('j M').' → '.$o->ends_on->translatedFormat('j M Y')),
                TextColumn::make('status')->label('Souscriptions')->badge()->state(fn (ContractOffer $o) => $o->status())
                    ->description(fn (ContractOffer $o) => 'jusqu\'au '.$o->closes_at->translatedFormat('j M à H\hi')),
                TextColumn::make('contracts_count')->label('Adhérents')->counts('contracts')->alignCenter()
                    ->formatStateUsing(fn ($state, ContractOffer $o) => $o->max_contracts ? "{$state} / {$o->max_contracts}" : $state),
                TextColumn::make('contracts_sum_total_cents')->label('Montant total')->sum('contracts', 'total_cents')->money('EUR', divideBy: 100, locale: 'fr'),
            ])
            ->recordActions([
                EditAction::make(),
                Action::make('dupliquer')->label('Dupliquer pour la période suivante')->icon('heroicon-o-document-duplicate')->color('gray')
                    ->modalDescription('Une copie non publiée est créée, avec toutes les dates décalées.')
                    ->schema([
                        Select::make('months')->label('Décaler de')->required()
                            ->options(['12' => 'Un an (contrat annuel)', '6' => 'Six mois', '3' => 'Trois mois (contrat trimestriel)'])
                            ->default(fn (ContractOffer $record) => (string) self::periodMonths($record)),
                    ])
                    ->action(function (ContractOffer $record, array $data) {
                        $months = (int) $data['months'];
                        DB::transaction(function () use ($record, $months) {
                            $copy = $record->replicate(['announced_at']);
                            $copy->title = $months === 12 ? preg_replace_callback('/\d{4}/', fn ($m) => $m[0] + 1, $record->title) : $record->title.' (suite)';
                            foreach (['starts_on', 'ends_on', 'opens_at', 'closes_at'] as $field) {
                                $copy->{$field} = $record->{$field}->copy()->addMonthsNoOverflow($months);
                            }
                            $copy->is_published = false;
                            $copy->save();
                            $copy->products()->sync($record->products()->pluck('products.id'));
                        });
                        Notification::make()->title('Contrat dupliqué : pensez à vérifier les dates puis à le publier.')->success()->send();
                    }),
            ]);
    }

    /** Durée de l'offre arrondie à 3, 6 ou 12 mois. */
    private static function periodMonths(ContractOffer $offer): int
    {
        $months = (int) round($offer->starts_on->diffInMonths($offer->ends_on->copy()->addDay()));

        return match (true) {
            $months <= 4 => 3,
            $months <= 8 => 6,
            default => 12,
        };
    }
}
