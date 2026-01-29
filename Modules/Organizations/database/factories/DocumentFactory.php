<?php

namespace Modules\Organizations\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Organizations\Models\Document;
use Modules\Organizations\Models\Opportunity;

/**
 * Factory: DocumentFactory
 *
 * Generates fake data for testing and seeding Document entities.
 * Since file handling is managed by Spatie Media Library, only
 * basic attributes (title, description, opportunity_id) are seeded here.
 */
class DocumentFactory extends Factory
{
    protected $model = Document::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title'          => $this->faker->sentence(3),
            'description'    => $this->faker->paragraph(),
            'opportunity_id' => Opportunity::factory(),
        ];
    }
}
