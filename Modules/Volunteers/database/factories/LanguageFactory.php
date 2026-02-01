<?php

namespace Modules\Volunteers\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class LanguageFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = \Modules\Volunteers\Models\Language::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->languageCode(), 
            'code' => $this->faker->unique()->languageCode(),
            'status' => 'active',
        ];
    }
}

