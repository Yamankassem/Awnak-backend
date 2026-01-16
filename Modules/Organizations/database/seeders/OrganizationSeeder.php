<?php

namespace Modules\Organizations\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Organizations\Models\Organization;

class OrganizationSeeder extends Seeder
{
    public function run()
    {
        Organization::create([
            'license_number' => 'LIC-001',
            'type' => 'NGO',
            'bio' => 'Humanitarian organization providing aid and relief.',
            'website' => 'https://redcrescent.org',
        ]);
    }
}
