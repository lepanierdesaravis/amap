<?php

namespace App\Filament\Resources\GroupPurchases\Pages;

use App\Filament\Resources\GroupPurchases\GroupPurchaseResource;
use Filament\Resources\Pages\CreateRecord;

class CreateGroupPurchase extends CreateRecord
{
    protected static string $resource = GroupPurchaseResource::class;

    protected static ?string $title = 'Nouvelle commande groupée';

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['referent_id'] = auth()->id();

        return $data;
    }
}
