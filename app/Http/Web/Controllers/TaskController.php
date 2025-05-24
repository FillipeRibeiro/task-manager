<?php

namespace App\Http\Web\Controllers;

use App\Actions\Tasks\CreateTaskAction;
use App\Actions\Tasks\DeleteTaskAction;
use App\Actions\Tasks\UpdateStatusTaskAction;
use App\Actions\Tasks\UpdateTaskAction;
use App\Http\Controller;
use App\Http\Requests\Task\StoreTaskRequest;
use App\Http\Requests\Task\UpdateTaskRequest;
use App\Http\Requests\Task\UpdateTaskStatusRequest;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class TaskController extends Controller
{
    public function __construct(
        private CreateTaskAction $createTask,
        private DeleteTaskAction $deleteTask,
        private UpdateTaskAction $updateTask,
        private UpdateStatusTaskAction $updateStatusTaskAction
    ) {}

    public function list(Request $request): Response
    {
        $tasks = Task::query()
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
            ->get();

        return Inertia::render('Task/Index', [
            'project_id' => (int) $request->project,
            'tasks' => $tasks
        ]);
    }

    public function create(Request $request): Response
    {
        return Inertia::render('Task/CreateTaskForm', [
            'project_id' => $request->project,
        ]);
    }

    public function store(StoreTaskRequest $request): RedirectResponse
    {
        $validated = [
            ...$request->validated(),
            'user_id' => auth()->id(),
        ];

        $newTask = $this->createTask->handle($validated);

        return redirect()
            ->route('tasks.list', ['project' => $newTask->project_id])
            ->with('success', __('Task Created'));
    }

    public function edit(Task $task): Response
    {
        return Inertia::render('Task/EditTaskForm', [
            'id' => $task->id,
            'title' => $task->title,
            'priority' => $task->priority,
            'project_id' => $task->project_id,
            'description' => $task->description,
            'due_date' => Carbon::parse($task->due_date)->format('Y-m-d'),
        ]);
    }

    public function update(UpdateTaskRequest $request, int $taskId): RedirectResponse
    {
        $task = $this->updateTask->handle([
            ...$request->validated(),
            'id' => $taskId
        ]);

        return redirect()
            ->route('tasks.list', ['project' => $task->project_id])
            ->with('success', 'Task Updated.');
    }

    public function updateStatus(UpdateTaskStatusRequest $request, int $taskId): RedirectResponse
    {
        $task = $this->updateStatusTaskAction->handle([
            ...$request->validated(),
            'id' => $taskId
        ]);

        return redirect()
            ->route('tasks.list', ['project' => $task->project_id])
            ->with('success', 'Task Updated.');
    }

    public function delete(Task $task): RedirectResponse
    {
        $projectId = (int) $task->project_id;

        $this->deleteTask->handle($task->id);

        return redirect()
            ->route('tasks.list', ['project' => $projectId])
            ->with('success', 'Task Deleted');
    }
}
