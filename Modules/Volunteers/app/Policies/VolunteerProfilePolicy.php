<?php

namespace Modules\Volunteers\Policies;

use Modules\Core\Models\User;
use Modules\Volunteers\Models\VolunteerProfile;
use Illuminate\Auth\Access\HandlesAuthorization;

class VolunteerProfilePolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     */
    public function __construct() {}

    /**
     * View a volunteer profile
     */
    public function view(User $user, VolunteerProfile $profile): bool
    {
        return
            $user->hasRole('system-admin') ||

            $user->hasPermissionTo('organization.volunteers.read') ||

            (
                $user->hasPermissionTo('profile.read.own') &&
                $profile->user_id === $user->id
            );
    }

    /**
     * Update volunteer profile
     */
    public function update(User $user, VolunteerProfile $profile): bool
    {
        return
            $user->hasRole('system-admin') ||

            (
                $user->hasPermissionTo('profile.update.own') &&
                $profile->user_id === $user->id
            );
    }

    /**
     * Verify volunteer profile
     */
    public function verify(User $user, VolunteerProfile $profile): bool
    {
        return
            $user->hasRole('system-admin') ||

            $user->hasPermissionTo('organization.volunteers.evaluate');
    }

    /**
     * Delete volunteer profile
     */
    public function delete(User $user, VolunteerProfile $profile): bool
    {
        return $user->hasRole('system-admin');
    }
    public function viewPending(User $user): bool
    {
        return
            $user->hasRole('system-admin') ||
            $user->hasPermissionTo('organization.volunteers.read');
    }
    public function manageStatus(User $user): bool
    {
        return
            $user->hasRole('system-admin') ||
            $user->hasPermissionTo('organization.volunteers.evaluate');
    }
}
