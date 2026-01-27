<?php

namespace Modules\Organizations\Database\Factories;

use Modules\Volunteers\Models\Skill;
use Modules\Organizations\Models\OpportunitySkill;
use Illuminate\Database\Eloquent\Factories\Factory;

class OpportunitySkillFactory extends Factory
{
    protected $model = OpportunitySkill::class;

    public function definition()
    {
        return [
            'opportunity_id' => \Modules\Organizations\Models\Opportunity::factory(),
            'skill_id'       => Skill::factory(), 
        ];
    }
}
