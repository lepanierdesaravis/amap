<?php

namespace App\Filament\Resources\GroupPurchases\RelationManagers;

use App\Models\GroupOrder;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class OrdersRelationManager extends RelationManager
{
    protected static string $relationship = 'orders';

    protected static ?string $title = 'Commandes des adhérents';

    public function isReadOnly(): bool
    {
        return true;
    }

    public function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn ($query) => $query->with(['user', 'lines.item']))
            ->columns([
                TextColumn::make('user.last_name')->label('Adhérent')->sortable()->searchable()
                    ->formatStateUsing(fn (GroupOrder $o) => $o->user->sort_name),
                TextColumn::make('lines')->label('Articles')->listWithLineBreaks()
                    ->state(fn (GroupOrder $o) => $o->lines->map(fn ($l) => "{$l->quantity} × {$l->item->fullName()}")->all()),
                TextColumn::make('total_cents')->label('Total')->money('EUR', divideBy: 100, locale: 'fr')->sortable(),
                TextColumn::make('validated_at')->label('Le')->dateTime('j M H:i')->sortable(),
            ]);
    }
}
