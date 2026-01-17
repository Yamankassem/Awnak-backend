<?php

namespace Modules\Core\Database\Seeders;

use Illuminate\Database\Seeder;

class TranslationLinesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $this->call([]);
        $model = config('translation-loader.model') ?? \Spatie\TranslationLoader\LanguageLine::class;

        // key = roles.super_admin -> تستدعيها: __('core.roles.super_admin')
        $model::updateOrCreate(
            ['group' => 'core', 'key' => 'roles.super_admin'],
            ['text' => ['en' => 'Super Admin', 'ar' => 'مدير عام']]
        );

        $model::updateOrCreate(
            ['group' => 'core', 'key' => 'roles.volunteer'],
            ['text' => ['en' => 'Volunteer', 'ar' => 'متطوع']]
        );

        $model::updateOrCreate(
            ['group' => 'core', 'key' => 'messages.seed_done'],
            ['text' => ['en' => 'Seed completed', 'ar' => 'تم تجهيز البيانات']]
        );
    }
}
