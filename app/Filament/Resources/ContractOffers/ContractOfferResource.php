<?php

namespace App\Filament\Resources\ContractOffers;

use App\Filament\Resources\ContractOffers\Pages\CreateContractOffer;
use App\Filament\Resources\ContractOffers\Pages\EditContractOffer;
use App\Filament\Resources\ContractOffers\Pages\ListContractOffers;
use App\Filament\Resources\ContractOffers\RelationManagers\ContractsRelationManager;
use App\Filament\Resources\ContractOffers\Schemas\ContractOfferForm;
use App\Filament\Resources\ContractOffers\Tables\ContractOffersTable;
use App\Filament\Support\FrenchLabels;
use App\Models\ContractOffer;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class ContractOfferResource extends Resource
{
    use FrenchLabels;

    protected static ?string $model = ContractOffer::class;

    protected static ?string $modelLabel = 'offre de contrat';

    protected static ?string $slug = 'offres-de-contrat';

    protected static ?string $pluralModelLabel = 'offres de contrat';

    protected static ?string $navigationLabel = 'Offres de contrat';

    protected static ?string $recordTitleAttribute = 'title';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;

    protected static string|UnitEnum|null $navigationGroup = 'Producteurs et contrats';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return ContractOfferForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ContractOffersTable::configure($table);
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery()->with('producer');

        return auth()->user()->isAdmin() ? $query : $query->whereHas('producer', fn ($q) => $q->where('referent_id', auth()->id()));
    }

    public static function getRelations(): array
    {
        return [
            ContractsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListContractOffers::route('/'),
            'create' => CreateContractOffer::route('/ajouter'),
            'edit' => EditContractOffer::route('/{record}/modifier'),
        ];
    }
}
