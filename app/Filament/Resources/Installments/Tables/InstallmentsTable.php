<?php

namespace App\Filament\Resources\Installments\Tables;

use App\Enums\InstallmentStatus;
use App\Enums\PaymentMethod;
use App\Models\Contract;
use App\Models\Installment;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class InstallmentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('due_on')
            ->columns([
                TextColumn::make('user.last_name')->label('Adhérent')->sortable()->searchable(['first_name', 'last_name'])
                    ->formatStateUsing(fn (Installment $i) => $i->user->sort_name),
                TextColumn::make('producer.name')->label('Producteur')->sortable(),
                TextColumn::make('payable_type')->label('Pour')
                    ->formatStateUsing(fn (Installment $i) => $i->payable instanceof Contract ? 'Contrat' : 'Commande groupée')
                    ->description(fn (Installment $i) => $i->payable instanceof Contract ? "règlement {$i->number}/{$i->payable->installments}" : null),
                TextColumn::make('amount_cents')->label('Montant')->money('EUR', divideBy: 100, locale: 'fr')->sortable(),
                TextColumn::make('due_on')->label('Encaissement')->date('F Y')->sortable(),
                TextColumn::make('payment_method')->label('Mode')->badge()->color('gray')
                    ->description(fn (Installment $i) => $i->cheque_number ? 'n° '.$i->cheque_number : null),
                TextColumn::make('status')->label('État')->badge()
                    ->description(fn (Installment $i) => $i->marked_at ? $i->marked_at->translatedFormat('j M').' par '.$i->markedBy?->first_name : null),
            ])
            ->filters([
                SelectFilter::make('producer_id')->label('Producteur')->relationship('producer', 'name', fn (Builder $query) => auth()->user()->isAdmin() ? $query : $query->where('referent_id', auth()->id())),
                SelectFilter::make('status')->label('État')->options(InstallmentStatus::class),
                SelectFilter::make('payment_method')->label('Mode')->options(PaymentMethod::class),
            ])
            ->recordActions([
                Action::make('recu')->label('Reçu')->icon('heroicon-o-inbox-arrow-down')->color('warning')->button()
                    ->visible(fn (Installment $i) => $i->status === InstallmentStatus::Expected)
                    ->schema([TextInput::make('cheque_number')->label('N° de chèque (facultatif)')])
                    ->modalWidth('sm')
                    ->action(function (Installment $record, array $data) {
                        $record->cheque_number = $data['cheque_number'] ?: $record->cheque_number;
                        $record->mark(InstallmentStatus::Received, auth()->user());
                    }),
                Action::make('encaisse')->label('Encaissé')->icon('heroicon-o-banknotes')->color('success')->button()
                    ->visible(fn (Installment $i) => $i->status !== InstallmentStatus::Cashed)
                    ->action(fn (Installment $record) => $record->mark(InstallmentStatus::Cashed, auth()->user())),
                Action::make('annuler')->label('Remettre en attente')->icon('heroicon-o-arrow-uturn-left')->color('gray')->iconButton()
                    ->visible(fn (Installment $i) => $i->status !== InstallmentStatus::Expected)
                    ->requiresConfirmation()
                    ->action(fn (Installment $record) => $record->mark(InstallmentStatus::Expected, auth()->user())),
            ])
            ->toolbarActions([
                BulkAction::make('recus')->label('Marquer reçus')->icon('heroicon-o-inbox-arrow-down')
                    ->action(fn (Collection $records) => $records->each->mark(InstallmentStatus::Received, auth()->user()))
                    ->deselectRecordsAfterCompletion(),
                BulkAction::make('encaisses')->label('Marquer encaissés')->icon('heroicon-o-banknotes')->color('success')
                    ->action(fn (Collection $records) => $records->each->mark(InstallmentStatus::Cashed, auth()->user()))
                    ->deselectRecordsAfterCompletion(),
            ])
            ->emptyStateHeading('Aucune échéance')
            ->emptyStateDescription('Les échéances apparaissent quand les adhérents valident leurs contrats.');
    }
}
