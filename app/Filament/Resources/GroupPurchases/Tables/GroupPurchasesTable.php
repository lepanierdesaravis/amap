<?php

namespace App\Filament\Resources\GroupPurchases\Tables;

use App\Filament\Resources\GroupPurchases\GroupPurchaseResource;
use App\Mail\GroupPurchaseOpened;
use App\Models\GroupPurchase;
use App\Models\User;
use App\Services\DistributionDocuments;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class GroupPurchasesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('closes_at', 'desc')
            ->columns([
                TextColumn::make('title')->label('Commande')->searchable()->description(fn (GroupPurchase $p) => $p->producer->name),
                TextColumn::make('status')->label('État')->badge()->state(fn (GroupPurchase $p) => $p->status())
                    ->description(fn (GroupPurchase $p) => 'clôture le '.$p->closes_at->translatedFormat('j M à H\hi')),
                TextColumn::make('distribution.date')->label('Livraison')->date('j M Y')->sortable(),
                TextColumn::make('orders_count')->label('Commandes')->counts('orders')->alignCenter(),
                TextColumn::make('orders_sum_total_cents')->label('Montant')->sum('orders', 'total_cents')->money('EUR', divideBy: 100, locale: 'fr'),
            ])
            ->recordActions([
                EditAction::make(),
                ActionGroup::make([
                    Action::make('recapitulatif')->label('Récapitulatif producteur (PDF)')->icon('heroicon-o-document-text')
                        ->action(function (GroupPurchase $record, DistributionDocuments $documents) {
                            $pdf = $documents->summaryPdf($record->distribution, $record->producer);

                            return response()->streamDownload(fn () => print ($pdf->output()), $documents->filename('recapitulatif', $record->distribution, $record->producer));
                        }),
                    Action::make('emargement')->label('Feuilles d\'émargement (PDF)')->icon('heroicon-o-clipboard-document-list')
                        ->action(function (GroupPurchase $record, DistributionDocuments $documents) {
                            $pdf = $documents->signInSheetsPdf($record->distribution, $record->producer);

                            return response()->streamDownload(fn () => print ($pdf->output()), $documents->filename('emargement', $record->distribution, $record->producer));
                        }),
                    Action::make('annoncer')->label('Annoncer aux adhérents')->icon('heroicon-o-megaphone')
                        ->visible(fn (GroupPurchase $p) => $p->isOpen())
                        ->requiresConfirmation()
                        ->modalDescription(fn (GroupPurchase $p) => $p->announced_at ? 'Déjà annoncée le '.$p->announced_at->translatedFormat('j M à H\hi').'. Renvoyer ?' : 'Un e-mail est envoyé à tous les adhérents à jour de cotisation.')
                        ->action(function (GroupPurchase $record) {
                            $members = User::whereHas('memberships', fn ($q) => $q->whereIn('year', [now()->year, now()->year + 1])->whereNotNull('paid_at'))->get();
                            foreach ($members as $member) {
                                Mail::to($member)->send(new GroupPurchaseOpened($record));
                            }
                            $record->update(['announced_at' => now()]);
                            Notification::make()->title("Annonce envoyée à {$members->count()} adhérents.")->success()->send();
                        }),
                    Action::make('dupliquer')->label('Dupliquer')->icon('heroicon-o-document-duplicate')
                        ->action(function (GroupPurchase $record) {
                            $copy = DB::transaction(function () use ($record) {
                                $copy = $record->replicate(['slug', 'announced_at']);
                                $copy->title = $record->title.' (copie)';
                                $copy->referent_id = auth()->id();
                                $copy->opens_at = now()->startOfHour();
                                $copy->closes_at = now()->addWeeks(2)->setTime(20, 0);
                                $copy->save();
                                foreach ($record->items as $item) {
                                    $copy->items()->create($item->only(['name', 'packaging', 'price_cents', 'max_per_member', 'sort']));
                                }

                                return $copy;
                            });
                            Notification::make()->title('Commande dupliquée : vérifiez les dates et la distribution.')->success()->send();

                            return redirect(GroupPurchaseResource::getUrl('edit', ['record' => $copy]));
                        }),
                ])->label('Plus')->icon('heroicon-m-ellipsis-vertical')->button()->color('gray'),
            ]);
    }
}
