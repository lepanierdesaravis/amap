<?php

namespace App\Filament\Resources\ContractOffers\Schemas;

use App\Enums\Frequency;
use App\Filament\Support\Fields;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;

class ContractOfferForm
{
    public const MONTHS = [
        1 => 'janv.', 2 => 'févr.', 3 => 'mars', 4 => 'avr.', 5 => 'mai', 6 => 'juin',
        7 => 'juil.', 8 => 'août', 9 => 'sept.', 10 => 'oct.', 11 => 'nov.', 12 => 'déc.',
    ];

    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(3)
            ->components([
                Section::make('Le contrat')
                    ->columnSpan(2)
                    ->columns(2)
                    ->schema([
                        Fields::producer()->live(),
                        TextInput::make('title')->label('Titre')->placeholder('Paniers légumes et fruits 2027')->required(),
                        DatePicker::make('starts_on')->label('Première livraison possible')->required(),
                        DatePicker::make('ends_on')->label('Dernière livraison possible')->required()->afterOrEqual('starts_on'),
                        CheckboxList::make('products')->label('Produits proposés')
                            ->helperText('Les produits se gèrent dans la fiche du producteur (onglet « Produits »).')
                            ->relationship('products', 'name', fn (Builder $query, Get $get) => $query->where('producer_id', $get('producer_id'))->where('is_active', true))
                            ->columns(2)->columnSpanFull()->required(),
                    ]),

                Section::make('Souscriptions')
                    ->description('Le site ouvre et ferme les souscriptions tout seul.')
                    ->columnSpan(1)
                    ->schema([
                        DateTimePicker::make('opens_at')->label('Ouverture')->seconds(false)->required(),
                        DateTimePicker::make('closes_at')->label('Clôture')->seconds(false)->required()->after('opens_at'),
                        Toggle::make('is_published')->label('Visible par les adhérents')->default(true),
                    ]),

                Section::make('Fréquences et paiements')
                    ->columnSpan(2)
                    ->schema([
                        CheckboxList::make('frequencies')->label('Fréquences proposées aux adhérents')
                            ->options(Frequency::class)->default([Frequency::Every->value, Frequency::Alternate->value])->required(),
                        Repeater::make('payment_plans')->label('Échéanciers proposés')
                            ->helperText('Par exemple : 1 chèque en mars, ou 3 chèques en mars, juin et septembre.')
                            ->schema([
                                TextInput::make('installments')->label('Nombre de règlements')->numeric()->minValue(1)->maxValue(12)->required(),
                                CheckboxList::make('months')->label('Mois d\'encaissement')->options(self::MONTHS)->columns(6)->required(),
                            ])
                            ->default([['installments' => 1, 'months' => [3]], ['installments' => 3, 'months' => [3, 6, 9]]])
                            ->itemLabel(fn (array $state) => ($state['installments'] ?? '?').' règlement(s)')
                            ->addActionLabel('Ajouter un échéancier')
                            ->minItems(1)->collapsible(),
                    ]),

                Section::make('Texte du contrat')
                    ->columnSpan(1)
                    ->collapsed()
                    ->schema([
                        RichEditor::make('terms')->label('')->toolbarButtons([['bold', 'italic'], ['bulletList'], ['undo', 'redo']]),
                    ]),
            ]);
    }
}
