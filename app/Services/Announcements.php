<?php

namespace App\Services;

use App\Enums\Audience;
use App\Mail\ContractOfferOpened;
use App\Mail\GroupPurchaseOpened;
use App\Models\ContractOffer;
use App\Models\GroupPurchase;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Mail;

/**
 * Annonces par e-mail de l'ouverture des contrats et des commandes groupées.
 * Lancées toutes les cinq minutes par `amap:annonces` (voir routes/console.php).
 */
class Announcements
{
    /** Envoie les annonces dont l'ouverture est passée. @return int nombre d'e-mails */
    public function sendDue(): int
    {
        $sent = 0;
        foreach (GroupPurchase::open()->where('announce_by_email', true)->whereNull('announced_at')->get() as $purchase) {
            $sent += $this->announceGroupPurchase($purchase);
        }
        foreach (ContractOffer::open()->whereNotNull('announce_to')->whereNull('announced_at')->get() as $offer) {
            $sent += $this->announceOffer($offer);
        }

        return $sent;
    }

    /** Commande liée à un engagement : seuls les adhérents engagés sont invités. Sinon tous les adhérents. */
    public function groupPurchaseRecipients(GroupPurchase $purchase): Builder
    {
        return $purchase->contract_offer_id
            ? User::whereHas('contracts', fn ($q) => $q->where('contract_offer_id', $purchase->contract_offer_id))
            : User::membersInGoodStanding();
    }

    public function announceGroupPurchase(GroupPurchase $purchase): int
    {
        $count = 0;
        foreach ($this->groupPurchaseRecipients($purchase)->cursor() as $user) {
            Mail::to($user)->queue(new GroupPurchaseOpened($purchase));
            $count++;
        }
        $purchase->update(['announced_at' => now()]);

        return $count;
    }

    public function offerRecipients(ContractOffer $offer): Builder
    {
        return match ($offer->announce_to) {
            // Adhérents qui ont un contrat en cours avec ce producteur (autre que celui-ci).
            Audience::CurrentSubscribers => User::whereHas('contracts.offer', fn ($q) => $q
                ->where('producer_id', $offer->producer_id)
                ->where('contract_offers.id', '!=', $offer->id)
                ->whereDate('ends_on', '>=', today())),
            default => User::membersInGoodStanding(),
        };
    }

    public function announceOffer(ContractOffer $offer): int
    {
        $count = 0;
        foreach ($this->offerRecipients($offer)->cursor() as $user) {
            Mail::to($user)->queue(new ContractOfferOpened($offer));
            $count++;
        }
        $offer->update(['announced_at' => now()]);

        return $count;
    }
}
