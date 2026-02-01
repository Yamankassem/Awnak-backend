<?php

namespace Modules\Volunteers\Policies;

use Modules\Core\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Volunteers\Models\VolunteerInterest;

class VolunteerInterestPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     */
    public function __construct() {}

    public function view(User $user, VolunteerInterest $interest): bool
    {
        return $interest->volunteerProfile->user_id === $user->id;
    }

    public function create(User $user): bool
    {
        return $user->can('profile.update.own');
    }

    public function update(User $user, VolunteerInterest $interest): bool
    {
        return $interest->volunteerProfile->user_id === $user->id;
    }

    public function delete(User $user, VolunteerInterest $interest): bool
    {
        return $interest->volunteerProfile->user_id === $user->id;
    }
}
