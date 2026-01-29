<?php

namespace Modules\Applications\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FeedbackResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name_of_org'   => $this->name_of_org,
            'name_of_vol'   => $this->name_of_vol,
            'rating'        => $this->rating,
            'comment'       => $this->comment,
            'created_at'    => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at'    => $this->updated_at->format('Y-m-d H:i:s'),
            'deleted_at'    => $this->deleted_at->format('Y-m-d H:i:s'),
        ];
    }
}
