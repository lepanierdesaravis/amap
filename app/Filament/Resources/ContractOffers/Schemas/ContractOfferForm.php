<?php

namespace App\Filament\Resources\ContractOffers\Schemas;

use App\Enums\Audience;
use App\Enums\Frequency;
use App\Filament\Support\Fields;
use App\Models\ContractOffer;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

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
                        Select::make('duration')->label('Durée')->dehydrated(false)->live()
                            ->options(['12' => 'Annuel', '6' => 'Semestriel', '3' => 'Trimestriel'])
                            ->placeholder('Dates libres')
                            ->helperText('Remplit la dernière date à partir de la première.')
                            ->afterStateUpdated(fn ($state, Get $get, Set $set) => $state && $get('starts_on')
                                ? $set('ends_on', Carbon::parse($get('starts_on'))->addMonths((int) $state)->subDay()->toDateString()) : null),
                        DatePicker::make('starts_on')->label('Première livraison possible')->required()->live()
                            ->afterStateUpdated(fn ($state, Get $get, Set $set) => $state && $get('duration')
                                ? $set('ends_on', Carbon::parse($state)->addMonths((int) $get('duration'))->subDay()->toDateString()) : null),
                        DatePicker::make('ends_on')->label('Dernière livraison possible')->required()->afterOrEqual('starts_on'),
                        CheckboxList::make('products')->label('Produits ou montants proposés')
                            ->helperText('Les produits et les montants d\'engagement se gèrent dans la fiche du producteur (onglet « Produits »).')
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
                        TextInput::make('max_contracts')->label('Nombre de contrats limité')->numeric()->minValue(1)
                            ->placeholder('Illimité')->helperText('Premier arrivé, premier servi.'),
                        Select::make('announce_to')->label('Annoncer l\'ouverture par e-mail')->options(Audience::class)
                            ->placeholder('Pas d\'annonce')
                            ->helperText(fn (?ContractOffer $record) => $record?->announced_at
                                ? 'Annonce envoyée le '.$record->announced_at->translatedFormat('j F à H\hi').'.'
                                : 'E-mail envoyé automatiquement à l\'ouverture.'),
                        Toggle::make('share_members_with_producer')->label('Le producteur peut télécharger la liste des adhérents')
                            ->helperText('Prénom, nom et e-mail des souscripteurs, en PDF ou en tableur, depuis l\'espace producteur.'),
                    ]),

                Section::make('Fréquences et paiements')
                    ->columnSpan(2)
                    ->schema([
                        CheckboxList::make('frequencies')->label('Fréquences proposées aux adhérents')
                            ->helperText('Pour un engagement en montant dont les produits se commandent au fil de la saison (ex. boulangerie), ne cochez rien : les livraisons viendront des commandes liées.')
                            ->options(Frequency::class)->default([Frequency::Every->value, Frequency::Alternate->value]),
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
