<?php

namespace Modules\Organizations\Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\TranslationLoader\LanguageLine;

class SkillTranslationsSeeder extends Seeder
{
    public function run(): void
    {
        LanguageLine::create([
            'group' => 'skills',
            'key' => 'attached',
            'text' => [
                'en' => 'Skill attached successfully.',
                'ar' => 'تم ربط المهارة بنجاح.',
            ],
        ]);

        LanguageLine::create([
            'group' => 'skills',
            'key' => 'detached',
            'text' => [
                'en' => 'Skill detached successfully.',
                'ar' => 'تم فك ربط المهارة بنجاح.',
            ],
        ]);

        LanguageLine::create([
            'group' => 'skills',
            'key' => 'not_found',
            'text' => [
                'en' => 'Skill not found.',
                'ar' => 'المهارة غير موجودة.',
            ],
        ]);
    }
}
