<?php

namespace App\Filament\Resources\Producers\Pages;

use App\Filament\Resources\Producers\ProducerResource;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditProducer extends EditRecord
{
    protected static string $resource = ProducerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('voir')->label('Voir la fiche publique')->icon('heroicon-o-eye')->color('gray')
                ->url(fn () => route('producteurs.show', $this->getRecord()))->openUrlInNewTab(),
            DeleteAction::make(),
        ];
    }

    public function getContentTabLabel(): ?string
    {
        return 'Fiche';
    }

    public function hasCombinedRelationManagerTabsWithContent(): bool
    {
        return true;
    }
}
