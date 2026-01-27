<?php

namespace Modules\Volunteers\Database\Factories;

use Modules\Volunteers\Models\Skill;
use Illuminate\Database\Eloquent\Factories\Factory;

class VolunteerSkillFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = \Modules\Volunteers\Models\VolunteerSkill::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'skill_id' => Skill::factory(),
            'level' => $this->faker->randomElement(['beginner','intermediate','advanced','expert']),
        ];
    }
}

