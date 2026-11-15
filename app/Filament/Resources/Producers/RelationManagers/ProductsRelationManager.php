<?php

namespace App\Filament\Resources\Producers\RelationManagers;

use App\Filament\Support\Fields;
use App\Models\Product;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ProductsRelationManager extends RelationManager
{
    protected static string $relationship = 'products';

    protected static ?string $title = 'Produits';

    protected static ?string $modelLabel = 'produit';

    public const ICONS = [
        '🥕' => '🥕 Légumes', '🍎' => '🍎 Fruits', '🧀' => '🧀 Fromage', '🥛' => '🥛 Lait',
        '🥚' => '🥚 Œufs', '🥩' => '🥩 Viande', '🍞' => '🍞 Pain', '🌾' => '🌾 Farine',
        '🍷' => '🍷 Vin', '🧃' => '🧃 Jus', '🍯' => '🍯 Miel / confiture', '🫒' => '🫒 Huile',
    ];

    public function form(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                TextInput::make('name')->label('Nom')->required()->maxLength(120)->columnSpanFull(),
                Fields::money('price_cents', 'Prix par livraison'),
                TextInput::make('unit')->label('Unité')->placeholder('panier, pièce, kg, litre…')->required()->default('panier'),
                Select::make('icon')->label('Icône dans le calendrier')->options(self::ICONS),
                TextInput::make('sort')->label('Ordre d\'affichage')->numeric()->default(0),
                Textarea::make('description')->label('Description')->rows(2)->columnSpanFull(),
                Toggle::make('is_active')->label('Proposé actuellement')->default(true),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->reorderable('sort')
            ->defaultSort('sort')
            ->columns([
                TextColumn::make('icon')->label(''),
                TextColumn::make('name')->label('Produit')->searchable()->description(fn (Product $p) => $p->description),
                TextColumn::make('price_cents')->label('Prix')->money('EUR', divideBy: 100, locale: 'fr')
                    ->description(fn (Product $p) => 'par '.$p->unit),
                IconColumn::make('is_active')->label('Proposé')->boolean(),
            ])
            ->headerActions([
                CreateAction::make()->label('Ajouter un produit'),
            ])
            ->recordActions([
                EditAction::make(),
                Action::make('dupliquer')->label('Dupliquer')->icon('heroicon-o-document-duplicate')->color('gray')
                    ->action(fn (Product $record) => $record->replicate()->fill(['name' => $record->name.' (copie)'])->save()),
                DeleteAction::make(),
            ]);
    }
}
