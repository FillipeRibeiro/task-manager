<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="UserResource",
 *     type="object",
 *     title="User Resource",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="John Doe"),
 *     @OA\Property(property="email", type="string", format="email", example="john@example.com"),
 *     @OA\Property(property="created_at", type="string", format="date", example="05-23-2025"),
 *     @OA\Property(property="updated_at", type="string", format="date", example="05-23-2025"),
 *     @OA\Property(property="deleted_at", type="string", format="date", nullable=true, example=null)
 * )
 */
class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'created_at' => Carbon::parse($this->created_at)->format('m-d-Y'),
            'updated_at' => Carbon::parse($this->updated_at)->format('m-d-Y'),
            'deleted_at' => Carbon::parse($this->deleted_at)->format('m-d-Y'),
        ];
    }
}
