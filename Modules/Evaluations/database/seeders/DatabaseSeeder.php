<?php

namespace Modules\Evaluations\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Evaluations\Database\Seeders\BadgesDatabaseSeeder;
use Modules\Evaluations\Database\Seeders\BadgesSeeder;
use Modules\Evaluations\Database\Seeders\CertificatesSeeder;
use Modules\Evaluations\Database\Seeders\EvaluationsSeeder;
use Modules\Evaluations\Database\Seeders\VolunteerBadgesSeeder;
use Modules\Evaluations\Database\Seeders\ReportsSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
            $this->call([
            BadgesSeeder::class,
            // CertificatesSeeder::class,
            // EvaluationsSeeder::class,
            // ReportsSeeder::class,
            // VolunteerBadgesSeeder::class,
    ]);
    }
}
