<?php

namespace App\Policies;

use App\Models\ContractOffer;
use App\Models\User;

class ContractOfferPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isReferent();
    }

    public function view(User $user, ContractOffer $offer): bool
    {
        return $offer->producer->referent_id === $user->id;
    }

    public function create(User $user): bool
    {
        return $user->isReferent();
    }

    public function update(User $user, ContractOffer $offer): bool
    {
        return $offer->producer->referent_id === $user->id;
    }

    public function delete(User $user, ContractOffer $offer): bool
    {
        return $offer->producer->referent_id === $user->id && $offer->contracts()->doesntExist();
    }
}
