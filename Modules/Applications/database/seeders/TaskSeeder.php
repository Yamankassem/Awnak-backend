<?php

namespace Modules\Applications\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Applications\Models\Task;

/**
 * Task Seeder
 * 
 * Seeds tasks table with sample data.
 * 
 * @package Modules\Applications\Database\Seeders
 * @author Your Name
 */
class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * @return void
     */
    public function run(): void
    {
        Task::factory()->count(100)->create();
    }
}
