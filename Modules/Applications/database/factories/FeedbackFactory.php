<?php

namespace Modules\Applications\Database\Factories;

use Modules\Applications\Models\Task;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Feedback Factory
 * 
 * Generates fake feedback data for testing and seeding.
 * 
 * @package Modules\Applications\Database\Factories
 * @author Your Name
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\Modules\Applications\Models\Feedback>
 */
class FeedbackFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     * 
     * @var string
     */
    protected $model = \Modules\Applications\Models\FeedbackFactory::class;

    /**
     * Define the model's default state.
     * 
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
           'task_id'      => Task::factory(), 
            'name_of_org' =>$this->faker->company,
            'name_of_vol' =>$this->faker->company,
            'rating'      =>$this->faker->numberBetween(1,5),
            'comment'     =>$this->faker->sentence,

        ];
    }
}

