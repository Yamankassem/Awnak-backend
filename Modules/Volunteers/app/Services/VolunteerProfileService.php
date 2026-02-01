<?php

namespace Modules\Volunteers\Services;

use Modules\Core\Models\User;
use Modules\Applications\Models\Application;
use Modules\Volunteers\Models\VolunteerProfile;
use Modules\Volunteers\Traits\LogsVolunteerActivity;

class VolunteerProfileService
{
    use LogsVolunteerActivity;
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

    public function update(VolunteerProfile $profile, array $data, User $actor): VolunteerProfile
    {
        $profile->update($data);

        $this->log(
            'volunteer.profile.updated',
            $profile,
            $actor,
            ['changed_fields' => array_keys($data)]
        );

        return $profile->refresh();
    }

    public function list(User $user)
    {
        return Application::with([
                'opportunity:id,title',
                'task:id,title'
            ])
            ->where('volunteer_id', $user->id)
            ->orderByDesc('created_at')
            ->get();
    }
}
