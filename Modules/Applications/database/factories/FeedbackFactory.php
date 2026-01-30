<?php

namespace Modules\Applications\Database\Factories;

use Modules\Applications\Models\Task;
use Illuminate\Database\Eloquent\Factories\Factory;

class FeedbackFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = \Modules\Applications\Models\FeedbackFactory::class;

    /**
     * Define the model's default state.
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

