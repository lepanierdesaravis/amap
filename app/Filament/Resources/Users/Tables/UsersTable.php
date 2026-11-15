<?php

namespace App\Filament\Resources\Users\Tables;

use App\Enums\Role;
use App\Models\User;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('last_name')
            ->modifyQueryUsing(fn ($query) => $query->with(['roles', 'memberships']))
            ->columns([
                TextColumn::make('last_name')->label('Nom')->sortable()->searchable(['first_name', 'last_name', 'email'])
                    ->formatStateUsing(fn (User $u) => $u->sort_name)->description(fn (User $u) => $u->email),
                TextColumn::make('phone')->label('Téléphone')->toggleable(),
                TextColumn::make('roles.name')->label('Rôles')->badge()
                    ->formatStateUsing(fn (string $state) => Role::from($state)->getLabel()),
                IconColumn::make('adhesion')->label('Adhésion '.now()->year)->boolean()->alignCenter()
                    ->state(fn (User $u) => $u->memberships->where('year', now()->year)->whereNotNull('paid_at')->isNotEmpty()),
                IconColumn::make('adhesion_suivante')->label('Adhésion '.(now()->year + 1))->boolean()->alignCenter()
                    ->state(fn (User $u) => $u->memberships->where('year', now()->year + 1)->whereNotNull('paid_at')->isNotEmpty()),
            ])
            ->filters([
                SelectFilter::make('roles')->label('Rôle')->relationship('roles', 'name')
                    ->getOptionLabelFromRecordUsing(fn ($record) => Role::from($record->name)->getLabel()),
            ])
            ->recordActions([EditAction::make()]);
    }
}
