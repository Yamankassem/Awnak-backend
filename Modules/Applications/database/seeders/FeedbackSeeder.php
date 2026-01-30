<?php

namespace Modules\Applications\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Applications\Models\Feedback;

/**
 * Feedback Seeder
 * 
 * Seeds feedbacks table with sample data.
 * 
 * @package Modules\Applications\Database\Seeders
 * @author Your Name
 */
class FeedbackSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * @return void
     */
    public function run(): void
    {
        Feedback::factory()->count(100)->create();
    }
}
