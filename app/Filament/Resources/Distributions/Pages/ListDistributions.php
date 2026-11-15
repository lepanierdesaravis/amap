<?php

namespace App\Filament\Resources\Distributions\Pages;

use App\Enums\DistributionStatus;
use App\Enums\Participation;
use App\Filament\Resources\Distributions\DistributionResource;
use App\Models\Distribution;
use App\Models\Producer;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Carbon;

class ListDistributions extends ListRecords
{
    protected static string $resource = DistributionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('generer')->label('Générer le calendrier')->icon('heroicon-o-sparkles')->color('gray')
                ->visible(fn () => auth()->user()->isAdmin())
                ->modalDescription('Crée une distribution toutes les deux semaines. Les producteurs sous contrat sont ajoutés ; vous pourrez ajuster chaque date ensuite.')
                ->schema([
                    DatePicker::make('first')->label('Première distribution')->required(),
                    DatePicker::make('last')->label('Dernière distribution')->required()->after('first'),
                    TextInput::make('interval')->label('Toutes les … semaines')->numeric()->default(config('amap.distribution.interval_weeks'))->required(),
                ])
                ->action(function (array $data) {
                    $producers = Producer::whereIn('participation', [Participation::Contract, Participation::Both])->pluck('id');
                    $created = 0;
                    for ($date = Carbon::parse($data['first']); $date->lte(Carbon::parse($data['last'])); $date->addWeeks((int) $data['interval'])) {
                        $distribution = Distribution::firstOrCreate(['date' => $date->toDateString()], [
                            'place' => config('amap.distribution.place'),
                            'starts_at' => config('amap.distribution.starts_at'),
                            'ends_at' => config('amap.distribution.ends_at'),
                            'status' => DistributionStatus::Planned,
                        ]);
                        if ($distribution->wasRecentlyCreated) {
                            $distribution->producers()->sync($producers);
                            $created++;
                        }
                    }
                    Notification::make()->title("{$created} distributions créées.")->success()->send();
                }),
            CreateAction::make()->label('Ajouter une date'),
        ];
    }
}
