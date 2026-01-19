<?php

namespace Modules\Organizations\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Organizations\Models\OpportunitySkill;

class OpportunitySkillFactory extends Factory
{
    protected $model = OpportunitySkill::class;

    public function definition()
    {
        return [
            'opportunity_id' => \Modules\Organizations\Models\Opportunity::factory(),
            'skill_id'       => $this->faker->numberBetween(1, 50), // أو Skill::factory() إذا عندك موديل Skill
        ];
    }
}
