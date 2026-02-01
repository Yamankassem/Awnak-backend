<?php

namespace Modules\Volunteers\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Volunteers\Models\Skill;
use Modules\Volunteers\Models\Interest;
use Modules\Volunteers\Models\Language;
use Modules\Volunteers\Models\VolunteerProfile;
use Modules\Volunteers\Models\VolunteerAvailability;

class VolunteerProfilesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        VolunteerProfile::factory()
            ->count(3)
            ->create()
            ->each(function ($profile) {
                $profile->skills()->attach(
                    Skill::inRandomOrder()->take(2)->pluck('id'),
                    ['level' => 'advanced']
                );

                $profile->languages()->attach(
                    Language::inRandomOrder()->take(2)->pluck('id'),
                    ['level' => 'intermediate']
                );

                $profile->interests()->attach(
                    Interest::inRandomOrder()->take(2)->pluck('id')
                );

                $slots = [
                    ['day' => 'friday', 'start_time' => '08:00', 'end_time' => '12:00'],
                    ['day' => 'monday', 'start_time' => '16:00', 'end_time' => '20:00'],
                ];

                foreach ($slots as $slot) {
                    $profile->availability()->updateOrCreate(
                        [
                            'volunteer_profile_id' => $profile->id,
                            'day' => $slot['day'],
                            'start_time' => $slot['start_time'],
                            'end_time' => $slot['end_time'],
                        ],
                        []
                    );
                }
            });
    }
}
