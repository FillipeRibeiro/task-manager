<?php

namespace App\Http\Requests\Task;

use App\Models\Task;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * @OA\Schema(
 *     schema="StoreTaskRequest",
 *     title="Store Task Request",
 *     description="Create new task",
 *     type="object",
 *     required={"project_id", "title", "due_date", "priority"},
 *     @OA\Property(
 *         property="project_id",
 *         type="integer",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="title",
 *         type="string",
 *         maxLength=80,
 *         example="Set goal"
 *     ),
 *     @OA\Property(
 *         property="description",
 *         type="string",
 *         maxLength=240,
 *         nullable=true,
 *         example="Discuss goals"
 *     ),
 *     @OA\Property(
 *         property="due_date",
 *         type="string",
 *         format="date",
 *         example="2025-06-01"
 *     ),
 *     @OA\Property(
 *         property="priority",
 *         type="string",
 *         enum={"low", "medium", "high"},
 *         example="high"
 *     )
 * )
 */
class StoreTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'project_id' => ['required', 'exists:projects,id'],
            'title' => ['required', 'string', 'max:80'],
            'description' => ['nullable', 'string', 'max:240'],
            'due_date' => ['required', 'date'],
            'priority' => ['required', 'string', Rule::in([
                Task::PRIORITY_LOW,
                Task::PRIORITY_MEDIUM,
                Task::PRIORITY_HIGH,
            ])],
        ];
    }
}
