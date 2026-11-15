<?php

namespace App\Filament\Resources\Distributions\Tables;

use App\Enums\DistributionStatus;
use App\Mail\DistributionSummary;
use App\Models\Distribution;
use App\Models\Producer;
use App\Services\DistributionDocuments;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Mail;

class DistributionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('date')
            ->modifyQueryUsing(fn ($query) => $query->withCount(['producers', 'deliveries']))
            ->columns([
                TextColumn::make('date')->label('Date')->sortable()
                    ->formatStateUsing(fn (Distribution $d) => $d->label())
                    ->description(fn (Distribution $d) => $d->timeRange().' · '.$d->place),
                TextColumn::make('producers_count')->label('Producteurs')->alignCenter(),
                TextColumn::make('deliveries_count')->label('Livraisons')->alignCenter(),
                TextColumn::make('status')->label('État')->badge(),
            ])
            ->filters([
                TernaryFilter::make('a_venir')->label('Période')
                    ->placeholder('Toutes')->trueLabel('À venir')->falseLabel('Passées')->default(true)
                    ->queries(
                        true: fn ($query) => $query->whereDate('date', '>=', today()),
                        false: fn ($query) => $query->whereDate('date', '<', today()),
                    ),
            ])
            ->recordActions([
                ActionGroup::make([
                    Action::make('emargement')->label('Feuilles d\'émargement (PDF)')->icon('heroicon-o-clipboard-document-list')
                        ->action(function (Distribution $record, DistributionDocuments $documents) {
                            $pdf = $documents->signInSheetsPdf($record);

                            return response()->streamDownload(fn () => print ($pdf->output()), $documents->filename('emargement', $record));
                        }),
                    Action::make('recapitulatif')->label('Récapitulatif d\'un producteur (PDF)')->icon('heroicon-o-document-text')
                        ->schema([
                            Select::make('producer_id')->label('Producteur')
                                ->options(fn (Distribution $record) => $record->producers()->pluck('name', 'producers.id'))
                                ->required(),
                        ])
                        ->action(function (Distribution $record, array $data, DistributionDocuments $documents) {
                            $producer = Producer::findOrFail($data['producer_id']);
                            $pdf = $documents->summaryPdf($record, $producer);

                            return response()->streamDownload(fn () => print ($pdf->output()), $documents->filename('recapitulatif', $record, $producer));
                        }),
                    Action::make('envoyer')->label('Clôturer et envoyer les récapitulatifs')->icon('heroicon-o-paper-airplane')
                        ->visible(fn () => auth()->user()->isAdmin())
                        ->requiresConfirmation()
                        ->modalHeading('Clôturer la distribution ?')
                        ->modalDescription('Chaque producteur concerné reçoit par e-mail son récapitulatif en PDF.')
                        ->action(function (Distribution $record) {
                            $producers = Producer::whereIn('id', $record->deliveries()->distinct()->pluck('producer_id'))->with('account')->get();
                            $sent = 0;
                            foreach ($producers as $producer) {
                                $email = $producer->email ?? $producer->account?->email;
                                if ($email) {
                                    Mail::to($email)->send(new DistributionSummary($record, $producer));
                                    $sent++;
                                }
                            }
                            $record->update(['status' => DistributionStatus::SummariesSent, 'closed_at' => now(), 'summaries_sent_at' => now()]);
                            Notification::make()->title("Récapitulatifs envoyés à {$sent} producteur(s).")->success()->send();
                        }),
                ])->label('Documents')->icon('heroicon-m-document-arrow-down')->button()->color('gray'),
                EditAction::make(),
            ]);
    }
}
