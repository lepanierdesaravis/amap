<?php

namespace App\Filament\Resources\Installments;

use App\Filament\Resources\Installments\Pages\ListInstallments;
use App\Filament\Resources\Installments\Tables\InstallmentsTable;
use App\Filament\Support\FrenchLabels;
use App\Models\Installment;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

/** Outil de pointage des paiements (chèques et virements à l'ordre des producteurs). */
class InstallmentResource extends Resource
{
    use FrenchLabels;

    protected static ?string $model = Installment::class;

    protected static ?string $modelLabel = 'échéance';

    protected static ?string $slug = 'paiements';

    protected static ?string $navigationLabel = 'Pointage des paiements';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCheckBadge;

    protected static string|UnitEnum|null $navigationGroup = 'Producteurs et contrats';

    protected static ?int $navigationSort = 3;

    public static function table(Table $table): Table
    {
        return InstallmentsTable::configure($table);
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery()->with(['user', 'producer', 'payable']);

        return auth()->user()->isAdmin() ? $query : $query->whereHas('producer', fn ($q) => $q->where('referent_id', auth()->id()));
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getPages(): array
    {
        return [
            'index' => ListInstallments::route('/'),
        ];
    }
}
