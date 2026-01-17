<?php

namespace Modules\Organizations\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Organizations\Models\Organization;

class OrganizationFactory extends Factory
{
    protected $model = Organization::class;

    public function definition()
    {
        return [
            'license_number' => $this->faker->unique()->bothify('ORG###'),
            'type'           => $this->faker->randomElement(['NGO', 'Company']),
            'bio'            => $this->faker->sentence(),
            'website'        => $this->faker->url(),
        ];
    }
}
