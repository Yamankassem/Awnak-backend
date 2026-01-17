<?php

namespace Modules\Applications\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Applications\Models\TaskHour;

class TaskHourSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TaskHour::factory()->count(30)->create();
    }
}
