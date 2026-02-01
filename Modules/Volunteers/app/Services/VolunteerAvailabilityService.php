<?php

namespace Modules\Volunteers\Services;

use Modules\Core\Models\User;
use Modules\Volunteers\Models\VolunteerProfile;
use Modules\Volunteers\Models\VolunteerAvailability;
use Modules\Volunteers\Traits\LogsVolunteerActivity;

class VolunteerAvailabilityService
{
    use LogsVolunteerActivity;
    public function handle() {}

    public function list(VolunteerProfile $profile)
    {
        return $profile->availability()->get();
    }

    public function create(VolunteerProfile $profile, array $data, User $actor): VolunteerAvailability
    {
        $availability = $profile->availability()->create($data);
        //activity log
        $this->log(
            'volunteer.availability.added',
            $availability,
            $actor,
            $data
        );
        return $availability;
    }

    public function update(VolunteerAvailability $availability, array $data, User $actor): VolunteerAvailability
    {
        $availability->update($data);
        //activity log
        $this->log(
            'volunteer.availability.updated',
            $availability,
            $actor,
            $data
        );
        return $availability->refresh();
    }

    public function delete(VolunteerAvailability $availability, User $actor): void
    {
        $availability->delete();
        //activity log
        $this->log(
            'volunteer.availability.deleted',
            $availability,
            $actor
        );
    }
}
