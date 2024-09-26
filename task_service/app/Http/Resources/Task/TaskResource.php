<?php

namespace App\Http\Resources\Task;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
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
            'title' => $this->title,
            'content' => $this->content,
            'status' => $this->status,
            'tags' => $this->tags,
            'started_at' => $this->started_at->format('Y-m-d H:i'),,
            'finished_at' => $this->finished_at->format('Y-m-d H:i'),,
            'created_at' => $this->created_at->format('Y-m-d H:i'),
        ];
    }
}
