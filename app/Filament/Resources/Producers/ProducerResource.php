<?php

namespace App\Filament\Resources\Producers;

use App\Filament\Resources\Producers\Pages\CreateProducer;
use App\Filament\Resources\Producers\Pages\EditProducer;
use App\Filament\Resources\Producers\Pages\ListProducers;
use App\Filament\Resources\Producers\RelationManagers\ProductsRelationManager;
use App\Filament\Resources\Producers\Schemas\ProducerForm;
use App\Filament\Resources\Producers\Tables\ProducersTable;
use App\Filament\Support\Fields;
use App\Filament\Support\FrenchLabels;
use App\Models\Producer;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class ProducerResource extends Resource
{
    use FrenchLabels;

    protected static ?string $model = Producer::class;

    protected static ?string $modelLabel = 'producteur';

    protected static ?string $slug = 'producteurs';

    protected static ?string $pluralModelLabel = 'producteurs';

    protected static ?string $recordTitleAttribute = 'name';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedHomeModern;

    protected static string|UnitEnum|null $navigationGroup = 'Producteurs et contrats';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return ProducerForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProducersTable::configure($table);
    }

    /** Un référent ne voit que les producteurs qu'il a créés. */
    public static function getEloquentQuery(): Builder
    {
        return Fields::scopeToReferent(parent::getEloquentQuery());
    }

    public static function getRelations(): array
    {
        return [
            ProductsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListProducers::route('/'),
            'create' => CreateProducer::route('/ajouter'),
            'edit' => EditProducer::route('/{record}/modifier'),
        ];
    }
}
