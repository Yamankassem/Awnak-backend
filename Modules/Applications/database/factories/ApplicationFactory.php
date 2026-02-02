<?php

namespace Modules\Applications\Database\Factories;

use Modules\Volunteers\Models\VolunteerProfile;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Application Factory
 * 
 * Generates fake application data for testing and seeding.
 * 
 * @package Modules\Applications\Database\Factories
 * @author Your Name
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\Modules\Applications\Models\Application>
 */
class ApplicationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     * 
     * @var string
     */
    protected $model = \Modules\Applications\Models\ApplicationFactory::class;

    /**
     * Define the model's default state.
     * 
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'opportunity_id' => Opportunity::factory(),
            'volunteer_id'   => VolunteerProfile::factory(),
            'coordinator_id' => User::factory(),
            'assigned_at'    => $this->faker->date('Y-m-d H:i:s'),
            'description'    => $this->faker->paragraph,
        ];
    }
}

