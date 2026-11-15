<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Enums\PaymentMethod;
use App\Enums\Role;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Spatie\Permission\Models\Role as RoleModel;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(3)
            ->components([
                Section::make('Coordonnées')
                    ->columnSpan(2)
                    ->columns(2)
                    ->schema([
                        TextInput::make('first_name')->label('Prénom')->required(),
                        TextInput::make('last_name')->label('Nom')->required(),
                        TextInput::make('email')->label('E-mail')->email()->required()->unique(ignoreRecord: true),
                        TextInput::make('phone')->label('Téléphone')->tel(),
                        TextInput::make('address')->label('Adresse')->columnSpanFull(),
                        TextInput::make('postal_code')->label('Code postal'),
                        TextInput::make('city')->label('Commune'),
                        Toggle::make('share_phone')->label('Accepte de partager son téléphone avec les autres adhérents')->columnSpanFull(),
                        TextInput::make('password')->label('Mot de passe')->password()->revealable()
                            ->required(fn (string $operation) => $operation === 'create')
                            ->dehydrated(fn ($state) => filled($state))
                            ->helperText('Laisser vide pour ne pas le changer.'),
                    ]),
                Section::make('Rôles')
                    ->columnSpan(1)
                    ->schema([
                        CheckboxList::make('roles')->label('')
                            ->relationship('roles', 'name')
                            ->getOptionLabelFromRecordUsing(fn (RoleModel $role) => Role::from($role->name)->getLabel()),
                    ]),
                Section::make('Adhésions')
                    ->columnSpanFull()
                    ->schema([
                        Repeater::make('memberships')->hiddenLabel()
                            ->relationship()
                            ->table([
                                Repeater\TableColumn::make('Année'),
                                Repeater\TableColumn::make('Montant (centimes)'),
                                Repeater\TableColumn::make('Mode'),
                                Repeater\TableColumn::make('Payée le'),
                            ])
                            ->schema([
                                TextInput::make('year')->numeric()->default(now()->year + 1)->required(),
                                TextInput::make('amount_cents')->numeric()->default(config('amap.membership_fee_cents'))->required(),
                                Select::make('payment_method')->options(PaymentMethod::class)->default(PaymentMethod::Cheque)->required(),
                                DateTimePicker::make('paid_at')->seconds(false),
                            ])
                            ->addActionLabel('Ajouter une adhésion'),
                    ]),
            ]);
    }
}
