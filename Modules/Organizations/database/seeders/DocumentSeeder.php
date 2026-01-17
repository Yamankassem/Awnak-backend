<?php

namespace Modules\Organizations\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Organizations\Models\Document;

class DocumentSeeder extends Seeder
{
    public function run()
    {
        Document::create([
            'opportunity_id' => 1,
            'title' => 'Annual Report 2025',
            'file_path' => 'documents/annual_report_2025.pdf',
        ]);
    }
}
