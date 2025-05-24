<?php

namespace App\Actions\Projects;

use App\Models\Project;

class CreateProjectAction
{
    public function handle(array $data): Project
    {
        return Project::create([
            'name' => $data['name'],
            'user_id' => $data['user_id'],
            'description' => $data['description'],
        ]);
    }
}
