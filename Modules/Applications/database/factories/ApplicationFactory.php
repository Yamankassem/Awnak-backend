<?php

namespace Modules\Applications\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ApplicationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = \Modules\Applications\Models\ApplicationFactory::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'opportunity_id' => $this->faker->Opportunity::factory(),
            'volunteer_id' => $this->faker->Volunteer::factory(),
            'coordinator_id' => $this->faker->Coordinator::factory(),
            'assigned_at' => $this->faker->date('Y-m-d H:i:s'),
            'description' => $this->faker->paragraph,
        ];
    }
}

