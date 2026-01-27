<?php

namespace Modules\Volunteers\Database\Factories;

use Modules\Volunteers\Models\Interest;
use Illuminate\Database\Eloquent\Factories\Factory;

class VolunteerInterestFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = \Modules\Volunteers\Models\VolunteerInterest::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'interest_id' => Interest::factory(),
        ];
    }
}

