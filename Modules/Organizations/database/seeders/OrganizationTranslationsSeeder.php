<?php

namespace Modules\Organizations\Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\TranslationLoader\LanguageLine;

class OrganizationTranslationsSeeder extends Seeder
{
    public function run(): void
    {
        LanguageLine::create([
            'group' => 'organizations',
            'key' => 'created',
            'text' => [
                'en' => 'Organization created successfully.',
                'ar' => 'تم إنشاء المنظمة بنجاح.',
            ],
        ]);

        LanguageLine::create([
            'group' => 'organizations',
            'key' => 'updated',
            'text' => [
                'en' => 'Organization updated successfully.',
                'ar' => 'تم تعديل المنظمة بنجاح.',
            ],
        ]);

        LanguageLine::create([
            'group' => 'organizations',
            'key' => 'deleted',
            'text' => [
                'en' => 'Organization deleted successfully.',
                'ar' => 'تم حذف المنظمة بنجاح.',
            ],
        ]);

        LanguageLine::create([
            'group' => 'organizations',
            'key' => 'retrieved',
            'text' => [
                'en' => 'Organizations retrieved successfully.',
                'ar' => 'تم جلب المنظمات بنجاح.',
            ],
        ]);

        LanguageLine::create([
            'group' => 'organizations',
            'key' => 'not_found',
            'text' => [
                'en' => 'Organization not found.',
                'ar' => 'المنظمة غير موجودة.',
            ],
        ]);

        LanguageLine::create([
            'group' => 'organizations',
            'key' => 'activated',
            'text' => [
                'en' => 'Organization activated.',
                'ar' => 'تم تفعيل المنظمة.',
            ],
        ]);
    }
}
