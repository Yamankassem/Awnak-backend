<?php

namespace Modules\Applications\Database\Factories;

use Modules\Applications\Models\Application;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = \Modules\Applications\Models\TaskFactory::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'application_id' => Application::factory() ,
            'title'          =>$this->faker->sentence ,
            'description'    =>$this->faker->paragraph ,
            'status'         =>$this->faker->randomElement(['active','complete']) ,
            'due_date'   =>$this->faker->dateTimeBetween('now','+1 month') ,
        ];
    }
}

