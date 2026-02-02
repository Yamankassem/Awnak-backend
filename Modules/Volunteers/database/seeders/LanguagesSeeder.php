<?php

namespace Modules\Volunteers\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Volunteers\Models\Language;

class LanguagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        $languages = [
            ['name' => 'Arabic',     'code' => 'ar'],
            ['name' => 'English',    'code' => 'en'],
            ['name' => 'French',     'code' => 'fr'],
            ['name' => 'German',     'code' => 'de'],
            ['name' => 'Spanish',    'code' => 'es'],
            ['name' => 'Turkish',    'code' => 'tr'],
            ['name' => 'Russian',    'code' => 'ru'],
            ['name' => 'Kurdish',    'code' => 'ku'],
            ['name' => 'Persian',    'code' => 'fa'],
        ];

        foreach ($languages as $language) {
            Language::firstOrCreate(
                ['code' => $language['code']],
                [
                    'name' => $language['name'],
                ]
            );
        }
    }
}
