<?php

namespace Modules\Evaluations\Database\Seeders;

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class EvaluationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $faker = Faker::create();
        DB::table('evaluations')->insert([
            [
                'task_id' => 1,                
                'evaluator_id' => 1,      
                'score' => 4, 
            ],
             [
                'task_id' => 2,                  
                'evaluator_id' => 1,      
                'score' => 2.5, 
            ],
             [
                'task_id' => 3,                
                'evaluator_id' => 1,      
                'score' => 3, 
            ],
        ]);
    }
}
