<?php

namespace App\Http\Requests\Task;

use App\Models\Task;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * @OA\Schema(
 *     schema="UpdateTaskStatusRequest",
 *     title="Update Task Status Request",
 *     description="Update task status",
 *     type="object",
 *     required={"status"},
 *     @OA\Property(
 *         property="status",
 *         type="string",
 *         enum={"pending", "in_progress", "completed"},
 *         example="in_progress"
 *     )
 * )
 */
class UpdateTaskStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'status' => ['required', 'string', Rule::in([
                Task::STATUS_TODO,
                Task::STATUS_ON_GOING,
                Task::STATUS_DONE,
            ])],
        ];
    }
}
