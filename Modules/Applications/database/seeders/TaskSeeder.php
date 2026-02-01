<?php

namespace Modules\Applications\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Applications\Models\Task;
use Faker\Factory as Faker;

class TaskSeeder extends Seeder
{
    private $faker;
    
    public function __construct()
    {
        $this->faker = Faker::create();
    }
    
    public function run(): void
    {
        $applicationIds = \DB::table('applications')->pluck('id')->toArray();
        
        if (empty($applicationIds)) {
            $this->command->warn('No apps!');
            return;
        }
        
        $tasks = [];
        
        for ($i = 1; $i <= 5; $i++) {
            $tasks[] = [
                'application_id' => $this->faker->randomElement($applicationIds),
                'title'          => $this->getRandomTitle(),
                'description'    => $this->getRandomDescription(),
                'due_date'       => $this->getRandomDueDate(),
                'status'         => $this->getRandomStatus(),
                'created_at'     => now(),
                'updated_at'     => now(),
            ];
        }
        
        Task::insert($tasks);
        $this->command->info('5 tasks were created successfully!');
    }
    
    private function getRandomStatus(): string
    {
        $statuses = ['preparation', 'active', 'complete', 'cancelled'];
        return $this->faker->randomElement($statuses);
    }
    
    private function getRandomTitle(): string
    {
        $titles = [
            'Review volunteer application',
            'Interview the volunteer',
            'Train the volunteer',
            'Performance evaluation'
        ];
        return $this->faker->randomElement($titles);
    }
    
    private function getRandomDescription(): string
    {
        $descriptions = [
            'Review the volunteer application',
            'Conduct an interview with the volunteer',
            'Provide the necessary training',
            'Evaluate the volunteers performance'
        ];
        return $this->faker->randomElement($descriptions);
    }
    
    private function getRandomDueDate()
    {
        return $this->faker->dateTimeBetween('+1 week', '+1 month');
    }
}