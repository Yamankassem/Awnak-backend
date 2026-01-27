<?php

namespace Modules\Volunteers\Database\Factories;

use Modules\Core\Models\User;
use Modules\Core\Models\Location;
use Illuminate\Database\Eloquent\Factories\Factory;

class VolunteerProfileFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = \Modules\Volunteers\Models\VolunteerProfile::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
         $isVerified = $this->faker->boolean(30);

        return [
            'user_id' => User::factory(),
            'location_id' => Location::factory(),

            'first_name' => $this->faker->firstName(),
            'last_name'  => $this->faker->lastName(),
            'phone'      => $this->faker->phoneNumber(),
            'gender'     => $this->faker->randomElement(['male', 'female', 'other']),
            'birth_date' => $this->faker->dateTimeBetween('-50 years', '-18 years')->format('Y-m-d'),
            'bio'        => $this->faker->paragraph(),

            'experience_years' => $this->faker->numberBetween(0, 15),
            'previous_experience_details' => $this->faker->optional()->paragraph(),

            'status' => $this->faker->randomElement(['active', 'inactive']),
            'is_verified' => $isVerified,
            'verified_at' => $isVerified ? now() : null,
        ];
    }

    /**
     * State: verified volunteer
     */
    public function verified(): static
    {
        return $this->state(fn () => [
            'is_verified' => true,
            'verified_at' => now(),
        ]);
    }

    /**
     * State: inactive volunteer
     */
    public function inactive(): static
    {
        return $this->state(fn () => [
            'status' => 'inactive',
        ]);
    }
}
