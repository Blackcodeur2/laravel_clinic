<?php

namespace App\Policies;

use App\Models\ClinicSetting;
use App\Models\User;

class ClinicSettingPolicy
{
    /**
     * Only admins can view or update clinic settings.
     */
    public function before(User $user, string $ability): ?bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return false;
    }

    public function view(User $user, ClinicSetting $setting): bool
    {
        return false;
    }

    public function update(User $user, ClinicSetting $setting): bool
    {
        return false;
    }
}
