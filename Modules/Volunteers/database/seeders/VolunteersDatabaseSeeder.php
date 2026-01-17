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
        $this->call([
            VolunteerSeeder::class,
        ]);
    }
}
