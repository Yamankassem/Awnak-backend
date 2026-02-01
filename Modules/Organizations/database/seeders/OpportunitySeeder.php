<?php

namespace Modules\Organizations\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Organizations\Models\Opportunity;
use Modules\Organizations\Models\Organization;
use Modules\Core\Models\Location;
use Illuminate\Support\Facades\DB;

class OpportunitySeeder extends Seeder
{
    public function run()
    {
        // إنشاء منظمة عشوائية
        $organization = Organization::inRandomOrder()->first();

        // إنشاء موقع افتراضي (مثلاً دمشق)
        $location = Location::create([
            'name' => 'Damascus Center',
            'type' => 'city',
            'coordinates' => DB::raw("ST_GeomFromText('POINT(36.2765 33.5138)')")
            // POINT(longitude latitude)
        ]);

        // إنشاء فرصة مرتبطة بالمنظمة والموقع
        Opportunity::create([
            'title' => 'Volunteer Teacher',
            'description' => 'Teach basic computer skills to students.',
            'organization_id' => $organization->id,
            'location_id' => $location->id,
        ]);
    }
}
