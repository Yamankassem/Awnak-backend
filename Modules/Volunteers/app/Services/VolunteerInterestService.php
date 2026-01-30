<?php

namespace Modules\Volunteers\Services;

use Modules\Volunteers\Models\VolunteerProfile;
use Modules\Volunteers\Models\VolunteerInterest;

class VolunteerInterestService
{
    public function handle() {}

    public function list(VolunteerProfile $profile)
    {
        return $profile->interests()->get();
    }

    public function create(VolunteerProfile $profile, array $data): VolunteerInterest
    {
        if ($profile->interests()->where('interest_id', $data['interest_id'])->exists()) {
            abort(422, 'Interest already added.');
        }

        return VolunteerInterest::create([
            'volunteer_profile_id' => $profile->id,
            'interest_id' => $data['interest_id'],
        ]);
    }

    public function update(VolunteerInterest $interest, array $data): VolunteerInterest
    {
        $interest->update($data);
        return $interest->refresh();
    }

    public function delete(VolunteerInterest $interest): void
    {
        $interest->delete();
    }
}
