<?php
namespace App\Actions\Tasks;

use App\Models\Task;

class UpdateTaskAction
{
    public function handle(array $data): Task
    {
        return Task::where('id', $data['id'])
            ->update([
            'title' => $data['title'],
            'description' => $data['description'],
            'due_date' => $data['due_date'],
            'priority' => $data['priority']
        ]);
    }
}
