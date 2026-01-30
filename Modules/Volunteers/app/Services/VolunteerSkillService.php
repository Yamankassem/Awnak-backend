<?php

namespace Modules\Volunteers\Services;

use Modules\Volunteers\Models\VolunteerSkill;
use Modules\Volunteers\Models\VolunteerProfile;

class VolunteerSkillService
{
    public function handle() {}

    public function list(VolunteerProfile $profile)
    {
        return $profile->skills()->withPivot('level')->get();
    }

    public function create(VolunteerProfile $profile, array $data): VolunteerSkill
    {
        // منع التكرار
        $exists = $profile->skills()
            ->where('skill_id', $data['skill_id'])
            ->exists();

        if ($exists) {
            abort(422, 'Skill already added.');
        }

        return VolunteerSkill::create([
            'volunteer_profile_id' => $profile->id,
            'skill_id' => $data['skill_id'],
            'level' => $data['level'],
        ]);
    }

    public function update(VolunteerSkill $skill, array $data): VolunteerSkill
    {
        $skill->update($data);
        return $skill->refresh();
    }

    public function delete(VolunteerSkill $skill): void
    {
        $skill->delete();
    }
}
