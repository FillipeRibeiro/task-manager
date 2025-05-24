<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="TaskResource",
 *     type="object",
 *     title="Task Resource",
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="user_id", type="integer"),
 *     @OA\Property(property="project_id", type="integer"),
 *     @OA\Property(property="title", type="string"),
 *     @OA\Property(property="status", type="string"),
 *     @OA\Property(property="due_date", type="string", format="date"),
 *     @OA\Property(property="priority", type="string"),
 *     @OA\Property(property="description", type="string"),
 *     @OA\Property(property="created_at", type="string", format="date"),
 *     @OA\Property(property="updated_at", type="string", format="date"),
 *     @OA\Property(property="deleted_at", type="string", format="date", nullable=true)
 * )
 */
class TaskResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'project_id' => $this->project_id,
            'title' => $this->title,
            'status' => $this->status,
            'due_date' => $this->due_date,
            'priority' => $this->priority,
            'description' => $this->description,
            'user' => new UserResource($this->whenLoaded('user')),
            'project' => new ProjectResource($this->whenLoaded('project')),
            'created_at' => Carbon::parse($this->created_at)->format('m-d-Y'),
            'updated_at' => Carbon::parse($this->updated_at)->format('m-d-Y'),
            'deleted_at' => Carbon::parse($this->deleted_at)->format('m-d-Y'),
        ];
    }
}
