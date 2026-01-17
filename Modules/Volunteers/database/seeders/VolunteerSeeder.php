<?php

namespace Modules\Volunteers\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\Volunteers\app\Models\VolunteerProfile;
use Modules\Volunteers\app\Models\VolunteerSkill;
use Modules\Volunteers\app\Models\VolunteerInterest;
use Modules\Volunteers\app\Models\Availability;
class VolunteerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🌱 Creating sample volunteer...');

        // Create a volunteer profile
        $volunteerId = DB::table('volunteer_profiles')->insertGetId([
            'user_id' => 1,
            'location_id' => 1,
            'first_name' => 'أحمد',
            'last_name' => 'محمد',
            'phone' => '+966501234567',
            'gender' => 'male',
            'birth_date' => '1995-05-15',
            'bio' => 'متطوع شغوف بمساعدة المجتمع والعمل الإنساني. لدي خبرة في التدريس والدعم النفسي.',
            'status' => 'active',
            'is_verified' => true,
            'verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Add skills
        $skills = [
            ['volunteer_profile_id' => $volunteerId, 'skill_name' => 'teaching', 'level' => 'advanced', 'created_at' => now(), 'updated_at' => now()],
            ['volunteer_profile_id' => $volunteerId, 'skill_name' => 'first_aid', 'level' => 'intermediate', 'created_at' => now(), 'updated_at' => now()],
            ['volunteer_profile_id' => $volunteerId, 'skill_name' => 'counseling', 'level' => 'beginner', 'created_at' => now(), 'updated_at' => now()],
        ];
        DB::table('volunteer_skills')->insert($skills);

        // Add interests
        $interests = [
            ['volunteer_profile_id' => $volunteerId, 'interest_name' => 'education', 'created_at' => now(), 'updated_at' => now()],
            ['volunteer_profile_id' => $volunteerId, 'interest_name' => 'psychological_support', 'created_at' => now(), 'updated_at' => now()],
            ['volunteer_profile_id' => $volunteerId, 'interest_name' => 'youth_programs', 'created_at' => now(), 'updated_at' => now()],
        ];
        DB::table('volunteer_interests')->insert($interests);

        // Add availability
        $availability = [
            ['volunteer_profile_id' => $volunteerId, 'day' => 'saturday', 'start_time' => '09:00', 'end_time' => '15:00', 'created_at' => now(), 'updated_at' => now()],
            ['volunteer_profile_id' => $volunteerId, 'day' => 'sunday', 'start_time' => '09:00', 'end_time' => '15:00', 'created_at' => now(), 'updated_at' => now()],
            ['volunteer_profile_id' => $volunteerId, 'day' => 'monday', 'start_time' => '17:00', 'end_time' => '21:00', 'created_at' => now(), 'updated_at' => now()],
        ];
        DB::table('volunteer_availability')->insert($availability);

        $this->command->info('✅ Sample volunteer created successfully!');
        $this->command->info("   ID: {$volunteerId}");
        $this->command->info("   Name: أحمد محمد");
        $this->command->info("   Skills: 3");
        $this->command->info("   Interests: 3");
        $this->command->info("   Availability Slots: 3");
    }
}