<?php

namespace App\Filament\Resources\GroupPurchases\Tables;

use App\Filament\Resources\GroupPurchases\GroupPurchaseResource;
use App\Models\Distribution;
use App\Models\GroupPurchase;
use App\Services\Announcements;
use App\Services\DistributionDocuments;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

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
                        ->modalDescription(fn (GroupPurchase $p) => ($p->announced_at ? 'Déjà annoncée le '.$p->announced_at->translatedFormat('j M à H\hi').'. Renvoyer ? ' : '')
                            .($p->contract_offer_id ? 'Un e-mail est envoyé aux adhérents engagés sur le contrat lié.' : 'Un e-mail est envoyé à tous les adhérents à jour de cotisation.'))
                        ->action(function (GroupPurchase $record, Announcements $announcements) {
                            $count = $announcements->announceGroupPurchase($record);
                            Notification::make()->title("Annonce envoyée à {$count} adhérents.")->success()->send();
                        }),
                    Action::make('repeter')->label('Répéter à chaque distribution')->icon('heroicon-o-arrow-path')
                        ->modalDescription('Crée une copie de cette commande pour chaque distribution suivante du producteur, avec les mêmes articles et les mêmes horaires (par exemple ouverture le mercredi à 12 h, clôture le jeudi à 18 h). Chaque copie est annoncée automatiquement à son ouverture.')
                        ->schema([
                            DatePicker::make('until')->label('Jusqu\'au')->required()
                                ->default(fn (GroupPurchase $record) => $record->contractOffer?->ends_on ?? $record->distribution->date->copy()->addMonths(3)),
                        ])
                        ->action(function (GroupPurchase $record, array $data) {
                            $count = self::repeat($record, Carbon::parse($data['until']));
                            Notification::make()->title("{$count} commande(s) créée(s).")->success()->send();
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

    /**
     * Copie la commande pour chaque distribution suivante où le producteur est présent
     * (toutes les distributions s'il n'est rattaché à aucune), en gardant l'écart entre
     * l'ouverture, la clôture et la livraison. Les distributions déjà servies sont ignorées.
     */
    public static function repeat(GroupPurchase $model, Carbon $until): int
    {
        $model->loadMissing(['distribution', 'items']);
        $base = $model->distribution->date->copy()->startOfDay();
        $opensOffset = $base->diffInMinutes($model->opens_at, false);
        $closesOffset = $base->diffInMinutes($model->closes_at, false);

        $query = $model->producer->distributions()->exists() ? $model->producer->distributions() : Distribution::query();
        $distributions = $query->whereDate('date', '>', $base)->whereDate('date', '<=', $until)->orderBy('date')->get();
        $taken = GroupPurchase::where('producer_id', $model->producer_id)->pluck('distribution_id');

        $count = 0;
        foreach ($distributions->reject(fn (Distribution $d) => $taken->contains($d->id)) as $distribution) {
            DB::transaction(function () use ($model, $distribution, $opensOffset, $closesOffset) {
                $day = $distribution->date->copy()->startOfDay();
                $copy = $model->replicate(['slug', 'announced_at']);
                $copy->title = preg_replace('/ – .*$/u', '', $model->title).' – '.$distribution->date->translatedFormat('j F');
                $copy->distribution_id = $distribution->id;
                $copy->opens_at = $day->copy()->addMinutes($opensOffset);
                $copy->closes_at = $day->copy()->addMinutes($closesOffset);
                $copy->save();
                foreach ($model->items as $item) {
                    $copy->items()->create($item->only(['name', 'packaging', 'price_cents', 'max_per_member', 'sort']));
                }
            });
            $count++;
        }

        return $count;
    }
}
