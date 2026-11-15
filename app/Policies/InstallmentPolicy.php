<?php

namespace App\Policies;

use App\Models\Installment;
use App\Models\User;

/** Pointage : le référent du producteur ou le producteur lui-même. L'adhérent voit ses échéances sans les modifier. */
class InstallmentPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isReferent();
    }

    public function view(User $user, Installment $installment): bool
    {
        return $this->update($user, $installment) || $installment->user_id === $user->id;
    }

    public function update(User $user, Installment $installment): bool
    {
        return $installment->producer->referent_id === $user->id
            || ($user->isProducer() && $installment->producer->user_id === $user->id);
    }
}
