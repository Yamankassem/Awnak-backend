<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
         return [
            'id'            => $this->id,
            'title'         => $this->title,
            'content'       => $this->content,
            'photo'         => $this->photo ?? null,
            'user'                => [
                'id'              => $this->user->id ?? null,
                'name'            => $this->user->name ?? null,
                'email'           => $this->user->email ?? null,
            ],
            'category'             => [
                'category_id'      => $this->category->id ?? null,
                'category_name'    => $this->category->name ?? null,
            ],
            'created_at'          => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at'          => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
