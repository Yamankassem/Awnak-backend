<?php

namespace Modules\Evaluations\Database\Seeders;

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class ReportsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        $reportTypes = ['daily', 'weekly', 'monthly'];
            DB::table('reports')->insert([
                'generated_by' => 1, 
                'report_type' => $faker->randomElement($reportTypes),
                'param' => json_encode([
                    'task_id' => 1,
                    'volunteer_id' => 1
                ]),
                'generated_at' => $faker->dateTimeBetween('-1 year', 'now'),
                'url' => $faker->url,
            ]);
        }
    }
