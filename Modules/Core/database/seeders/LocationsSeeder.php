<?php

namespace Modules\Core\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Core\Models\Location;
use MatanYadaev\EloquentSpatial\Objects\Point;

class LocationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Country
        $country = Location::firstOrCreate(
            ['type' => 'country', 'name' => 'Syria'],
            ['coordinates' => new Point(35.0, 38.5, 4326)]
        );

        // Cities
        Location::factory()->city(
            country: $country,
            name: 'Damascus'
        )->create([
            'coordinates' => new Point(33.5138, 36.2765, 4326),
        ]);

        Location::factory()->city(
            country: $country,
            name: 'Aleppo'
        )->create([
            'coordinates' => new Point(36.2021, 37.1343, 4326),
        ]);
    }
}
