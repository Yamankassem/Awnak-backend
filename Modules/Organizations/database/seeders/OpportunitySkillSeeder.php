<?php

namespace Modules\Organizations\Database\Seeders;


use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class OpportunitySkillSeeder extends Seeder
{
    public function run()
    {
        DB::table('opportunity_skill')->insert([
            ['opportunity_id' => 1, 'skill_id' => 3], // Volunteer Teacher needs Teaching
            ['opportunity_id' => 1, 'skill_id' => 1], // Volunteer Teacher needs Communication
            ['opportunity_id' => 2, 'skill_id' => 2], // Medical Aid Volunteer needs First Aid
        ]);
    }
}
