<?php

namespace Modules\Volunteers\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class VolunteerSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('🌱 Starting Modular Seeding...');

        // 1. إنشاء المهارات المرجعية أولاً (لأن الجداول الوسيطة تحتاج IDs)
        $skillId = DB::table('skills')->insertGetId([
            'name' => 'Teaching',
            'slug' => 'teaching',
            'created_at' => now()
        ]);

        // 2. إنشاء الاهتمامات المرجعية
        $interestId = DB::table('interests')->insertGetId([
            'name' => 'Education',
            'slug' => 'education',
            'created_at' => now()
        ]);

        // 3. إنشاء بروفايل المتطوع (نربطه بـ User ID = 1)
        $volunteerId = DB::table('volunteer_profiles')->insertGetId([
            'user_id' => 1, // تأكد أن المستخدم رقم 1 موجود
            'first_name' => 'أحمد',
            'last_name' => 'محمد',
            'phone' => '+966501234567',
            'status' => 'active',
            'experience_years' => 5,
            'previous_experience_details' => 'Worked in various educational institutions.',
            'created_at' => now(),
        ]);

        // 4. الربط في الجداول الوسيطة
        DB::table('volunteer_skills')->insert([
            'volunteer_profile_id' => $volunteerId,
            'skill_id' => $skillId, 
            'level' => 'advanced',
            'created_at' => now()
        ]);

        DB::table('volunteer_interests')->insert([
            'volunteer_profile_id' => $volunteerId,
            'interest_id' => $interestId,
            'created_at' => now()
        ]);

        DB::table('volunteer_availability')->insert([
            [
                'volunteer_profile_id' => $volunteerId,
                'day' => 'sunday',
                'start_time' => '08:00',
                'end_time' => '12:00',
                'created_at' => now()
            ],
            [
                'volunteer_profile_id' => $volunteerId,
                'day' => 'monday',
                'start_time' => '16:00',
                'end_time' => '20:00',
                'created_at' => now()
            ],
        ]);

        $this->command->info('✅ Modular Seeding Completed Successfully!');
    }
}