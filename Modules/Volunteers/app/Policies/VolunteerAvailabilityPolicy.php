<?php

namespace Modules\Volunteers\Policies;

use Modules\Core\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Volunteers\Models\VolunteerAvailability;

class VolunteerAvailabilityPolicy
{
    use HandlesAuthorization;
    
    // the owner of profile can see Volunteer Availability
     public function view(User $user, VolunteerAvailability $availability): bool
    {
        return $availability->volunteerProfile->user_id === $user->id;
    }

    // who has premission profile.update.own to store new availabiltiy record
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('profile.update.own');
    }

    // the owner of profile can see Volunteer Availability
    public function update(User $user, VolunteerAvailability $availability): bool
    {
        return $availability->volunteerProfile->user_id === $user->id;
    }

    // the owner of profile can see Volunteer Availability
    public function delete(User $user, VolunteerAvailability $availability): bool
    {
        return $availability->volunteerProfile->user_id === $user->id;
    }

    
}
