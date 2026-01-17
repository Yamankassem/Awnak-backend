<?php

namespace Modules\Organizations\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Organizations\Models\Document;

class DocumentFactory extends Factory
{
    protected $model = Document::class;

    public function definition()
    {
        return [
            'title' => $this->faker->sentence(3),
            'file_path' => $this->faker->filePath(),
            'file_type' => $this->faker->mimeType(),
            'file_size' => $this->faker->numberBetween(100, 5000),
            'opportunity_id' => \Modules\Organizations\Models\Opportunity::factory(),
        ];
    }
}
