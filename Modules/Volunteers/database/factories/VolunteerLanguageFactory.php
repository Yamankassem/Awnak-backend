<?php

namespace Modules\Volunteers\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Volunteers\Models\language;

class VolunteerLanguageFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = \Modules\Volunteers\Models\VolunteerLanguage::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'language_id' => language::factory(),
            'level' => $this->faker->randomElement(['basic', 'intermediate', 'fluent', 'native']),
        ];
    }
}

