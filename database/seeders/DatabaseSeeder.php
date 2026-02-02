<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;


class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Core first (users, roles, locations)
    $this->call([
        \Modules\Core\Database\Seeders\CoreDatabaseSeeder::class,
    ]);

    // Organizations
    $this->call([
        \Modules\Organizations\Database\Seeders\OrganizationsDatabaseSeeder::class,
    ]);

    // Volunteers
    $this->call([
        \Modules\Volunteers\Database\Seeders\SkillsSeeder::class,
        \Modules\Volunteers\Database\Seeders\InterestsSeeder::class,
        \Modules\Volunteers\Database\Seeders\LanguagesSeeder::class,
        \Modules\Volunteers\Database\Seeders\VolunteerProfilesSeeder::class,
    ]);

    // Applications (depends on opportunities + volunteers)
    $this->call([
        \Modules\Applications\Database\Seeders\ApplicationsDatabaseSeeder::class,
    ]);

    // Evaluations (depends on tasks)
    $this->call([
        \Modules\Evaluations\Database\Seeders\EvaluationsDatabaseSeeder::class,
    ]);
    }
}
