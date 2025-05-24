<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="ProjectResource",
 *     type="object",
 *     title="Project Resource",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="My Project"),
 *     @OA\Property(property="user_id", type="integer", example=5),
 *     @OA\Property(property="description", type="string", example="Project description..."),
 *     @OA\Property(property="created_at", type="string", format="date", example="05-23-2025"),
 *     @OA\Property(property="updated_at", type="string", format="date", example="05-23-2025"),
 *     @OA\Property(property="deleted_at", type="string", format="date", nullable=true, example=null)
 * )
 */
class ProjectResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'user_id' => $this->user_id,
            'description' => $this->description,
            'user' => new UserResource($this->whenLoaded('user')),
            'created_at' => Carbon::parse($this->created_at)->format('m-d-Y'),
            'updated_at' => Carbon::parse($this->updated_at)->format('m-d-Y'),
            'deleted_at' => Carbon::parse($this->deleted_at)->format('m-d-Y'),
        ];
    }
}
