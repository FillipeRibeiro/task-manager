<?php

namespace App\Http\Requests\Task;

use App\Models\Task;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * @OA\Schema(
 *     schema="UpdateTaskRequest",
 *     title="Update Task Request",
 *     description="Data to update task",
 *     type="object",
 *     @OA\Property(
 *         property="title",
 *         type="string",
 *         maxLength=80,
 *         example="New task title"
 *     ),
 *     @OA\Property(
 *         property="description",
 *         type="string",
 *         maxLength=240,
 *         nullable=true,
 *         example="Description updated"
 *     ),
 *     @OA\Property(
 *         property="due_date",
 *         type="string",
 *         format="date",
 *         nullable=true,
 *         example="2025-07-15"
 *     ),
 *     @OA\Property(
 *         property="priority",
 *         type="string",
 *         enum={"low", "medium", "high"},
 *         nullable=true,
 *         example="medium"
 *     )
 * )
 */
class UpdateTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'title' => ['sometimes', 'required', 'string', 'max:80'],
            'description' => ['nullable', 'string', 'max:240'],
            'due_date' => ['nullable', 'date'],
            'priority' => ['nullable', 'string', Rule::in([
                Task::PRIORITY_LOW,
                Task::PRIORITY_MEDIUM,
                Task::PRIORITY_HIGH,
            ])],
        ];
    }
}
