<?php

namespace App\Filament\Resources\ContractOffers\Pages;

use App\Filament\Resources\ContractOffers\ContractOfferResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListContractOffers extends ListRecords
{
    protected static string $resource = ContractOfferResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('Nouvelle offre de contrat')->icon('heroicon-o-plus'),
        ];
    }
}
