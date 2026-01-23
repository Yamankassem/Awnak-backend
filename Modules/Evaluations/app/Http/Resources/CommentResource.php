<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
         return [
            'id'                  => $this->id,
            'content'             => $this->content,
            'status'              => $this->status,
            'user'                => [
                'id'              => $this->user->id ?? null,
                'name'            => $this->user->name ?? null,
                'email'           => $this->user->email ?? null,
            ],
            'post'             => [
                'post_id'      => $this->post->id ?? null,
                'post_title'    => $this->post->title ?? null,
            ],
            'created_at'          => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at'          => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
