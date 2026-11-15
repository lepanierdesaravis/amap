<?php

namespace App\Filament\Resources\GroupPurchases\Schemas;

use App\Filament\Support\Fields;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

/** Formulaire « Nouvelle commande groupée ». */
class GroupPurchaseForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(3)
            ->components([
                Section::make('La commande')
                    ->columnSpan(2)
                    ->columns(2)
                    ->schema([
                        TextInput::make('title')->label('Titre')->placeholder('Agrumes de Sicile – janvier')->required()->columnSpanFull(),
                        Fields::producer(),
                        Fields::distribution('distribution_id', 'Livraison à la distribution du'),
                        Textarea::make('description')->label('Présentation')->rows(3)->columnSpanFull(),
                        Textarea::make('payment_info')->label('Paiement')
                            ->placeholder('Chèque à l\'ordre de … à remettre à la distribution')->rows(2)->columnSpanFull(),
                    ]),

                Section::make('Dates')
                    ->description('Ouverture et fermeture automatiques.')
                    ->columnSpan(1)
                    ->schema([
                        DateTimePicker::make('opens_at')->label('Ouverture')->seconds(false)->default(now()->startOfHour())->required(),
                        DateTimePicker::make('closes_at')->label('Clôture')->seconds(false)->required()->after('opens_at'),
                        Toggle::make('announce_by_email')->label('Annoncer par e-mail aux adhérents')->default(true),
                        FileUpload::make('photo')->label('Photo')->image()->disk('public')->directory('commandes-groupees')->maxSize(8192),
                    ]),

                Section::make('Articles')
                    ->columnSpanFull()
                    ->schema([
                        Repeater::make('items')->hiddenLabel()
                            ->relationship()
                            ->orderColumn('sort')
                            ->table([
                                Repeater\TableColumn::make('Article'),
                                Repeater\TableColumn::make('Conditionnement'),
                                Repeater\TableColumn::make('Prix'),
                                Repeater\TableColumn::make('Maximum par adhérent'),
                            ])
                            ->schema([
                                TextInput::make('name')->label('Article')->required(),
                                TextInput::make('packaging')->label('Conditionnement')->placeholder('1 kg'),
                                Fields::money('price_cents'),
                                TextInput::make('max_per_member')->label('Maximum')->numeric()->minValue(1),
                            ])
                            ->addActionLabel('Ajouter un article')
                            ->minItems(1)
                            ->defaultItems(1),
                    ]),
            ]);
    }
}
