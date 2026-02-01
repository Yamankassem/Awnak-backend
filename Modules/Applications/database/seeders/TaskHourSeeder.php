<?php

namespace Modules\Applications\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Applications\Models\TaskHour;

/**
 * TaskHour Seeder
 * 
 * Seeds taskHours table with sample data.
 * 
 * @package Modules\Applications\Database\Seeders
 * @author Your Name
 */
class TaskHourSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * @return void
     */
    public function run(): void
    {
        TaskHour::factory()->count(100)->create();
    }
}
