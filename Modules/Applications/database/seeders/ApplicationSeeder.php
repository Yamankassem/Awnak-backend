<?php

namespace Modules\Applications\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Applications\Models\Application;

/**
 * Application Seeder
 * 
 * Seeds applications table with sample data.
 * 
 * @package Modules\Applications\Database\Seeders
 * @author Your Name
 */
class ApplicationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * @return void
     */
    public function run(): void
    {
        Application::factory()->count(100)->create();
    }
}
