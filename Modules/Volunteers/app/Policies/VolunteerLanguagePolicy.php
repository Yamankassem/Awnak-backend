<?php

namespace Modules\Volunteers\Policies;

use Modules\Core\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Volunteers\Models\VolunteerLanguage;

class VolunteerLanguagePolicy
{
    use HandlesAuthorization;


    // who has premission profile.update.own to store new Language record
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('profile.update.own');
    }

    // the owner of profile can see Volunteer Language
    public function update(User $user, VolunteerLanguage $language): bool
    {
        return $language->volunteerProfile->user_id === $user->id;
    }

    // the owner of profile can see Volunteer Language
    public function delete(User $user, VolunteerLanguage $language): bool
    {
        return $language->volunteerProfile->user_id === $user->id;
    }
}
