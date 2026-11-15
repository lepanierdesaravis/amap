<?php

namespace App\Filament\Resources\Distributions\Schemas;

use App\Enums\DistributionStatus;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class DistributionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(3)
            ->components([
                Section::make('Date et lieu')
                    ->columnSpan(1)
                    ->schema([
                        DatePicker::make('date')->label('Date')->required()->unique(ignoreRecord: true),
                        TimePicker::make('starts_at')->label('Début')->seconds(false)->default(config('amap.distribution.starts_at'))->required(),
                        TimePicker::make('ends_at')->label('Fin')->seconds(false)->default(config('amap.distribution.ends_at'))->required(),
                        TextInput::make('place')->label('Lieu')->default(config('amap.distribution.place'))->required(),
                        Select::make('status')->label('État')->options(DistributionStatus::class)->default(DistributionStatus::Planned)->required(),
                        Textarea::make('notes')->label('Informations pour les adhérents')->rows(3),
                    ]),
                Section::make('Producteurs présents')
                    ->columnSpan(2)
                    ->schema([
                        CheckboxList::make('producers')->label('')
                            ->relationship('producers', 'name')
                            ->columns(3)->bulkToggleable(),
                    ]),
            ]);
    }
}
