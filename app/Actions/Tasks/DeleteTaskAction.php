<?php
namespace App\Actions\Tasks;

use App\Models\Task;

class DeleteTaskAction
{
    public function handle(int $taskId): void
    {
        Task::where('id', $taskId)->delete();
    }
}
