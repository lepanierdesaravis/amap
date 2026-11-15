<?php

namespace App\Filament\Resources\Distributions;

use App\Filament\Resources\Distributions\Pages\CreateDistribution;
use App\Filament\Resources\Distributions\Pages\EditDistribution;
use App\Filament\Resources\Distributions\Pages\ListDistributions;
use App\Filament\Resources\Distributions\Schemas\DistributionForm;
use App\Filament\Resources\Distributions\Tables\DistributionsTable;
use App\Filament\Support\FrenchLabels;
use App\Models\Distribution;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class DistributionResource extends Resource
{
    use FrenchLabels;

    protected static ?string $model = Distribution::class;

    protected static ?string $modelLabel = 'distribution';

    protected static ?string $slug = 'distributions';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCalendarDays;

    protected static string|UnitEnum|null $navigationGroup = 'Distributions';

    public static function form(Schema $schema): Schema
    {
        return DistributionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DistributionsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListDistributions::route('/'),
            'create' => CreateDistribution::route('/ajouter'),
            'edit' => EditDistribution::route('/{record}/modifier'),
        ];
    }
}
