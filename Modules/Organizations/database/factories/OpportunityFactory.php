<?php

namespace Modules\Organizations\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Organizations\Models\Opportunity;
use Modules\Organizations\Models\Organization;

/**
 * Factory: OpportunityFactory
 *
 * Generates fake data for testing and seeding Opportunity entities.
 * Ensures consistency with the opportunities table schema, including
 * title, description, type, dates, status, organization linkage, and location.
 */
class OpportunityFactory extends Factory
{
    protected $model = Opportunity::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title'           => $this->faker->sentence(3),
            'description'     => $this->faker->paragraph(),
            'type'            => $this->faker->randomElement(['volunteering', 'training', 'job']),
            'start_date'      => $this->faker->date(),
            'end_date'        => $this->faker->date(),
            'status'          => $this->faker->randomElement(['approved', 'rejected', 'pending']),
            'organization_id' => Organization::factory(),
            'location'        => $this->faker->point(35.0, 36.5), // Example coordinates (lat, lng)
        ];
    }
}

