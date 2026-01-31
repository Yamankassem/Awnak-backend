<?php

namespace Modules\Applications\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TaskSeeder extends Seeder
{
    public function run(): void
    {
        if (DB::table('applications')->count() == 0) {
            $this->call(ApplicationSeeder::class);
        }
        
        $tasks = [];
        
        for ($i = 1; $i <= 5; $i++) {
            $status = $this->getRandomStatus();
            
            $tasks[] = [
                'application_id' => rand(1, 5),
                'title'          => $this->getRandomTitle(),
                'description'    => $this->getRandomTaskDescription(),
                'status'         => $this->getRandomTaskStatus(),
                'due_date'       => $this->getRandomDueDate(),
                'created_at'     => now(),
                'updated_at'     => now(),
            ];
        }
        
        DB::table('tasks')->insert($tasks);
        
        $this->command->info('5 random tasks have been successfully created!');
    }
    
    private function getRandomTitle(): string
    {
        $titles = [
            'Preparing educational materials',
            'Beach cleaning',
            'Visiting patients',
            'Distributing meals',
            'Website design'
        ];
        return $titles[array_rand($titles)];
    }
    
    private function getRandomTaskDescription(): string
    {
        $descriptions = [
            'Preparing the necessary materials for activities',
            'Removing waste from the area',
            'Providing psychological and moral support',
            'Distributing food to those in need',
            'Establishing a website for the organization'
        ];
        return $descriptions[array_rand($descriptions)];
    }
    
    private function getRandomTaskStatus(): string
    {
        $statuses = ['pending', 'in_progress', 'completed'];
        return $statuses[array_rand($statuses)];
    }
    
    private function getRandomDueDate(): string
    {
        return now()->addDays(rand(1, 30))->format('Y-m-d H:i:s');
    }
}