<?php

namespace Modules\Applications\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskHourResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'hours' => $this->hours,
            'started_date'  =>$this->started_date->format('Y-m-d'),
            'ended_date'    =>$this->ended_date->format('Y-m-d'),
            'note'           => $this->note,
            'created_at'    => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at'    => $this->updated_at->format('Y-m-d H:i:s'),
            'deleted_at'    => $this->deleted_at->format('Y-m-d H:i:s'),
        ];
    }
}
