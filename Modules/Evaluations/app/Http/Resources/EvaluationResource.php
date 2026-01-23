<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EvaluationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
         return [
            'id' => $this->id,
            'task_id' => $this->task_id,
            'volunteer_id' => $this->volunteer_id,
            'evaluator_id' => $this->evaluator_id,
            'score' => $this->score,
            'strengths' => $this->strengths,
            'improvement' => $this->improvement,
            'evaluated_at' => optional($this->evaluated_at)->toDateTimeString(),
            'created_at' => $this->created_at?->toDateTimeString(),
            'updated_at' => $this->updated_at?->toDateTimeString(),
         ];
    }
}
