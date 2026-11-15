<?php

namespace App\Policies;

use App\Models\Page;
use App\Models\User;

/** Réservé aux administrateurs (autorisés par Gate::before). Les référents consultent seulement. */
class PagePolicy
{
    public function viewAny(User $user): bool
    {
        return false;
    }

    public function view(User $user, Page $model): bool
    {
        return false;
    }

    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, Page $model): bool
    {
        return false;
    }

    public function delete(User $user, Page $model): bool
    {
        return false;
    }
}
