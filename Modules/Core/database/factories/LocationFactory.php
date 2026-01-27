<?php

namespace Modules\Core\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Core\Models\Location;
use MatanYadaev\EloquentSpatial\Objects\Point;

/**
 * @extends Factory<Location>
 */
class LocationFactory extends Factory
{
    protected $model = Location::class;

    public function definition(): array
    {
        return [
            'type' => 'city',
            'name' => $this->faker->city(),
            'parent_id' => null,
            'coordinates' => new Point(
                $this->faker->latitude(),
                $this->faker->longitude(),
                4326
            ),
        ];
    }

    /**
     * Country state.
     */
    public function country(string $name = 'Country'): static
    {
        return $this->state(fn() => [
            'type' => 'country',
            'name' => $name,
            'parent_id' => null,
        ]);
    }

    /**
     * City under a given country.
     */
    public function city(Location $country, ?string $name = null): static
    {
        return $this->state(fn() => [
            'type' => 'city',
            'name' => $name ?? $this->faker->city(),
            'parent_id' => $country->id,
        ]);
    }
}
