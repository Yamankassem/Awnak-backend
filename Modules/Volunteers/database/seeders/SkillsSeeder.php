<?php

namespace Modules\Volunteers\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Volunteers\Models\Skill;

class SkillsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Skill::factory()->count(5)->create();
    }
}
