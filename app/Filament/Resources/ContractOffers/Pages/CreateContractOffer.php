<?php

namespace App\Filament\Resources\ContractOffers\Pages;

use App\Filament\Resources\ContractOffers\ContractOfferResource;
use Filament\Resources\Pages\CreateRecord;

class CreateContractOffer extends CreateRecord
{
    protected static string $resource = ContractOfferResource::class;

    protected static ?string $title = 'Nouvelle offre de contrat';
}
