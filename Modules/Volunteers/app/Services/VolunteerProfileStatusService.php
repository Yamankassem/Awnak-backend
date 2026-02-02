<?php

namespace Modules\Volunteers\Services;

use Modules\Core\Models\User;
use Modules\Volunteers\Models\VolunteerProfile;
/**
 * Class VolunteerProfileStatusService
 *
 * Manages activation and suspension of volunteer profiles
 * with audit logging for administrative actions.
 *
 * @package Modules\Volunteers\Services
 */
class VolunteerProfileStatusService
{
    /**
     * Activate a volunteer profile.
     *
     * Prevents activating an already active profile.
     *
     * @param VolunteerProfile $volunteerProfile
     * @param User $actor
     * @return VolunteerProfile
     */
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

     /**
     * Suspend a volunteer profile.
     *
     * Prevents suspending an already suspended profile.
     *
     * @param VolunteerProfile $volunteerProfile
     * @param User $actor
     * @return VolunteerProfile
     */
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
