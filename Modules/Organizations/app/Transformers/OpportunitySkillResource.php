<?php

namespace Modules\Organizations\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class OpportunitySkillResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'             => $this->id,
            'opportunity_id' => $this->opportunity_id,
            'skill_id'       => $this->skill_id,
            'created_at'     => $this->created_at,
            'updated_at'     => $this->updated_at,
        ];
    }
}
