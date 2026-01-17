<?php

namespace Modules\Organizations\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * OpportunitySkillResource
 *
 * This transformer is responsible for converting the OpportunitySkill model
 * into a structured JSON response. It ensures that the API output
 * is consistent and easy to consume by frontend or external clients.
 */
class OpportunitySkillResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     *
     * Each key in the returned array represents a field that will be
     * included in the JSON response. This helps control exactly what
     * data is exposed from the OpportunitySkill model.
     */
    public function toArray($request)
    {
        return [
            // Unique identifier for the record
            'id'             => $this->id,

            // Foreign key linking to the related opportunity
            'opportunity_id' => $this->opportunity_id,

            // Foreign key linking to the related skill
            'skill_id'       => $this->skill_id,

            // Timestamp when the record was created
            'created_at'     => $this->created_at,

            // Timestamp when the record was last updated
            'updated_at'     => $this->updated_at,
        ];
    }
}
