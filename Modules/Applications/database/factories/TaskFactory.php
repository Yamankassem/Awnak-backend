<?php

namespace Modules\Applications\Database\Factories;

use Modules\Applications\Models\Application;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Task Factory
 * 
 * Generates fake task data for testing and seeding.
 * 
 * @package Modules\Applications\Database\Factories
 * @author Your Name
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\Modules\Applications\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     * 
     * @var string
     */
    protected $model = \Modules\Applications\Models\TaskFactory::class;

    /**
     * Define the model's default state.
     * 
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'application_id' => Application::factory() ,
            'title'          =>$this->faker->sentence ,
            'description'    =>$this->faker->paragraph ,
            'status'         =>$this->faker->randomElement(['preparation','active','complete','cancelled']) ,
            'due_date'   =>$this->faker->dateTimeBetween('now','+1 month') ,
        ];
    }
}

