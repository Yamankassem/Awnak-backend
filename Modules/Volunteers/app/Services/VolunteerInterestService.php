<?php

namespace Modules\Volunteers\Services;

use Modules\Core\Models\User;
use Modules\Volunteers\Models\VolunteerProfile;
use Modules\Volunteers\Models\VolunteerInterest;
use Modules\Volunteers\Traits\LogsVolunteerActivity;
/**
 * Class VolunteerInterestService
 *
 * Manages volunteer interests (attach, update, remove)
 * and records activity logs for auditing.
 *
 * @package Modules\Volunteers\Services
 */
class VolunteerInterestService
{
    use LogsVolunteerActivity;

    /**
     * Retrieve all interests associated with a volunteer profile.
     *
     * @param VolunteerProfile $profile
     * @return \Illuminate\Support\Collection
     */
    public function list(VolunteerProfile $profile)
    {
        return $profile->interests()->get();
    }

    /**
     * Attach a new interest to a volunteer profile.
     *
     * Prevents duplicate interests.
     *
     * @param VolunteerProfile $profile
     * @param array<string, mixed> $data
     * @param User $actor
     * @return VolunteerInterest
     */
    public function create(VolunteerProfile $profile, array $data, User $actor): VolunteerInterest
    {
        if ($profile->interests()->where('interest_id', $data['interest_id'])->exists()) {
            abort(422, 'Interest already added.');
        }

        $interest = VolunteerInterest::create([
            'volunteer_profile_id' => $profile->id,
            'interest_id' => $data['interest_id'],
        ]);
        //activity log
        $this->log(
            'volunteer.interest.added',
            $interest,
            $actor,
            ['interest_id' => $data['interest_id']]
        );
        return  $interest;
    }

    /**
     * Update an existing volunteer interest.
     *
     * @param VolunteerInterest $interest
     * @param array<string, mixed> $data
     * @param User $actor
     * @return VolunteerInterest
     */
    public function update(VolunteerInterest $interest, array $data, User $actor): VolunteerInterest
    {
        $interest->update($data);
        //activity log
        $this->log(
            'volunteer.interest.updated',
            $interest,
            $actor,
            ['interest_id' => $data['interest_id']]
        );
        return $interest->refresh();
    }

    /**
     * Remove an interest from a volunteer profile.
     *
     * @param VolunteerInterest $interest
     * @param User $actor
     * @return void
     */
    public function delete(VolunteerInterest $interest, User $actor): void
    {
        $interest->delete();
        //activity log
        $this->log(
            'volunteer.interest.deleted',
            $interest,
            $actor,
        );
    }
}
