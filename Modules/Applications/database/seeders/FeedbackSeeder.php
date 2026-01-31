<?php

namespace Modules\Applications\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FeedbackSeeder extends Seeder
{
    public function run(): void
    {
        if (DB::table('tasks')->count() == 0) {
            $this->call(TaskSeeder::class);
        }
        
        $feedbacks = [];
        
        for ($i = 1; $i <= 5; $i++) {
            $feedbacks[] = [
                'task_id'      => 1,
                'name_of_org'  => $this->getRandomOrgName(),
                'name_of_vol'  => $this->getRandomVolName(),
                'rating'       => rand(1, 5),
                'comment'      => $this->getRandomComment(),
                'created_at'   => now(),
                'updated_at'   => now(),
            ];
        }
        
        DB::table('feedbacks')->insert($feedbacks);
        
        $this->command->info('5 random reviews were successfully created!' );
    }
    
    private function getRandomOrgName(): string
    {
        $orgs = [
            'Hope School' ,
            'Environment Association' ,
            'Al-Rahma Hospital' ,
            'Al-Ihsan Association' ,
            'Technology Center'
        ];
        return $orgs[array_rand($orgs)];
    }
    
    private function getRandomVolName(): string
    {
        $names = [
            'Ahmed Mohamed',
            'Sara Ali',
            'Mohamed Khaled',
            'Fatima Abdullah',
            'Khaled Saeed'
        ];
        return $names[array_rand($names)];
    }
    
    private function getRandomComment(): string
    {
        $comments = [
            'Excellent work and great effort',
            'Good level but needs improvement',
            'Wonderful and outstanding performance',
            'Happy to collaborate with you',
            'Thank you for your effort'
        ];
        return $comments[array_rand($comments)];
    }
}