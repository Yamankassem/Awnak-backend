<?php

namespace Modules\Organizations\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Organizations\Models\Opportunity;

class OpportunityFactory extends Factory
{
    protected $model = Opportunity::class;

    public function definition()
    {
        return [
            'title'          => $this->faker->sentence(3),
            'description'    => $this->faker->paragraph(),
            'type'           => $this->faker->randomElement(['volunteering', 'training', 'job']),
            'start_date'     => $this->faker->date(),
            'end_date'       => $this->faker->date(),
            'organization_id'=> \Modules\Organizations\Models\Organization::factory(),
        ];
    }
}
