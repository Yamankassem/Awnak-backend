<?php

namespace Modules\Core\Database\Seeders;

use Modules\Core\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $this->call([]);
        $admin = User::updateOrCreate(
            ['email' => 'admin@awnak.test'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('Password@123'),
                'status' => 'active',
            ]
        );

        $admin->syncRoles(['system-admin']);

        $volunteer = User::updateOrCreate(
            ['email' => 'volunteer@awnak.test'],
            [
                'name' => 'Volunteer User',
                'password' => Hash::make('Password@123'),
                'status' => 'active',
            ]
        );
        $volunteer->syncRoles(['volunteer']);
    }
}
