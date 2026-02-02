<?php

namespace Modules\Evaluations\Policies;

use Modules\Core\Models\User;
use Modules\Evaluations\Models\VolunteerBadge;

class VolunteerBadgePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        if ($user->hasAnyRole(['system-admin', 'volunteer-coordinator'])) {
            return true;
        }
        return $user->hasRole('volunteer');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, VolunteerBadge $volunteerBadge): bool
    {
        if ($user->hasAnyRole(['system-admin', 'volunteer-coordinator'])) {
            return true;
        }

        return $user->id === $volunteerBadge->volunteer_id;
    }

    /**
     * Determine whether the user can create models.
     */
   public function create(User $user): bool
    {
         return $user->hasAnyRole(['system-admin', 'volunteer-coordinator']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, VolunteerBadge $volunteerBadge):bool
    {
         return $user->hasAnyRole(['system-admin', 'volunteer-coordinator']);
    }

    /**
     * Delete badge
     */
    public function delete(User $user, VolunteerBadge $volunteerBadge):bool
    {
         return $user->hasAnyRole(['system-admin', 'volunteer-coordinator']);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, VolunteerBadge $volunteerBadge): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, VolunteerBadge $volunteerBadge): bool
    {
        return false;
    }
}
