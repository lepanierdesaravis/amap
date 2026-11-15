<?php

namespace App\Filament\Resources\GroupPurchases\Pages;

use App\Filament\Resources\GroupPurchases\GroupPurchaseResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditGroupPurchase extends EditRecord
{
    protected static string $resource = GroupPurchaseResource::class;

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()];
    }

    public function getContentTabLabel(): ?string
    {
        return 'Commande';
    }

    public function hasCombinedRelationManagerTabsWithContent(): bool
    {
        return true;
    }
}
