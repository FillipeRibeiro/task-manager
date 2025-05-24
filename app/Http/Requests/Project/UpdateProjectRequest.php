<?php

namespace App\Http\Requests\Project;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     schema="UpdateProjectRequest",
 *     title="Update Project Request",
 *     description="Update project data",
 *     type="object",
 *     @OA\Property(
 *         property="name",
 *         type="string",
 *         example="Project updated"
 *     ),
 *     @OA\Property(
 *         property="description",
 *         type="string",
 *         nullable=true,
 *         example="Description updated"
 *     )
 * )
 */
class UpdateProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'string'],
            'description' => ['nullable', 'string'],
        ];
    }
}
