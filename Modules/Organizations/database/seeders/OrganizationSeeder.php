<?php

namespace Modules\Organizations\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Organizations\Models\Organization;
/**
 * Seeder: OrganizationSeeder
 *
 * Seeds the organizations table with initial data.
 * Provides a sample organization record for testing and development.
 *
 * Notes:
 * - Requires that a user with id=1 exists in the users table.
 * - Useful for local development and demo environments.
 *
 * Example usage:
 * php artisan db:seed --class=Modules\\Organizations\\Database\\Seeders\\OrganizationSeeder
 */
class OrganizationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Creates a sample organization with license number, type, bio,
     * website, and links it to an existing user.
     *
     * @return void
     */
    public function run()
    {
        Organization::create([
            'license_number' => 'LIC-001',
            'type' => 'NGO',
            'bio' => 'Humanitarian organization providing aid and relief.',
            'website' => 'https://redcrescent.org',
            'user_id' => 1, // Must match an existing user in the users table 
        ]);
    }
}
