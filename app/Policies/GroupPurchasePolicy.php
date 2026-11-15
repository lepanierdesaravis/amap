<?php

namespace App\Policies;

use App\Models\GroupPurchase;
use App\Models\User;

class GroupPurchasePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isReferent();
    }

    public function view(User $user, GroupPurchase $purchase): bool
    {
        return $purchase->referent_id === $user->id;
    }

    public function create(User $user): bool
    {
        return $user->isReferent();
    }

    public function update(User $user, GroupPurchase $purchase): bool
    {
        return $purchase->referent_id === $user->id;
    }

    public function delete(User $user, GroupPurchase $purchase): bool
    {
        return $purchase->referent_id === $user->id && $purchase->orders()->doesntExist();
    }
}
