<?php
namespace App\Actions\Tasks;

use App\Models\Task;

class CreateTaskAction
{
    public function handle(array $data): Task
    {
        return Task::create([
            'user_id' => $data['user_id'],
            'project_id' => $data['project_id'],
            'title' => $data['title'],
            'priority' => $data['priority'],
            'due_date' => $data['due_date'],
            'status' => Task::STATUS_TODO,
            'description' => $data['description'],
        ]);
    }
}
