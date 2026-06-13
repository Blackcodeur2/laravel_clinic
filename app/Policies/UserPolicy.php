<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Admin-only gate for all user management.
     */
    public function before(User $user, string $ability): ?bool
    {
        if ($user->isAdmin() || $user->isResponsable()) {
            return true;
        }

        return false; // Non-admins/non-responsables cannot manage users
    }

    public function viewAny(User $user): bool
    {
        return false;
    }

    public function view(User $user, User $model): bool
    {
        return false;
    }

    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, User $model): bool
    {
        return false;
    }

    public function delete(User $user, User $model): bool
    {
        return $user->id !== $model->id; // Can't delete yourself
    }
}
