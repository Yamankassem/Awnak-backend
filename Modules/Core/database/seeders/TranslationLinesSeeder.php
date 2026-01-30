<?php

namespace Modules\Core\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Core\Database\Factories\TranslationLineFactory;

class TranslationLinesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TranslationLineFactory::create(
            group: 'core',
            key: 'roles.super_admin',
            text: [
                'en' => 'Super Admin',
                'ar' => 'مدير عام',
            ]
        );

        TranslationLineFactory::create(
            group: 'core',
            key: 'roles.volunteer',
            text: [
                'en' => 'Volunteer',
                'ar' => 'متطوع',
            ]
        );

        TranslationLineFactory::create(
            group: 'core',
            key: 'messages.seed_done',
            text: [
                'en' => 'Seed completed',
                'ar' => 'تم تجهيز البيانات',
            ]
        );
    }
}
