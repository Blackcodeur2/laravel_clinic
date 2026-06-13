<?php

namespace App\Policies;

use App\Models\Medicament;
use App\Models\User;

class MedicamentPolicy
{
    /**
     * Perform pre-authorization checks.
     */
    public function before(User $user, string $ability): ?bool
    {
        if ($user->isAdmin() || $user->isResponsable()) {
            return true;
        }

        return null;
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Medicament $medicament): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false; // Only admin
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Medicament $medicament): bool
    {
        return false; // Only admin
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Medicament $medicament): bool
    {
        return false; // Only admin
    }
}
