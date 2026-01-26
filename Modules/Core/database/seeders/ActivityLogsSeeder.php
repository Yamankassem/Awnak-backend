<?php

namespace Modules\Core\Database\Seeders;

use Modules\Core\Models\User;
use Illuminate\Database\Seeder;
use Modules\Core\Database\Factories\ActivityLogFactory;

class ActivityLogsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $this->call([]);
        $admin = User::where('email', 'admin@awnak.test')->first();

        if (!$admin) {
            return;
        }

        ActivityLogFactory::create(
            causer: $admin,
            logName: 'core',
            description: 'Seeded core initial data',
            properties: ['source' => 'seeder']
        );
    }
}
