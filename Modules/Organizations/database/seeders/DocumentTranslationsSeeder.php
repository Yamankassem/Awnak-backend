<?php

namespace Modules\Organizations\Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\TranslationLoader\LanguageLine;

class DocumentTranslationsSeeder extends Seeder
{
    public function run(): void
    {
        LanguageLine::create([
            'group' => 'documents',
            'key' => 'uploaded',
            'text' => [
                'en' => 'Document uploaded successfully.',
                'ar' => 'تم رفع المستند بنجاح.',
            ],
        ]);

        LanguageLine::create([
            'group' => 'documents',
            'key' => 'updated',
            'text' => [
                'en' => 'Document updated successfully.',
                'ar' => 'تم تعديل المستند بنجاح.',
            ],
        ]);

        LanguageLine::create([
            'group' => 'documents',
            'key' => 'deleted',
            'text' => [
                'en' => 'Document deleted successfully.',
                'ar' => 'تم حذف المستند بنجاح.',
            ],
        ]);

        LanguageLine::create([
            'group' => 'documents',
            'key' => 'retrieved',
            'text' => [
                'en' => 'Document retrieved successfully.',
                'ar' => 'تم جلب المستند بنجاح.',
            ],
        ]);

        LanguageLine::create([
            'group' => 'documents',
            'key' => 'not_found',
            'text' => [
                'en' => 'Document not found.',
                'ar' => 'المستند غير موجود.',
            ],
        ]);

        LanguageLine::create([
            'group' => 'documents',
            'key' => 'update_failed',
            'text' => [
                'en' => 'Failed to update document.',
                'ar' => 'فشل تعديل المستند.',
            ],
        ]);

        LanguageLine::create([
            'group' => 'documents',
            'key' => 'delete_failed',
            'text' => [
                'en' => 'Failed to delete document.',
                'ar' => 'فشل حذف المستند.',
            ],
        ]);
    }
}
