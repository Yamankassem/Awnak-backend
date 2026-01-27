<?php

namespace Modules\Organizations\Database\Seeders;


use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\Volunteers\Models\Skill;
use Modules\Organizations\Models\Opportunity;


class OpportunitySkillSeeder extends Seeder
{
    public function run()
    {
        //  Guard clauses (إجباري)
        if (!Skill::exists() || !Opportunity::exists()) {
            $this->command?->warn('Skipping OpportunitySkillSeeder: missing skills or opportunities');
            return;
        }

        $opportunities = Opportunity::all();
        $skills = Skill::pluck('id');

        foreach ($opportunities as $opportunity) {
            $opportunity->skills()->syncWithoutDetaching(
                $skills->random(2)->toArray()
            );
        }
    }
}
