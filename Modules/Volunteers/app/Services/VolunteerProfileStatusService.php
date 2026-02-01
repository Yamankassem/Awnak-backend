<?php

namespace Modules\Volunteers\Services;

use Modules\Core\Models\User;
use Modules\Volunteers\Models\VolunteerProfile;

class VolunteerProfileStatusService
{
    public function handle() {}

    public function activate(VolunteerProfile $volunteerProfile, User $actor): VolunteerProfile
    {
        if ($volunteerProfile->status === 'active') {
            abort(422, 'Profile already active.');
        }

        $volunteerProfile->update([
            'status' => 'active',
        ]);

        //activity log
        activity('audit')
            ->performedOn($volunteerProfile)
            ->causedBy($actor)
            ->withProperties([
                'action' => 'activate',
            ])
            ->log('volunteer.activated');

        return $volunteerProfile->refresh();
    }

    public function suspend(VolunteerProfile $volunteerProfile, User $actor): VolunteerProfile
    {
        if ($volunteerProfile->status === 'suspended') {
            abort(422, 'Profile already suspended.');
        }

        $volunteerProfile->update([
            'status' => 'suspended',
        ]);

        //activity log
        activity('audit')
            ->performedOn($volunteerProfile)
            ->causedBy($actor)
            ->withProperties([
                'action' => 'suspend',
            ])
            ->log('volunteer.suspended');

        return $volunteerProfile->refresh();
    }
}
