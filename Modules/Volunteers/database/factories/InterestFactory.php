<?php

namespace Modules\Volunteers\Database\Factories;

use Illuminate\Support\Str;
use Modules\Volunteers\Models\Interest;
use Illuminate\Database\Eloquent\Factories\Factory;

class InterestFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = Interest::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        $name = $this->faker->unique()->word();

        return [
            'name' => ucfirst($name),
            'slug' => Str::slug($name),
        ];
    }
}
