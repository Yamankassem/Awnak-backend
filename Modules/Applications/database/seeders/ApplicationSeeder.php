<?php

namespace Modules\Applications\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ApplicationSeeder extends Seeder
{
    public function run(): void
    {
        $applications = [];
        
        for ($i = 1; $i <= 5; $i++) {
            $status = $this->getRandomStatus();
            
            $applications[] = [
                'opportunity_id' => rand(1, 3), 
                'volunteer_id'   => rand(1, 3),
                'coordinator_id' => rand(1, 2), 
                'status'         => $this->getRandomStatus(), 
                'description'    => $this->getRandomDescription(), 
                'assigned_at'    => $this->getAssignedAtBasedOnStatus($status), 
                'created_at'     => now(),
                'updated_at'     => now(),
            ];
        }
        
        DB::table('applications')->insert($applications);
        
        $this->command->info('5 random volunteer requests have been successfully created!');
    }
    
    private function getRandomStatus(): string
    {
        $statuses = ['pending', 'approved', 'rejected', 'under_review'];
        return $statuses[array_rand($statuses)];
    }
    
    private function getRandomDescription(): string
    {
        $descriptions = [
            'I want to volunteer to help the community',
            'I am interested in volunteering in the field of education',
            'I am looking for an opportunity to develop my skills',
            'I want to contribute to charitable work',
            'I am interested in environmental volunteering'
        ];
        return $descriptions[array_rand($descriptions)];
    }
    
    private function getRandomMotivation(): string
    {
        $motivations = [
            'To contribute to community service',
            'To gain practical experience',
            'To develop personal skills',
            'To meet new people',
            'To achieve a personal accomplishment'
        ];
        return $motivations[array_rand($motivations)];
    }

    private function getAssignedAtBasedOnStatus(string $status): ?string
    {
        if (in_array($status, ['approved', 'under_review'])) {
            return now()->subDays(rand(1, 30))->format('Y-m-d H:i:s');
        }
        return null;
    }
}