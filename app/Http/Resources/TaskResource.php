<?php

namespace App\Http\Resources;

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
            'description' => $this->description,
            'status' => $this->status,
            'due_date' => date_format($this->due_date,'Y M d H:i:s'),
            'dependencies_completed' => $this->allDependenciesCompleted(),
            'additional_info' => [
                'assignee' => new UserResource($this->whenLoaded('assignee')),
                'creator' => new UserResource($this->whenLoaded('creator')),
                'dependencyTasks' => TaskDependencyResource::collection($this->whenLoaded('dependencyTasks')),
            ],
            'created_at' => date_format($this->created_at,'Y M d H:i:s'),
            'updated_at' => date_format($this->updated_at,'Y M d H:i:s')
        ];
    }
}
