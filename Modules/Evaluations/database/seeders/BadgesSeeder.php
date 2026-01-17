<?php

namespace Modules\Evaluations\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BadgesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('badges')->insert([
            [
                'name' => 'The best volunteer',
            ],
            [
                'name' => 'Punctual ',
            ],
        ]);
    }
}
