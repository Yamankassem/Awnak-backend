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
        // جذور
        $country = Location::updateOrCreate(
            ['type' => 'country', 'name' => 'Syria', 'parent_id' => null],
            ['coordinates' => new Point(35.0, 38.5, 4326)]
        );

        $damascus = Location::updateOrCreate(
            ['type' => 'city', 'name' => 'Damascus', 'parent_id' => $country->id],
            ['coordinates' => new Point(33.5138, 36.2765, 4326)]
        );

        $aleppo = Location::updateOrCreate(
            ['type' => 'city', 'name' => 'Aleppo', 'parent_id' => $country->id],
            ['coordinates' => new Point(36.2021, 37.1343, 4326)]
        );
    }
}
