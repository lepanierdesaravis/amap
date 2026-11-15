<?php

namespace App\Filament\Resources\Producers\Schemas;

use App\Enums\Participation;
use App\Enums\Role;
use App\Models\User;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

/**
 * Formulaire « Ajouter un producteur » : il suffit de le remplir
 * pour que la fiche publique soit créée.
 */
class ProducerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(3)
            ->components([
                Section::make('La ferme')
                    ->columnSpan(2)
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')->label('Nom de la ferme')->required()->maxLength(120)->columnSpanFull(),
                        TextInput::make('farmer_names')->label('Producteurs')->placeholder('Christophe et Nathalie Raucaz'),
                        TextInput::make('city')->label('Commune'),
                        TextInput::make('tagline')->label('En une phrase')->placeholder('Fruits, compotes et jus bio au rythme des saisons')->maxLength(140)->columnSpanFull(),
                        RichEditor::make('description')->label('Présentation')
                            ->toolbarButtons([['bold', 'italic', 'link'], ['h2', 'h3'], ['bulletList', 'orderedList'], ['undo', 'redo']])
                            ->columnSpanFull(),
                    ]),

                Section::make('Publication')
                    ->columnSpan(1)
                    ->schema([
                        Toggle::make('is_published')->label('Fiche visible sur le site')->default(true),
                        Select::make('participation')->label('Participation')->options(Participation::class)->default(Participation::Contract)->required(),
                        CheckboxList::make('product_types')->label('Types de produits')->options(config('amap.product_types'))->columns(2),
                        CheckboxList::make('labels')->label('Labels')->options(config('amap.labels')),
                    ]),

                Section::make('Photos et documents')
                    ->columnSpan(2)
                    ->schema([
                        FileUpload::make('cover_photo')->label('Photo principale')
                            ->helperText('Elle illustre la fiche et la liste des producteurs.')
                            ->image()->imageEditor()->disk('public')->directory('producteurs')->maxSize(8192),
                        FileUpload::make('photos')->label('Autres photos')
                            ->image()->multiple()->reorderable()->disk('public')->directory('producteurs')->maxSize(8192)->panelLayout('grid'),
                        FileUpload::make('documents')->label('Documents (PDF : gamme, tarifs, conseils…)')
                            ->acceptedFileTypes(['application/pdf'])->multiple()->disk('public')->directory('producteurs/documents')
                            ->preserveFilenames()->openable()->maxSize(10240),
                    ]),

                Section::make('Contact')
                    ->columnSpan(1)
                    ->schema([
                        TextInput::make('address')->label('Adresse'),
                        TextInput::make('phone')->label('Téléphone')->tel(),
                        TextInput::make('email')->label('E-mail')->email(),
                        TextInput::make('website')->label('Site internet')->url()->placeholder('https://'),
                        TextInput::make('social_links.facebook')->label('Facebook')->url(),
                        TextInput::make('social_links.instagram')->label('Instagram')->url(),
                    ]),

                Section::make('Paiements des contrats')
                    ->description("L'AMAP n'encaisse rien : les chèques et virements vont directement au producteur.")
                    ->columnSpan(2)
                    ->columns(3)
                    ->collapsed()
                    ->schema([
                        TextInput::make('cheque_payee')->label('Ordre des chèques'),
                        TextInput::make('iban')->label('IBAN (virements)'),
                        TextInput::make('siret')->label('SIRET'),
                    ]),

                Section::make('Espace producteur')
                    ->columnSpan(1)
                    ->collapsed()
                    ->schema([
                        Select::make('user_id')->label('Compte du producteur')
                            ->helperText('Pour consulter ses récapitulatifs et pointer ses paiements.')
                            ->relationship('account', 'email', fn ($query) => $query->role(Role::Producer->value))
                            ->getOptionLabelFromRecordUsing(fn (User $user) => "{$user->name} ({$user->email})")
                            ->searchable()->preload(),
                    ]),
            ]);
    }
}
