<?php

namespace Modules\Applications\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TaskHourSeeder extends Seeder
{
    public function run(): void
    {
        if (DB::table('tasks')->count() == 0) {
            $this->call(TaskSeeder::class);
        }
        
        $taskHours = [];
        
        for ($i = 1; $i <= 5; $i++) {
            $taskHours[] = [
                'task_id'      => 1,
                'hours'        => 1,
                'started_date' => $this->getRandomStartDate(),
                'ended_date'   => $this->getRandomEndDate(),
                'note'         => $this->getRandomNote(),
                'created_at'   => now(),
                'updated_at'   => now(),
            ];
        }
        
        DB::table('task_hours')->insert($taskHours);
        
        $this->command->info('5 random work hours were successfully generated!');
    }
    
    private function getRandomStartDate(): string
    {
        return now()->subDays(rand(1, 10))->subHours(rand(1, 5))->format('Y-m-d H:i:s');
    }
    
    private function getRandomEndDate(): string
    {
        return now()->subDays(rand(0, 9))->addHours(rand(1, 8))->format('Y-m-d H:i:s');
    }
    
    private function getRandomNote(): string
    {
        $notes = [
            'Good and meticulous work',
            'The task was completed successfully',
            'Needs more time',
            'Excellent performance',
            'Good follow-up'
        ];
        return $notes[array_rand($notes)];
    }
}