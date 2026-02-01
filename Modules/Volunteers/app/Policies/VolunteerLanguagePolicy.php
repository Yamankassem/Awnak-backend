<?php

namespace Modules\Volunteers\Policies;

use Modules\Core\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Volunteers\Models\VolunteerLanguage;

class VolunteerLanguagePolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     */
    public function __construct() {}

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('profile.update.own');
    }

    public function update(User $user, VolunteerLanguage $language): bool
    {
        return $language->volunteerProfile->user_id === $user->id;
    }

    public function delete(User $user, VolunteerLanguage $language): bool
    {
        return $language->volunteerProfile->user_id === $user->id;
    }
}
