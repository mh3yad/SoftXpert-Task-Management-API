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
            'assignee' => new UserResource($this->whenLoaded('assignee')),
            'creator' => new UserResource($this->whenLoaded('creator')),
            'due_date' => $this->due_date,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'additional_info' => [
                'dependencies' => [
                     TaskDependencyResource::collection($this->whenLoaded('dependencies'))
                ],
                'dependents' => [
                     TaskDependencyResource::collection($this->whenLoaded('dependents'))
                ]
            ],
            'dependencies_completed' => $this->allDependenciesCompleted()
        ];
    }
}
