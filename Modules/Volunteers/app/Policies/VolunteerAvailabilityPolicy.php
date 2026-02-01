<?php

namespace Modules\Volunteers\Policies;

use Modules\Core\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Volunteers\Models\VolunteerAvailability;

class VolunteerAvailabilityPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     */
    public function __construct() {}

     public function view(User $user, VolunteerAvailability $availability): bool
    {
        return $availability->volunteerProfile->user_id === $user->id;
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('profile.update.own');
    }

    public function update(User $user, VolunteerAvailability $availability): bool
    {
        return $availability->volunteerProfile->user_id === $user->id;
    }

    public function delete(User $user, VolunteerAvailability $availability): bool
    {
        return $availability->volunteerProfile->user_id === $user->id;
    }

    
}
