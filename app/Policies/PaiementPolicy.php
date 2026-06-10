<?php

namespace App\Policies;

use App\Models\Paiement;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PaiementPolicy
{
    /**
     * Perform pre-authorization checks.
     */
    public function before(User $user, string $ability): ?bool
    {
        if ($user->isAdmin()) {
            return true;
        }
        return null;
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->isResponsable() || $user->isCaissier();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Paiement $paiement): bool
    {
        return $user->isResponsable() || $user->isCaissier();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->isCaissier();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Paiement $paiement): bool
    {
        return $user->isCaissier();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Paiement $paiement): bool
    {
        return false; // Only admin
    }
}
