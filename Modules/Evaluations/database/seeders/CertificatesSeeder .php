<?php

namespace Modules\Evaluations\Database\Seeders;

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class CertificatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

            DB::table('certificates')->insert([
                'task_id' =>1,
                'hours' => 10,
                'issued_at' => $faker->dateTimeBetween('-1 year', 'now')->format('Y-m-d'),
            ]);
        }
}
