<?php

namespace App\Policies;

use App\Models\Membership;
use App\Models\User;

/** Réservé aux administrateurs (autorisés par Gate::before). Les référents consultent seulement. */
class MembershipPolicy
{
    public function viewAny(User $user): bool
    {
        return false;
    }

    public function view(User $user, Membership $model): bool
    {
        return false;
    }

    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, Membership $model): bool
    {
        return false;
    }

    public function delete(User $user, Membership $model): bool
    {
        return false;
    }
}
