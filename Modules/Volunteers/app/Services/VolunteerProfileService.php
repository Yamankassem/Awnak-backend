<?php

namespace Modules\Volunteers\Services;

use Modules\Core\Models\User;
use Modules\Volunteers\Models\VolunteerProfile;

class VolunteerProfileService
{
    public function handle() {}

    public function getByUser(User $user): VolunteerProfile
    {
        return VolunteerProfile::with([
            'skills',
            'interests',
            'availability',
            'location',
        ])->where('user_id', $user->id)->firstOrFail();
    }

    public function update(VolunteerProfile $profile, array $data): VolunteerProfile
    {
        $profile->update($data);

        return $profile->refresh();
    }
}
