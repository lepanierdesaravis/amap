<?php

namespace App\Filament\Resources\ContractOffers\RelationManagers;

use App\Models\Contract;
use App\Services\SubscriberList;
use Filament\Actions\Action;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

/** Contrats souscrits par les adhérents (consultation). */
class ContractsRelationManager extends RelationManager
{
    protected static string $relationship = 'contracts';

    protected static ?string $title = 'Contrats souscrits';

    public function isReadOnly(): bool
    {
        return true;
    }

    public function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn ($query) => $query->with(['user', 'lines.product']))
            ->defaultSort('validated_at', 'desc')
            ->headerActions([
                Action::make('liste-pdf')->label('Liste des adhérents (PDF)')->icon('heroicon-o-document-text')->color('gray')
                    ->action(fn (SubscriberList $list) => $list->pdf($this->getOwnerRecord())),
                Action::make('liste-csv')->label('Tableur (CSV)')->icon('heroicon-o-table-cells')->color('gray')
                    ->action(fn (SubscriberList $list) => $list->csv($this->getOwnerRecord())),
            ])
            ->columns([
                TextColumn::make('user.last_name')->label('Adhérent')->sortable()->searchable()
                    ->formatStateUsing(fn (Contract $c) => $c->user->sort_name),
                TextColumn::make('lines')->label('Produits')->listWithLineBreaks()
                    ->state(fn (Contract $c) => $c->lines->map(fn ($l) => $l->is_amount && ! $l->distribution_ids
                        ? $l->product->name
                        : "{$l->quantity} × {$l->product->name} — {$l->frequency->shortLabel()} (".count($l->distribution_ids).' livraisons)')->all()),
                TextColumn::make('total_cents')->label('Total')->money('EUR', divideBy: 100, locale: 'fr')->sortable(),
                TextColumn::make('installments')->label('Règlements')
                    ->formatStateUsing(fn (Contract $c) => $c->installments.' × '.$c->payment_method->getLabel()),
                TextColumn::make('validated_at')->label('Validé le')->dateTime('j M Y H:i')->sortable(),
            ]);
    }
}
