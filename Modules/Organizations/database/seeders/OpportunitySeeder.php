<?php

namespace Modules\Organizations\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Organizations\Models\Opportunity;
use Modules\Organizations\Models\Organization;

class OpportunitySeeder extends Seeder
{
    public function run()
    {
        Opportunity::create([
            'title' => 'Volunteer Teacher',
            'description' => 'Teach basic computer skills to students.',
            'organization_id' => Organization::inRandomOrder()->first()->id,
        ]);


    }
}
