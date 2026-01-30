<?php

namespace Modules\Volunteers\Services;

use Modules\Volunteers\Models\VolunteerProfile;
use Modules\Volunteers\Models\VolunteerAvailability;

class VolunteerAvailabilityService
{
    public function handle() {}

    public function list(VolunteerProfile $profile)
    {
        return $profile->availability()->get();
    }

    public function create(VolunteerProfile $profile, array $data): VolunteerAvailability
    {
        return $profile->availability()->create($data);
    }

    public function update(VolunteerAvailability $availability, array $data): VolunteerAvailability
    {
        $availability->update($data);
        return $availability->refresh();
    }

    public function delete(VolunteerAvailability $availability): void
    {
        $availability->delete();
    }
}
