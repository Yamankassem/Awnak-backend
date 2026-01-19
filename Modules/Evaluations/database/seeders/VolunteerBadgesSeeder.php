<?php

namespace Modules\Evaluations\Database\Seeders;

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class VolunteerBadgesSeeder  extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $faker = Faker::create();
        DB::table('volunteer_badges')->insert([
            [
                'volunteer_id' => 1,
                'badge_id' => 1,
                'awarded_by' => 1,
                'awarded_at' => $faker->dateTimeBetween('-6 months', 'now'),
            ],
            [
                'volunteer_id' => 2,
                'badge_id' => 2,
                'awarded_by' => 1,
                'awarded_at' => $faker->dateTimeBetween('-6 months', 'now'),
            ],
        ]);
    }
}
