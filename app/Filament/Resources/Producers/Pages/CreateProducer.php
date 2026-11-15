<?php

namespace App\Filament\Resources\Producers\Pages;

use App\Filament\Resources\Producers\ProducerResource;
use Filament\Resources\Pages\CreateRecord;

class CreateProducer extends CreateRecord
{
    protected static string $resource = ProducerResource::class;

    protected static ?string $title = 'Ajouter un producteur';

    /** Le référent qui crée le producteur en devient le gestionnaire. */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['referent_id'] = auth()->id();

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('edit', ['record' => $this->getRecord(), 'relation' => '0']);
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Producteur créé : sa fiche est en ligne. Ajoutez maintenant ses produits.';
    }
}
