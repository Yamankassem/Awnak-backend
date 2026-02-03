<?php

namespace Modules\Organizations\Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\TranslationLoader\LanguageLine;

class OpportunityTranslationsSeeder extends Seeder
{
    public function run(): void
    {
        LanguageLine::create([
            'group' => 'opportunities',
            'key' => 'created',
            'text' => [
                'en' => 'Opportunity created successfully.',
                'ar' => 'تم إنشاء الفرصة بنجاح.',
            ],
        ]);

        LanguageLine::create([
            'group' => 'opportunities',
            'key' => 'updated',
            'text' => [
                'en' => 'Opportunity updated successfully.',
                'ar' => 'تم تعديل الفرصة بنجاح.',
            ],
        ]);

        LanguageLine::create([
            'group' => 'opportunities',
            'key' => 'deleted',
            'text' => [
                'en' => 'Opportunity deleted successfully.',
                'ar' => 'تم حذف الفرصة بنجاح.',
            ],
        ]);

        LanguageLine::create([
            'group' => 'opportunities',
            'key' => 'retrieved',
            'text' => [
                'en' => 'Opportunity retrieved successfully.',
                'ar' => 'تم جلب الفرصة بنجاح.',
            ],
        ]);

        LanguageLine::create([
            'group' => 'opportunities',
            'key' => 'not_found',
            'text' => [
                'en' => 'Opportunity not found.',
                'ar' => 'الفرصة غير موجودة.',
            ],
        ]);
    }
}
