<?php

namespace App\Filament\Resources\ContractOffers\Pages;

use App\Filament\Resources\ContractOffers\ContractOfferResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditContractOffer extends EditRecord
{
    protected static string $resource = ContractOfferResource::class;

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()];
    }

    public function getContentTabLabel(): ?string
    {
        return 'Offre';
    }

    public function hasCombinedRelationManagerTabsWithContent(): bool
    {
        return true;
    }
}
