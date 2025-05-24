<?php

namespace App\Http\Api\Controllers;

use App\Actions\Tasks\CreateTaskAction;
use App\Actions\Tasks\DeleteTaskAction;
use App\Actions\Tasks\UpdateStatusTaskAction;
use App\Actions\Tasks\UpdateTaskAction;
use App\Http\Controller;
use App\Http\Requests\Task\StoreTaskRequest;
use App\Http\Requests\Task\UpdateTaskRequest;
use App\Http\Requests\Task\UpdateTaskStatusRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

/**
 * @OA\Tag(
 *     name="Tasks",
 *     description="API Endpoints for managing tasks"
 * )
 */
class TaskController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/tasks",
     *     summary="List tasks",
     *     tags={"Tasks"},
     *     @OA\Parameter(name="project", in="query", required=true, @OA\Schema(type="integer")),
     *     @OA\Parameter(name="searchTitle", in="query", @OA\Schema(type="string")),
     *     @OA\Parameter(name="searchPriority", in="query", @OA\Schema(type="string")),
     *     @OA\Parameter(name="searchStatus", in="query", @OA\Schema(type="string")),
     *     @OA\Response(
     *         response=200,
     *         description="List of tasks",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/TaskResource"))
     *     )
     * )
     */
    public function list(Request $request): AnonymousResourceCollection
    {
        $tasks = Task::query()
            ->with(['user', 'project'])
            ->where('project_id', $request->project)
            ->where('user_id', auth()->id())
            ->whereNull('deleted_at')
            ->when($request->searchTitle, function ($query) use ($request) {
                return $query->where('title', 'like', "%{$request->searchTitle}%");
            })
            ->when($request->searchPriority, function ($query) use ($request) {
                return $query->where('priority', $request->searchPriority);
            })
            ->when($request->searchStatus, function ($query) use ($request) {
                return $query->where('status', $request->searchStatus);
            })
            ->paginate(20);

        return TaskResource::collection($tasks);
    }

    /**
     * @OA\Get(
     *     path="/api/tasks/{task}",
     *     summary="Show specific task",
     *     description="Show specific task by ID.",
     *     operationId="getTaskById",
     *     tags={"Tasks"},
     *     security={{"sanctum":{}}},
     *
     *     @OA\Parameter(
     *         name="task",
     *         in="path",
     *         required=true,
     *         description="task id",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Show a especific task",
     *         @OA\JsonContent(ref="#/components/schemas/TaskResource")
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Not found"
     *     )
     * )
     */
    public function show(int $task): TaskResource
    {
        $task = Task::query()
            ->with(['user', 'project'])
            ->where('id', $task)
            ->whereNull('deleted_at')
            ->firstOrFail();

        return new TaskResource($task);
    }

    /**
     * @OA\Post(
     *     path="/api/tasks",
     *     summary="Create a new task",
     *     tags={"Tasks"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/StoreTaskRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Task created",
     *         @OA\JsonContent(ref="#/components/schemas/TaskResource")
     *     )
     * )
     */
    public function store(StoreTaskRequest $request, CreateTaskAction $createTask): TaskResource
    {
        $validated = [
            ...$request->validated(),
            'user_id' => auth()->id(),
        ];

        $newTask = $createTask->handle($validated);

        return new TaskResource($newTask);
    }

    /**
     * @OA\Put(
     *     path="/api/tasks/{id}",
     *     summary="Update an existing task",
     *     tags={"Tasks"},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UpdateTaskRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Task updated",
     *         @OA\JsonContent(ref="#/components/schemas/TaskResource")
     *     )
     * )
     */
    public function update(UpdateTaskRequest $request, int $taskId, UpdateTaskAction $updateTask): TaskResource
    {
        $task = $updateTask->handle([
            ...$request->validated(),
            'id' => $taskId
        ]);

        return new TaskResource($task);
    }

    /**
     * @OA\Put(
     *     path="/api/tasks/{id}/status",
     *     summary="Update task status",
     *     tags={"Tasks"},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UpdateTaskStatusRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Task status updated",
     *         @OA\JsonContent(ref="#/components/schemas/TaskResource")
     *     )
     * )
     */
    public function updateStatus(UpdateTaskStatusRequest $request, int $taskId, UpdateStatusTaskAction $updateStatusTaskAction): TaskResource
    {
        $task = $updateStatusTaskAction->handle([
            ...$request->validated(),
            'id' => $taskId
        ]);

        return new TaskResource($task);
    }

    /**
     * @OA\Delete(
     *     path="/api/tasks/{id}",
     *     summary="Delete a task",
     *     tags={"Tasks"},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(
     *         response=200,
     *         description="Task deleted",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Task Deleted")
     *         )
     *     )
     * )
     */
    public function delete(Task $task, DeleteTaskAction $deleteTask): JsonResponse
    {
        $deleteTask->handle($task->id);

        return response()->json([
            'message' => 'Task Deleted'
        ], Response::HTTP_OK);
    }
}
