<?php

namespace Modules\Volunteers\Policies;

use Modules\Core\Models\User;
use Modules\Volunteers\Models\VolunteerSkill;
use Illuminate\Auth\Access\HandlesAuthorization;

class VolunteerSkillPolicy
{
    use HandlesAuthorization;

    /**
     * View a volunteer skill.
     */
    public function view(User $user, VolunteerSkill $skill): bool
    {
        return $skill->volunteerProfile->user_id === $user->id;
    }

    /**
     * create a volunteer skill.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('profile.update.own');
    }

    /**
     * update a volunteer skill.
     */
    public function update(User $user, VolunteerSkill $skill): bool
    {
        return $skill->volunteerProfile->user_id === $user->id;
    }

    /**
     * delete a volunteer skill.
     */
    public function delete(User $user, VolunteerSkill $skill): bool
    {
        return $skill->volunteerProfile->user_id === $user->id;
    }
}
