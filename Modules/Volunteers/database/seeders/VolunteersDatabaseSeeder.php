<?php

namespace Modules\Volunteers\Database\Seeders;

use Illuminate\Database\Seeder;

class VolunteersDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🌱 Seeding Volunteers Module...');

        $this->call([
            SkillsSeeder::class,
            InterestsSeeder::class,
            LanguagesSeeder::class,
            VolunteerProfilesSeeder::class,
        ]);

        $this->command->info('✅ Volunteers Module Seeded Successfully');
    }
}
