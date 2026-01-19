<?php

namespace Modules\Organizations\Database\Seeders;

use Illuminate\Database\Seeder;

use Modules\Organizations\Database\Seeders\OrganizationSeeder;

use Modules\Organizations\Database\Seeders\OpportunitySeeder;
use Modules\Organizations\Database\Seeders\OpportunitySkillSeeder;
use Modules\Organizations\Database\Seeders\DocumentSeeder;

class OrganizationsDatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            OrganizationSeeder::class,
            OpportunitySeeder::class,
        //    OpportunitySkillSeeder::class,
            DocumentSeeder::class,
        ]);
    }
}
