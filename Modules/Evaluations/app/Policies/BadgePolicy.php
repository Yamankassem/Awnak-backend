<?php

namespace Modules\Evaluations\Policies;

use Modules\Core\Models\User;
use Modules\Evaluations\Models\Badge;

class BadgePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Badge $badge): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
   public function create(User $user): bool
    {
        return $user->hasRole('system-admin');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Badge $badge):bool
    {
       return $user->hasRole('system-admin');
    }

    /**
     * Delete badge
     */
    public function delete(User $user, Badge $badge):bool
    {
        return $user->hasRole('system-admin');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Badge $badge): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Badge $badge): bool
    {
        return false;
    }
}
