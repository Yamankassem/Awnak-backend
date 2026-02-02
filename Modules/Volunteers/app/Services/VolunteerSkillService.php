<?php

namespace Modules\Volunteers\Services;

use Modules\Core\Models\User;
use Modules\Volunteers\Models\VolunteerSkill;
use Modules\Volunteers\Models\VolunteerProfile;
use Modules\Volunteers\Traits\LogsVolunteerActivity;

class VolunteerSkillService
{
    use LogsVolunteerActivity;

    /**
     * List all skills associated with a volunteer profile.
     *
     * @param VolunteerProfile $profile
     * @return \Illuminate\Support\Collection
     */
    public function list(VolunteerProfile $profile)
    {
        return $profile->skills()->withPivot('level')->get();
    }
    /**
     * Attach a new skill to a volunteer profile.
     *
     * Prevents duplicate skill assignment.
     *
     * @param VolunteerProfile $profile
     * @param array{skill_id:int, level:string} $data
     * @param User $actor
     * @return VolunteerSkill
     */
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
     /**
     * Update a volunteer skill level.
     *
     * @param VolunteerSkill $skill
     * @param array{level:string} $data
     * @param User $actor
     * @return VolunteerSkill
     */
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
    /**
     * Remove a skill from a volunteer profile.
     *
     * @param VolunteerSkill $skill
     * @param User $actor
     * @return void
     */
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
