<?php

namespace Modules\Volunteers\Services;

use Modules\Core\Models\User;
use Modules\Volunteers\Models\VolunteerProfile;
use Modules\Volunteers\Models\VolunteerInterest;
use Modules\Volunteers\Traits\LogsVolunteerActivity;

class VolunteerInterestService
{
    use LogsVolunteerActivity;
    public function handle() {}

    public function list(VolunteerProfile $profile)
    {
        return $profile->interests()->get();
    }

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
