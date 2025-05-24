<?php

namespace App\Actions\Projects;

use App\Models\Project;

class UpdateProjectAction
{
    public function handle(array $data, int $projectId): Project
    {
        return Project::where('id', $projectId)
            ->update([
                'name' => $data['name'],
                'description' => $data['description'],
            ]);
    }
}
