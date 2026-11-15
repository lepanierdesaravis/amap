<?php

namespace App\Filament\Resources\GroupPurchases\Pages;

use App\Filament\Resources\GroupPurchases\GroupPurchaseResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListGroupPurchases extends ListRecords
{
    protected static string $resource = GroupPurchaseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('Nouvelle commande groupée')->icon('heroicon-o-plus'),
        ];
    }
}
