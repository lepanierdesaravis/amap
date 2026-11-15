<?php

namespace App\Filament\Resources\GroupPurchases;

use App\Filament\Resources\GroupPurchases\Pages\CreateGroupPurchase;
use App\Filament\Resources\GroupPurchases\Pages\EditGroupPurchase;
use App\Filament\Resources\GroupPurchases\Pages\ListGroupPurchases;
use App\Filament\Resources\GroupPurchases\RelationManagers\OrdersRelationManager;
use App\Filament\Resources\GroupPurchases\Schemas\GroupPurchaseForm;
use App\Filament\Resources\GroupPurchases\Tables\GroupPurchasesTable;
use App\Filament\Support\Fields;
use App\Filament\Support\FrenchLabels;
use App\Models\GroupPurchase;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class GroupPurchaseResource extends Resource
{
    use FrenchLabels;

    protected static ?string $model = GroupPurchase::class;

    protected static ?string $modelLabel = 'commande groupée';

    protected static ?string $slug = 'commandes-groupees';

    protected static ?string $pluralModelLabel = 'commandes groupées';

    protected static ?string $navigationLabel = 'Commandes groupées';

    protected static ?string $recordTitleAttribute = 'title';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedShoppingBag;

    protected static string|UnitEnum|null $navigationGroup = 'Commandes groupées';

    public static function form(Schema $schema): Schema
    {
        return GroupPurchaseForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return GroupPurchasesTable::configure($table);
    }

    /** Un référent ne voit que les commandes groupées qu'il a créées. */
    public static function getEloquentQuery(): Builder
    {
        return Fields::scopeToReferent(parent::getEloquentQuery()->with(['producer', 'distribution']));
    }

    public static function getRelations(): array
    {
        return [
            OrdersRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListGroupPurchases::route('/'),
            'create' => CreateGroupPurchase::route('/nouvelle'),
            'edit' => EditGroupPurchase::route('/{record}/modifier'),
        ];
    }
}
