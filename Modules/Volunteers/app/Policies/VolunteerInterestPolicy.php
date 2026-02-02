<?php

namespace Modules\Volunteers\Policies;

use Modules\Core\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Volunteers\Models\VolunteerInterest;

class VolunteerInterestPolicy
{
    use HandlesAuthorization;

    // the owner of profile can see Volunteer Interest
    public function view(User $user, VolunteerInterest $interest): bool
    {
        return $interest->volunteerProfile->user_id === $user->id;
    }

    // who has premission profile.update.own to store new interest record
    public function create(User $user): bool
    {
        return $user->can('profile.update.own');
    }

    // the owner of profile can see Volunteer Interest
    public function update(User $user, VolunteerInterest $interest): bool
    {
        return $interest->volunteerProfile->user_id === $user->id;
    }

    // the owner of profile can see Volunteer Interest
    public function delete(User $user, VolunteerInterest $interest): bool
    {
        return $interest->volunteerProfile->user_id === $user->id;
    }
}
