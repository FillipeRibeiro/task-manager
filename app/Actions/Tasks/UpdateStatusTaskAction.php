<?php
namespace App\Actions\Tasks;

use App\Models\Task;

class UpdateStatusTaskAction
{
    public function handle(array $data): Task
    {
        $task = Task::findOrFail($data['id']);

        $task->update(['status' => $data['status']]);

        return $task;
    }
}
