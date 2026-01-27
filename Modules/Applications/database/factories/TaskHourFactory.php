<?php

namespace Modules\Applications\Database\Factories;

use Modules\Applications\Models\Task;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskHourFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = \Modules\Applications\Models\TaskHourFactory::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'task_id'      => Task::factory(),
            'hours'        =>$this->faker->numberBetween(1,8) ,
            'started_date' =>$this->faker->date('Y-m-d H:i:s') ,
            'ended_date'   =>$this->faker->date('Y-m-d H:i:s') ,
            'note'         =>$this->faker->sentence ,
        ];
    }
}

