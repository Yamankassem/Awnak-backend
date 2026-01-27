<?php

namespace Modules\Applications\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Applications\Models\ArchivedApplication;

class ArchivedApplicationFactory extends Factory
{
    protected $model = ArchivedApplication::class;

    public function definition(): array
    {
        return [
            'original_id' => $this->faker->unique()->numberBetween(1000, 9999),
            'description' => $this->faker->paragraph,
            'status' => $this->faker->randomElement(['pending', 'approved', 'rejected']),
            'deleted_reason' => $this->faker->randomElement(['complete', 'cancelled', 'duplicate', 'inappropriate']),
            'original_created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'original_updated_at' => $this->faker->dateTimeBetween('-6 months', 'now'),
        ];
    }
}