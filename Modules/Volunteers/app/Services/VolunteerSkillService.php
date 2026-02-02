<?php

namespace Modules\Volunteers\Services;

use Modules\Core\Models\User;
use Modules\Volunteers\Models\VolunteerSkill;
use Modules\Volunteers\Models\VolunteerProfile;
use Modules\Volunteers\Traits\LogsVolunteerActivity;

class VolunteerSkillService
{
    use LogsVolunteerActivity;
    public function handle() {}

    public function list(VolunteerProfile $profile)
    {
        return $profile->skills()->withPivot('level')->get();
    }

    public function create(VolunteerProfile $profile, array $data, User $actor): VolunteerSkill
    {
        // do not repeat the skill
        $exists = $profile->skills()
            ->where('skill_id', $data['skill_id'])
            ->exists();

        if ($exists) {
            abort(422, 'Skill already added.');
        }

        $skills = VolunteerSkill::create([
            'volunteer_profile_id' => $profile->id,
            'skill_id' => $data['skill_id'],
            'level' => $data['level'],
        ]);

        //activity log
        $this->log(
            'volunteer.skill.added',
            $skills,
            $actor,
            ['skill_id' => $data['skill_id']]
        );
        return $skills;
    }

    public function update(VolunteerSkill $skill, array $data, User $actor): VolunteerSkill
    {
        $skill->update($data);
        
         //activity log
        $this->log(
            'volunteer.skill.updates',
            $skill,
            $actor,
            ['skill_id' => $data['skill_id']]
        );
        
        return $skill->refresh();
    }

    public function delete(VolunteerSkill $skill, User $actor): void
    {
        //activity log
        $this->log(
            'volunteer.skill.deleted',
            $skill,
            $actor
        );
        $skill->delete();
    }
}
