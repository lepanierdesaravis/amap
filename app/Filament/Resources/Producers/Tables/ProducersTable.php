<?php

namespace App\Filament\Resources\Producers\Tables;

use App\Enums\Participation;
use App\Models\Producer;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ProducersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('name')
            ->columns([
                ImageColumn::make('cover_photo')->label('')->disk('public')->square()->imageSize(48),
                TextColumn::make('name')->label('Ferme')->searchable()->sortable()
                    ->description(fn (Producer $p) => $p->farmer_names),
                TextColumn::make('city')->label('Commune')->sortable()->toggleable(),
                TextColumn::make('participation')->label('Participation')->badge(),
                TextColumn::make('products_count')->label('Produits')->counts('products')->alignCenter(),
                TextColumn::make('referent.name')->label('Référent')->visible(fn () => auth()->user()->isAdmin()),
                IconColumn::make('is_published')->label('En ligne')->boolean()->alignCenter(),
            ])
            ->filters([
                SelectFilter::make('participation')->options(Participation::class),
            ])
            ->recordActions([
                Action::make('voir')->label('Voir la fiche')->icon('heroicon-o-eye')->color('gray')
                    ->url(fn (Producer $p) => route('producteurs.show', $p))->openUrlInNewTab()
                    ->visible(fn (Producer $p) => $p->is_published),
                EditAction::make(),
            ])
            ->emptyStateHeading('Aucun producteur')
            ->emptyStateDescription('Cliquez sur « Ajouter un producteur » pour créer votre première fiche.');
    }
}
