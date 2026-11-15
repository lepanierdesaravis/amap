<?php

namespace App\Filament\Resources\Pages\Tables;

use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PagesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->label('Page'),
                TextColumn::make('updated_at')->label('Modifiée le')->dateTime('j M Y H:i'),
            ])
            ->recordActions([EditAction::make()]);
    }
}
