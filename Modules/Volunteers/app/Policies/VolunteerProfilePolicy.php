<?php

namespace Modules\Volunteers\Policies;

use Modules\Core\Models\User;
use Modules\Volunteers\Models\VolunteerProfile;
use Illuminate\Auth\Access\HandlesAuthorization;

class VolunteerProfilePolicy
{
    use HandlesAuthorization;
    /**
     * View a volunteer profile
     * system admin and organization.volunteers.read (organization admin)
     * and the owner of profile with permiision profile.read.own
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
     * update a volunteer profile
     * system admin 
     * and the owner of profile with permiision profile.read.own
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
     * system admin and organization.volunteers.read (organization admin)
     */
    public function verify(User $user, VolunteerProfile $profile): bool
    {
        return
            $user->hasRole('system-admin') ||

            $user->hasPermissionTo('organization.volunteers.evaluate');
    }

    /**
     * delete a volunteer profile
     * system admin 
     */
    public function delete(User $user, VolunteerProfile $profile): bool
    {
        return $user->hasRole('system-admin');
    }
    /**
     * View pending  profiles
     * system admin and organization.volunteers.read (organization admin)
     */
    public function viewPending(User $user): bool
    {
        return
            $user->hasRole('system-admin') ||
            $user->hasPermissionTo('organization.volunteers.read');
    }
    /**
     * Manage Status of  profiles
     * system admin and organization.volunteers.read (organization admin)
     */
    public function manageStatus(User $user): bool
    {
        return
            $user->hasRole('system-admin') ||
            $user->hasPermissionTo('organization.volunteers.evaluate');
    }
}
