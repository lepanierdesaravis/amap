<?php

namespace App\Filament\Resources\ContractOffers\Tables;

use App\Models\ContractOffer;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
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
                TextColumn::make('contracts_count')->label('Adhérents')->counts('contracts')->alignCenter(),
                TextColumn::make('contracts_sum_total_cents')->label('Montant total')->sum('contracts', 'total_cents')->money('EUR', divideBy: 100, locale: 'fr'),
            ])
            ->recordActions([
                EditAction::make(),
                Action::make('dupliquer')->label('Dupliquer pour l\'année suivante')->icon('heroicon-o-document-duplicate')->color('gray')
                    ->requiresConfirmation()
                    ->modalDescription('Une copie non publiée est créée, avec toutes les dates décalées d\'un an.')
                    ->action(function (ContractOffer $record) {
                        DB::transaction(function () use ($record) {
                            $copy = $record->replicate();
                            $copy->title = preg_replace_callback('/\d{4}/', fn ($m) => $m[0] + 1, $record->title);
                            foreach (['starts_on', 'ends_on', 'opens_at', 'closes_at'] as $field) {
                                $copy->{$field} = $record->{$field}->copy()->addYear();
                            }
                            $copy->is_published = false;
                            $copy->save();
                            $copy->products()->sync($record->products()->pluck('products.id'));
                        });
                        Notification::make()->title('Contrat dupliqué : pensez à vérifier les dates puis à le publier.')->success()->send();
                    }),
            ]);
    }
}
