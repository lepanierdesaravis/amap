<?php

namespace App\Policies;

use App\Models\Distribution;
use App\Models\User;

/** Réservé aux administrateurs (autorisés par Gate::before). Les référents consultent seulement. */
class DistributionPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isReferent();
    }

    public function view(User $user, Distribution $model): bool
    {
        return $user->isReferent();
    }

    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, Distribution $model): bool
    {
        return false;
    }

    public function delete(User $user, Distribution $model): bool
    {
        return false;
    }
}
