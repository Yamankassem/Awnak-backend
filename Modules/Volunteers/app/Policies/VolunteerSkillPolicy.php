<?php

namespace Modules\Volunteers\Policies;

use Modules\Core\Models\User;
use Modules\Volunteers\Models\VolunteerSkill;
use Illuminate\Auth\Access\HandlesAuthorization;

class VolunteerSkillPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     */
    public function __construct() {}

    public function view(User $user, VolunteerSkill $skill): bool
    {
        return $skill->volunteerProfile->user_id === $user->id;
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('profile.update.own');
    }

    public function update(User $user, VolunteerSkill $skill): bool
    {
        return $skill->volunteerProfile->user_id === $user->id;
    }

    public function delete(User $user, VolunteerSkill $skill): bool
    {
        return $skill->volunteerProfile->user_id === $user->id;
    }
}
