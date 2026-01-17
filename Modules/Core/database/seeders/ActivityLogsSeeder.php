<?php

namespace Modules\Core\Database\Seeders;

use Modules\Core\Models\User;
use Illuminate\Database\Seeder;

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

        activity('core')
            ->causedBy($admin)
            ->withProperties(['source' => 'seeder'])
            ->log('Seeded core initial data');
    }
}
