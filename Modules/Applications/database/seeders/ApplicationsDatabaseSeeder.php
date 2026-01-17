<?php

namespace Modules\Applications\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Applications\Database\Seeders\TaskSeeder;
use Modules\Applications\Database\Seeders\FeedbackSeeder;
use Modules\Applications\Database\Seeders\TaskHourSeeder;
use Modules\Applications\Database\Seeders\ApplicationSeeder;

class ApplicationsDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $this->call([
            ApplicationSeeder::class,
            TaskSeeder::class,
            TaskHourSeeder::class,
            FeedbackSeeder::class,
         ]);
    }
}
