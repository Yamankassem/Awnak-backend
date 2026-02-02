<?php

namespace Modules\Volunteers\Services;

use Modules\Core\Models\User;
use Modules\Volunteers\Models\VolunteerProfile;
use Modules\Volunteers\Models\VolunteerAvailability;
use Modules\Volunteers\Traits\LogsVolunteerActivity;
/**
 * Class VolunteerAvailabilityService
 *
 * Handles CRUD operations for volunteer availability slots
 * and logs all related volunteer activities.
 *
 * @package Modules\Volunteers\Services
 */
class VolunteerAvailabilityService
{
    use LogsVolunteerActivity;
    /**
     * List all availability entries for a volunteer profile.
     *
     * @param VolunteerProfile $profile
     * @return \Illuminate\Support\Collection
     */
    public function list(VolunteerProfile $profile)
    {
        return $profile->availability()->get();
    }
    /**
     * Create a new availability entry for a volunteer.
     *
     * @param VolunteerProfile $profile
     * @param array<string, mixed> $data
     * @param User $actor
     * @return VolunteerAvailability
     */
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
    /**
     * Update an existing availability entry.
     *
     * @param VolunteerAvailability $availability
     * @param array<string, mixed> $data
     * @param User $actor
     * @return VolunteerAvailability
     */
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

    /**
     * Delete a volunteer availability entry.
     *
     * @param VolunteerAvailability $availability
     * @param User $actor
     * @return void
     */
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
