<?php

namespace Modules\Organizations\Database\Seeders;

use Illuminate\Database\Seeder;

use Modules\Organizations\Database\Seeders\OpportunitySeeder;
use Modules\Organizations\Database\Seeders\OrganizationSeeder;


class OrganizationsDatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            OrganizationSeeder::class,
            OpportunitySeeder::class,
          // Translations Seeders
            OrganizationTranslationsSeeder::class,
            OpportunityTranslationsSeeder::class,
            DocumentTranslationsSeeder::class,
            SkillTranslationsSeeder::class,
        ]);
    }
}
