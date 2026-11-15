<?php

namespace App\Policies;

use App\Models\Producer;
use App\Models\User;

/** Un référent gère uniquement les producteurs qu'il a créés (décision du 15/11/2026). */
class ProducerPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isReferent();
    }

    public function view(User $user, Producer $producer): bool
    {
        return $producer->referent_id === $user->id;
    }

    public function create(User $user): bool
    {
        return $user->isReferent();
    }

    public function update(User $user, Producer $producer): bool
    {
        return $producer->referent_id === $user->id;
    }

    public function delete(User $user, Producer $producer): bool
    {
        return $producer->referent_id === $user->id;
    }
}
